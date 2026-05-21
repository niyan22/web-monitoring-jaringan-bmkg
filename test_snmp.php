<?php
/**
 * Script test koneksi SNMP ke Mikrotik
 * Jalankan: php test_snmp.php
 */

$host      = '192.168.44.1';
$community = 'public';
$timeout   = 1000000; // 1 detik
$retries   = 2;

echo "=================================================\n";
echo " TEST SNMP → Mikrotik ($host)\n";
echo "=================================================\n\n";

// Cek extension SNMP
if (!extension_loaded('snmp')) {
    echo "❌ GAGAL: PHP extension 'snmp' tidak aktif!\n";
    echo "   Aktifkan extension=snmp di php.ini lalu restart Laragon.\n";
    exit(1);
}
echo "✅ PHP SNMP extension: AKTIF\n\n";

// Fungsi helper
function snmpTest(string $label, string $host, string $community, string $oid, int $timeout, int $retries): void
{
    snmp_set_quick_print(true);
    $result = @snmpget($host, $community, $oid, $timeout, $retries);
    if ($result === false) {
        echo "  ❌ $label → GAGAL (timeout atau unreachable)\n";
    } else {
        echo "  ✅ $label → $result\n";
    }
}

echo "--- Ping SNMP ke Mikrotik ---\n";

// sysDescr — sistem operasi Mikrotik
snmpTest('sysDescr (OS Mikrotik)',   $host, $community, '1.3.6.1.2.1.1.1.0',         $timeout, $retries);

// sysUpTime — uptime perangkat
snmpTest('sysUpTime (Uptime)',       $host, $community, '1.3.6.1.2.1.1.3.0',         $timeout, $retries);

// CPU Load
snmpTest('hrProcessorLoad (CPU %)',  $host, $community, '1.3.6.1.2.1.25.3.3.1.2.1', $timeout, $retries);

// Memory alloc unit
snmpTest('Memory alloc unit',       $host, $community, '1.3.6.1.2.1.25.2.3.1.4.65536', $timeout, $retries);

// Memory Total
snmpTest('Memory Total (units)',    $host, $community, '1.3.6.1.2.1.25.2.3.1.5.65536', $timeout, $retries);

// Memory Used
snmpTest('Memory Used (units)',     $host, $community, '1.3.6.1.2.1.25.2.3.1.6.65536', $timeout, $retries);

// Interface ether1 — in octets
snmpTest('ether1 ifInOctets',       $host, $community, '1.3.6.1.2.1.2.2.1.10.1',    $timeout, $retries);

// Interface ether1 — out octets
snmpTest('ether1 ifOutOctets',      $host, $community, '1.3.6.1.2.1.2.2.1.16.1',    $timeout, $retries);

// TCP Connections
snmpTest('tcpCurrEstab (TCP Conn)', $host, $community, '1.3.6.1.2.1.6.9.0',         $timeout, $retries);

echo "\n=================================================\n";
echo "Jika semua ✅ = SNMP berjalan normal!\n";
echo "Jika ada ❌  = periksa:\n";
echo "  1. VM Mikrotik sudah nyala?\n";
echo "  2. IP 192.168.44.1 bisa di-ping dari Windows?\n";
echo "  3. SNMP community 'public' sudah aktif?\n";
echo "=================================================\n";
