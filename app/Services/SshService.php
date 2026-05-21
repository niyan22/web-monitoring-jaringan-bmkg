<?php

namespace App\Services;

use phpseclib3\Net\SSH2;
use Exception;

class SshService
{
    protected SSH2 $ssh;
    protected string $interface;

    /**
     * Connect to the VirtualBox VM via SSH
     */
    public function connect(): void
    {
        $host = config('vm.ssh_host');
        $port = config('vm.ssh_port');
        $username = config('vm.ssh_username');
        $password = config('vm.ssh_password');
        $this->interface = config('vm.ssh_interface');

        $this->ssh = new SSH2($host, $port, 10);

        if (!$this->ssh->login($username, $password)) {
            throw new Exception("Gagal login SSH ke VM ({$host}:{$port}). Periksa username/password.");
        }
    }

    /**
     * Fetch all network traffic data from VM
     */
    public function fetchNetworkData(): array
    {
        $this->connect();

        $interfaceData  = $this->getInterfaceStats();
        $connectionData = $this->getConnectionStats();
        $speedData      = $this->getSpeedEstimate();

        $this->ssh->disconnect();

        return [
            'interface_name'          => $this->interface,
            'download_speed'          => $speedData['download_speed'],
            'upload_speed'            => $speedData['upload_speed'],
            'packets_sent'            => $interfaceData['packets_sent'],
            'packets_received'        => $interfaceData['packets_received'],
            'bytes_sent'              => $interfaceData['bytes_sent'],
            'bytes_received'          => $interfaceData['bytes_received'],
            'active_connections'      => $connectionData['active'],
            'established_connections' => $connectionData['established'],
        ];
    }

    /**
     * Fetch system metrics (CPU, Memory, Disk, Processor) from VM via SSH
     */
    public function fetchSystemData(): array
    {
        $this->connect();

        // ── CPU Load ─────────────────────────────────────────────────
        // Read /proc/stat twice with 1s delay to compute usage %
        $cpu1 = $this->parseProcStat($this->ssh->exec('cat /proc/stat | grep "^cpu "'));
        $this->ssh->exec('sleep 1');
        $cpu2 = $this->parseProcStat($this->ssh->exec('cat /proc/stat | grep "^cpu "'));

        $cpuLoad = 0;
        if ($cpu1 && $cpu2) {
            $totalDiff = ($cpu2['total'] - $cpu1['total']);
            $idleDiff  = ($cpu2['idle']  - $cpu1['idle']);
            $cpuLoad   = $totalDiff > 0
                ? round((1 - $idleDiff / $totalDiff) * 100, 1)
                : 0;
        }

        // ── Memory ───────────────────────────────────────────────────
        $memInfo = $this->ssh->exec('cat /proc/meminfo');
        $memTotal = $memFree = $memBuffers = $memCached = 0;
        foreach (explode("\n", $memInfo) as $line) {
            if (preg_match('/^MemTotal:\s+(\d+)/', $line, $m))     $memTotal   = (int)$m[1];
            if (preg_match('/^MemFree:\s+(\d+)/', $line, $m))      $memFree    = (int)$m[1];
            if (preg_match('/^Buffers:\s+(\d+)/', $line, $m))      $memBuffers = (int)$m[1];
            if (preg_match('/^Cached:\s+(\d+)/', $line, $m))       $memCached  = (int)$m[1];
        }
        // Convert kB → GB, used = total - free - buffers - cached
        $memUsedKb  = $memTotal - $memFree - $memBuffers - $memCached;
        $memUsedGb  = round($memUsedKb  / 1024 / 1024, 2);
        $memTotalGb = round($memTotal    / 1024 / 1024, 2);

        // ── Disk ─────────────────────────────────────────────────────
        $dfOutput = $this->ssh->exec("df -BG / | tail -1");
        $dfParts  = preg_split('/\s+/', trim($dfOutput));
        // Format: Filesystem 1G-blocks Used Available Use% Mounted
        $diskTotalGb = isset($dfParts[1]) ? (int)rtrim($dfParts[1], 'G') : 0;
        $diskUsedGb  = isset($dfParts[2]) ? (int)rtrim($dfParts[2], 'G') : 0;

        // ── Processor Info ───────────────────────────────────────────
        $cpuInfo = $this->ssh->exec('cat /proc/cpuinfo');
        $procName  = 'Unknown';
        $procCores = 1;
        $procFreq  = 0;
        foreach (explode("\n", $cpuInfo) as $line) {
            if (preg_match('/^model name\s*:\s*(.+)/', $line, $m)) $procName  = trim($m[1]);
            if (preg_match('/^cpu cores\s*:\s*(\d+)/',  $line, $m)) $procCores = (int)$m[1];
            if (preg_match('/^cpu MHz\s*:\s*([\d.]+)/', $line, $m)) $procFreq  = round((float)$m[1] / 1000, 2);
        }

        $this->ssh->disconnect();

        return [
            'cpu_load'            => max(0, min(100, $cpuLoad)),
            'memory_used'         => $memUsedGb,
            'memory_total'        => $memTotalGb ?: 1,
            'disk_used'           => $diskUsedGb,
            'disk_total'          => $diskTotalGb ?: 1,
            'processor_name'      => $procName,
            'processor_cores'     => $procCores,
            'processor_frequency' => $procFreq,
        ];
    }

    /**
     * Parse "cpu ..." line from /proc/stat → ['total' => n, 'idle' => n]
     */
    protected function parseProcStat(string $line): ?array
    {
        $line  = trim($line);
        $parts = preg_split('/\s+/', $line);
        // Format: cpu user nice system idle iowait irq softirq steal guest guest_nice
        if (count($parts) < 5 || $parts[0] !== 'cpu') return null;
        $idle  = (int)$parts[4];
        $total = array_sum(array_slice(array_map('intval', $parts), 1));
        return ['total' => $total, 'idle' => $idle];
    }

    /**
     * Get interface stats from /proc/net/dev
     */
    protected function getInterfaceStats(): array
    {
        $output = $this->ssh->exec("cat /proc/net/dev");

        if (empty($output)) {
            throw new Exception("Gagal membaca /proc/net/dev dari VM.");
        }

        $lines = explode("\n", trim($output));

        foreach ($lines as $line) {
            // Format: Interface: bytes packets errs drop fifo frame compressed multicast | bytes packets ...
            if (str_contains($line, $this->interface . ':')) {
                $line = trim(str_replace($this->interface . ':', '', $line));
                $parts = preg_split('/\s+/', $line);

                if (count($parts) >= 10) {
                    return [
                        'bytes_received' => (int) $parts[0],
                        'packets_received' => (int) $parts[1],
                        'bytes_sent' => (int) $parts[8],
                        'packets_sent' => (int) $parts[9],
                    ];
                }
            }
        }

        throw new Exception("Interface '{$this->interface}' tidak ditemukan di VM. Periksa nama interface di .env (VM_SSH_INTERFACE).");
    }

    /**
     * Get connection stats using ss command
     */
    protected function getConnectionStats(): array
    {
        $output = $this->ssh->exec("ss -s");

        $active = 0;
        $established = 0;

        if (!empty($output)) {
            $lines = explode("\n", $output);
            foreach ($lines as $line) {
                // TCP line: "TCP:   X (estab Y, closed Z, orphaned A, timewait B)"
                if (preg_match('/^TCP:\s+(\d+)/', $line, $totalMatch)) {
                    $active = (int) $totalMatch[1];
                }
                if (preg_match('/estab\s+(\d+)/', $line, $estabMatch)) {
                    $established = (int) $estabMatch[1];
                }
            }
        }

        return [
            'active' => $active,
            'established' => $established,
        ];
    }

    /**
     * Estimate network speed by measuring bytes delta over 2 seconds
     */
    protected function getSpeedEstimate(): array
    {
        // First reading
        $output1 = $this->ssh->exec("cat /proc/net/dev | grep '{$this->interface}'");
        $stats1 = $this->parseDevLine($output1);

        // Wait 2 seconds
        $this->ssh->exec("sleep 2");

        // Second reading
        $output2 = $this->ssh->exec("cat /proc/net/dev | grep '{$this->interface}'");
        $stats2 = $this->parseDevLine($output2);

        if ($stats1 && $stats2) {
            // Calculate Mbps: (delta bytes * 8) / (2 seconds * 1,000,000)
            $downloadSpeed = round(($stats2['rx_bytes'] - $stats1['rx_bytes']) * 8 / (2 * 1000000), 2);
            $uploadSpeed = round(($stats2['tx_bytes'] - $stats1['tx_bytes']) * 8 / (2 * 1000000), 2);

            return [
                'download_speed' => max(0, $downloadSpeed),
                'upload_speed' => max(0, $uploadSpeed),
            ];
        }

        return ['download_speed' => 0, 'upload_speed' => 0];
    }

    /**
     * Parse a single line from /proc/net/dev
     */
    protected function parseDevLine(string $output): ?array
    {
        $line = trim($output);
        if (empty($line)) {
            return null;
        }

        if (str_contains($line, $this->interface . ':')) {
            $line = trim(str_replace($this->interface . ':', '', $line));
        }

        $parts = preg_split('/\s+/', trim($line));

        if (count($parts) >= 10) {
            return [
                'rx_bytes' => (int) $parts[0],
                'tx_bytes' => (int) $parts[8],
            ];
        }

        return null;
    }
}
