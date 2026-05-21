<?php
snmp_set_quick_print(true);

echo "Testing SNMP v1...\n";
$r1 = @snmpget('192.168.56.2', 'public', '.1.3.6.1.2.1.1.1.0', 5000000, 3);
echo "SNMPv1 sysDescr: " . ($r1 === false ? "FAILED" : $r1) . "\n";

echo "Testing SNMP v2c...\n";
$r2 = @snmp2_get('192.168.56.2', 'public', '.1.3.6.1.2.1.1.1.0', 5000000, 3);
echo "SNMPv2c sysDescr: " . ($r2 === false ? "FAILED" : $r2) . "\n";

echo "Testing SNMP v1 sysUpTime...\n";
$r3 = @snmpget('192.168.56.2', 'public', '.1.3.6.1.2.1.1.3.0', 5000000, 3);
echo "SNMPv1 sysUpTime: " . ($r3 === false ? "FAILED" : $r3) . "\n";

echo "Done.\n";
