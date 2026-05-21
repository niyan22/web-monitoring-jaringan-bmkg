<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkTraffic extends Model
{
    use HasFactory;

    protected $table = 'network_traffic';

    protected $fillable = [
        'interface_name',
        'download_speed',
        'upload_speed',
        'packets_sent',
        'packets_received',
        'bytes_sent',
        'bytes_received',
        'active_connections',
        'established_connections',
        'recorded_at',
    ];

    protected $casts = [
        'download_speed' => 'float',
        'upload_speed' => 'float',
        'packets_sent' => 'integer',
        'packets_received' => 'integer',
        'bytes_sent' => 'integer',
        'bytes_received' => 'integer',
        'active_connections' => 'integer',
        'established_connections' => 'integer',
        'recorded_at' => 'datetime',
    ];

    public function getTotalDataTransferredAttribute()
    {
        return $this->bytes_sent + $this->bytes_received;
    }

    public function getAverageSpeedAttribute()
    {
        return round(($this->download_speed + $this->upload_speed) / 2, 2);
    }
}
