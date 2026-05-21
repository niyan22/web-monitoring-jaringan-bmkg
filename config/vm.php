<?php

return [
    'ssh_host' => env('VM_SSH_HOST', '127.0.0.1'),
    'ssh_port' => (int) env('VM_SSH_PORT', 2222),
    'ssh_username' => env('VM_SSH_USERNAME', 'user'),
    'ssh_password' => env('VM_SSH_PASSWORD', 'password'),
    'ssh_interface' => env('VM_SSH_INTERFACE', 'eth0'),
];
