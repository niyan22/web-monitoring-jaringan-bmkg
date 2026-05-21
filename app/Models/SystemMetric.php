<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemMetric extends Model
{
    use HasFactory;

    protected $table = 'system_metrics';

    protected $fillable = [
        'cpu_load',
        'memory_used',
        'memory_total',
        'disk_used',
        'disk_total',
        'processor_name',
        'processor_cores',
        'processor_frequency',
        'recorded_at',
    ];

    protected $casts = [
        'cpu_load'            => 'float',
        'memory_used'         => 'float',
        'memory_total'        => 'float',
        'disk_used'           => 'float',
        'disk_total'          => 'float',
        'processor_cores'     => 'integer',
        'processor_frequency' => 'float',
        'recorded_at'         => 'datetime',
    ];

    public function getMemoryPercentageAttribute()
    {
        return round(($this->memory_used / $this->memory_total) * 100, 2);
    }

    public function getDiskPercentageAttribute()
    {
        return round(($this->disk_used / $this->disk_total) * 100, 2);
    }
}
