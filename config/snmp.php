<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi SNMP untuk Mikrotik
    |--------------------------------------------------------------------------
    | Host     : IP address Mikrotik yang dapat diakses dari server
    | Community: SNMP community string (default: public)
    | Port     : Port SNMP (default: 161)
    | Timeout  : Timeout dalam mikrodetik (default: 1.000.000 = 1 detik)
    | Retries  : Jumlah percobaan ulang jika gagal
    */

    'host'            => env('SNMP_HOST', '192.168.44.1'),
    'community'       => env('SNMP_COMMUNITY', 'public'),
    'port'            => (int) env('SNMP_PORT', 161),
    'timeout'         => (int) env('SNMP_TIMEOUT', 1000000),
    'retries'         => (int) env('SNMP_RETRIES', 3),
    'version'         => env('SNMP_VERSION', '1'), // '1', '2c', or '3'

    /*
    | Konfigurasi Interface Jaringan Mikrotik yang Dimonitor
    | interface_name  : Nama interface di Mikrotik (ether1, ether2, dst.)
    | interface_index : SNMP ifIndex dari interface tersebut
    |                   (cek dengan: /interface print dari Mikrotik — urutan = index)
    */
    'interface_name'  => env('SNMP_INTERFACE_NAME', 'ether1'),
    'interface_index' => (int) env('SNMP_INTERFACE_INDEX', 1),
];
