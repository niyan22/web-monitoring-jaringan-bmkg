<?php

namespace App\Services;

use Exception;

class SnmpService
{
    protected string $host;
    protected string $community;
    protected int    $port;
    protected int    $timeout;
    protected int    $retries;
    protected string $interfaceName;
    protected int    $ifIndex;

    public function __construct()
    {
        $this->host          = config('snmp.host');
        $this->community     = config('snmp.community');
        $this->port          = config('snmp.port', 161);
        $this->timeout       = config('snmp.timeout', 1000000);
        $this->retries       = config('snmp.retries', 3);
        $this->interfaceName = config('snmp.interface_name', 'ether1');
        $this->ifIndex       = config('snmp.interface_index', 1);

        if (!extension_loaded('snmp')) {
            throw new Exception('PHP extension SNMP tidak aktif. Aktifkan extension=snmp di php.ini.');
        }

        // Nonaktifkan error output SNMP bawaan; error ditangani manual
        snmp_set_quick_print(true);
    }

    // =========================================================================
    //  PUBLIC: Data Sistem (CPU, Memory, Disk, Processor)
    // =========================================================================

    /**
     * Ambil data performa sistem dari Mikrotik via SNMP.
     */
    public function fetchSystemData(): array
    {
        // ── CPU Load (HOST-RESOURCES-MIB::hrProcessorLoad.1) ────────────────
        // OID ini mengembalikan persentase CPU 0–100
        $cpuLoad = (float) $this->get('1.3.6.1.2.1.25.3.3.1.2.1');

        // ── Memory ──────────────────────────────────────────────────────────
        [$memUsedGb, $memTotalGb] = $this->getMemoryInfo();

        // ── Disk ─────────────────────────────────────────────────────────────
        [$diskUsedGb, $diskTotalGb] = $this->getDiskInfo();

        // ── Processor Info ────────────────────────────────────────────────────
        // sysDescr (.1.3.6.1.2.1.1.1.0) → nama/versi sistem Mikrotik
        $sysDescr  = $this->get('1.3.6.1.2.1.1.1.0') ?: 'MikroTik RouterOS';
        // hrDeviceDescr.1 → deskripsi CPU
        $cpuDescr  = $this->get('1.3.6.1.2.1.25.3.2.1.3.1') ?: $sysDescr;

        // Jumlah CPU core & frekuensi: Mikrotik tidak expose via SNMP standar,
        // ambil dari sysDescr (biasanya berisi info CPU jika CHR)
        $cores    = 1;
        $freqGhz  = 0.0;

        return [
            'cpu_load'            => max(0.0, min(100.0, round($cpuLoad, 1))),
            'memory_used'         => $memUsedGb,
            'memory_total'        => $memTotalGb ?: 1.0,
            'disk_used'           => $diskUsedGb,
            'disk_total'          => $diskTotalGb ?: 1.0,
            'processor_name'      => $cpuDescr,
            'processor_cores'     => $cores,
            'processor_frequency' => $freqGhz,
        ];
    }

    // =========================================================================
    //  PUBLIC: Data Traffic Jaringan
    // =========================================================================

    /**
     * Ambil data traffic jaringan dari Mikrotik via SNMP.
     * Mengukur kecepatan dengan membaca counter dua kali (interval 2 detik).
     */
    public function fetchNetworkData(): array
    {
        $idx = $this->ifIndex;

        // ── Pembacaan pertama ──────────────────────────────────────────────
        $rx1  = (float) $this->get("1.3.6.1.2.1.2.2.1.10.{$idx}"); // ifInOctets
        $tx1  = (float) $this->get("1.3.6.1.2.1.2.2.1.16.{$idx}"); // ifOutOctets

        // ── Tunggu 2 detik ─────────────────────────────────────────────────
        sleep(2);

        // ── Pembacaan kedua ────────────────────────────────────────────────
        $rx2  = (float) $this->get("1.3.6.1.2.1.2.2.1.10.{$idx}"); // ifInOctets
        $tx2  = (float) $this->get("1.3.6.1.2.1.2.2.1.16.{$idx}"); // ifOutOctets

        // ── Hitung kecepatan dalam Mbps ────────────────────────────────────
        // Rumus: (delta_bytes × 8 bit/byte) ÷ (2 detik × 1.000.000 bit/Mbps)
        $downloadSpeed = max(0.0, round(($rx2 - $rx1) * 8 / (2 * 1_000_000), 2));
        $uploadSpeed   = max(0.0, round(($tx2 - $tx1) * 8 / (2 * 1_000_000), 2));

        // ── Paket ──────────────────────────────────────────────────────────
        $pktReceived = (int) $this->get("1.3.6.1.2.1.2.2.1.11.{$idx}"); // ifInUcastPkts
        $pktSent     = (int) $this->get("1.3.6.1.2.1.2.2.1.17.{$idx}"); // ifOutUcastPkts

        // ── TCP Connections ───────────────────────────────────────────────
        // tcpCurrEstab: jumlah koneksi TCP yang sedang ESTABLISHED
        $tcpEstab = (int) $this->get('1.3.6.1.2.1.6.9.0');

        // ── Total Connections (active) ─────────────────────────────────────
        // Mikrotik: gunakan tcpConnTable count atau fallback ke tcpEstab
        $tcpActive = $tcpEstab;

        return [
            'interface_name'          => $this->interfaceName,
            'download_speed'          => $downloadSpeed,
            'upload_speed'            => $uploadSpeed,
            'packets_sent'            => $pktSent,
            'packets_received'        => $pktReceived,
            'bytes_sent'              => (int) $tx2,
            'bytes_received'          => (int) $rx2,
            'active_connections'      => $tcpActive,
            'established_connections' => $tcpEstab,
        ];
    }

    // =========================================================================
    //  PRIVATE HELPERS
    // =========================================================================

    /**
     * Eksekusi SNMP GET untuk satu OID.
     * Mengembalikan nilai bersih (tanpa prefix type SNMP seperti "INTEGER: ").
     */
    protected function get(string $oid): string|int|float
    {
        $raw = @snmpget(
            $this->host,
            $this->community,
            $oid,
            $this->timeout,
            $this->retries
        );

        if ($raw === false || $raw === null) {
            return 0;
        }

        return $this->parseSnmpValue((string) $raw);
    }

    /**
     * Eksekusi SNMP WALK untuk subtree OID.
     * Mengembalikan array [oid_suffix => value].
     */
    protected function walk(string $oid): array
    {
        $raw = @snmpwalk(
            $this->host,
            $this->community,
            $oid,
            $this->timeout,
            $this->retries
        );

        if (!is_array($raw)) {
            return [];
        }

        $result = [];
        foreach ($raw as $value) {
            $result[] = $this->parseSnmpValue((string) $value);
        }

        return $result;
    }

    /**
     * Parse nilai mentah SNMP (misal: "INTEGER: 42", "STRING: ether1", "Gauge32: 1024").
     */
    protected function parseSnmpValue(string $raw): string|int|float
    {
        // Format: "TYPE: VALUE" — ambil VALUE-nya saja
        if (preg_match('/^(?:\w+\s*\d*\s*):\s+(.+)$/', $raw, $m)) {
            $val = trim($m[1], " \t\n\r\0\x0B\"");
            if (is_numeric($val)) {
                return strpos($val, '.') !== false ? (float) $val : (int) $val;
            }
            return $val;
        }

        $trimmed = trim($raw, " \t\n\r\0\x0B\"");
        if (is_numeric($trimmed)) {
            return strpos($trimmed, '.') !== false ? (float) $trimmed : (int) $trimmed;
        }

        return $trimmed;
    }

    /**
     * Ambil info memori dari hrStorage (HOST-RESOURCES-MIB).
     * Mikrotik RouterOS menaruh RAM di index 65536.
     *
     * @return array [usedGb, totalGb]
     */
    protected function getMemoryInfo(): array
    {
        // Coba index standar Mikrotik (65536)
        $allocUnit = (int) $this->get('1.3.6.1.2.1.25.2.3.1.4.65536'); // hrStorageAllocationUnits
        $sizeKU    = (int) $this->get('1.3.6.1.2.1.25.2.3.1.5.65536'); // hrStorageSize (dalam unit)
        $usedKU    = (int) $this->get('1.3.6.1.2.1.25.2.3.1.6.65536'); // hrStorageUsed (dalam unit)

        if ($allocUnit > 0 && $sizeKU > 0) {
            $totalBytes = $allocUnit * $sizeKU;
            $usedBytes  = $allocUnit * $usedKU;
            return [
                round($usedBytes  / 1024 / 1024 / 1024, 2),
                round($totalBytes / 1024 / 1024 / 1024, 2),
            ];
        }

        // Fallback: walk hrStorageDescr untuk cari index RAM
        $descs = $this->walk('1.3.6.1.2.1.25.2.3.1.2');
        foreach ($descs as $i => $desc) {
            $descStr = strtolower((string) $desc);
            if (str_contains($descStr, 'ram') || str_contains($descStr, 'memory') || str_contains($descStr, 'real')) {
                // index SNMP dimulai dari 1
                $snmpIndex = $i + 1;
                $alloc = (int) $this->get("1.3.6.1.2.1.25.2.3.1.4.{$snmpIndex}");
                $total = (int) $this->get("1.3.6.1.2.1.25.2.3.1.5.{$snmpIndex}");
                $used  = (int) $this->get("1.3.6.1.2.1.25.2.3.1.6.{$snmpIndex}");
                if ($alloc > 0 && $total > 0) {
                    return [
                        round($alloc * $used  / 1024 / 1024 / 1024, 2),
                        round($alloc * $total / 1024 / 1024 / 1024, 2),
                    ];
                }
            }
        }

        return [0.0, 1.0]; // fallback jika tidak ditemukan
    }

    /**
     * Ambil info disk / flash dari hrStorage.
     * Mikrotik RouterOS menyimpan storage di index selain 65536.
     *
     * @return array [usedGb, totalGb]
     */
    protected function getDiskInfo(): array
    {
        // Walk hrStorageDescr untuk mencari storage yang bukan RAM
        $descs = $this->walk('1.3.6.1.2.1.25.2.3.1.2');
        foreach ($descs as $i => $desc) {
            $descStr = strtolower((string) $desc);
            // Cari entry yang berisi kata disk, flash, storage, virtual, atau data
            if (str_contains($descStr, 'flash')
                || str_contains($descStr, 'disk')
                || str_contains($descStr, 'virtual disk')
                || str_contains($descStr, 'sata')
            ) {
                $snmpIndex = $i + 1;
                $alloc = (int) $this->get("1.3.6.1.2.1.25.2.3.1.4.{$snmpIndex}");
                $total = (int) $this->get("1.3.6.1.2.1.25.2.3.1.5.{$snmpIndex}");
                $used  = (int) $this->get("1.3.6.1.2.1.25.2.3.1.6.{$snmpIndex}");
                if ($alloc > 0 && $total > 0) {
                    return [
                        round($alloc * $used  / 1024 / 1024 / 1024, 2),
                        round($alloc * $total / 1024 / 1024 / 1024, 2),
                    ];
                }
            }
        }

        return [0.0, 1.0]; // fallback
    }
}
