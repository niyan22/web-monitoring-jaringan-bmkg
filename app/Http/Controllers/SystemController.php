<?php

namespace App\Http\Controllers;

use App\Models\SystemMetric;
use App\Services\SnmpService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SystemController extends Controller
{
    public function index()
    {
        $metrics      = SystemMetric::latest('recorded_at')->paginate(10);
        $latestMetric = SystemMetric::latest('recorded_at')->first();

        return view('system.index', compact('metrics', 'latestMetric'));
    }

    public function create()
    {
        return view('system.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cpu_load'            => 'required|numeric|min:0|max:100',
            'memory_used'         => 'required|numeric|min:0',
            'memory_total'        => 'required|numeric|min:0',
            'disk_used'           => 'required|numeric|min:0',
            'disk_total'          => 'required|numeric|min:0',
            'processor_name'      => 'required|string',
            'processor_cores'     => 'required|integer|min:1',
            'processor_frequency' => 'required|numeric|min:0',
        ]);

        $validated['recorded_at'] = now();

        SystemMetric::create($validated);

        return redirect()->route('system')->with('success', 'Data sistem berhasil ditambahkan!');
    }

    public function show(SystemMetric $systemMetric)
    {
        return view('system.show', compact('systemMetric'));
    }

    public function edit(SystemMetric $systemMetric)
    {
        return view('system.edit', compact('systemMetric'));
    }

    public function update(Request $request, SystemMetric $systemMetric)
    {
        $validated = $request->validate([
            'cpu_load'            => 'required|numeric|min:0|max:100',
            'memory_used'         => 'required|numeric|min:0',
            'memory_total'        => 'required|numeric|min:0',
            'disk_used'           => 'required|numeric|min:0',
            'disk_total'          => 'required|numeric|min:0',
            'processor_name'      => 'required|string',
            'processor_cores'     => 'required|integer|min:1',
            'processor_frequency' => 'required|numeric|min:0',
        ]);

        $systemMetric->update($validated);

        return redirect()->route('system')->with('success', 'Data sistem berhasil diperbarui!');
    }

    public function destroy(SystemMetric $systemMetric)
    {
        $systemMetric->delete();

        return redirect()->route('system')->with('success', 'Data sistem berhasil dihapus!');
    }

    /**
     * Fetch data sistem dari Mikrotik via SNMP + simpan otomatis ke DB
     */
    public function autoFetchAndSave(): JsonResponse
    {
        try {
            $snmpService = new SnmpService();
            $data = $snmpService->fetchSystemData();

            $data['recorded_at'] = now();
            $metric = SystemMetric::create($data);

            $memPct  = $metric->memory_total > 0
                ? round($metric->memory_used / $metric->memory_total * 100, 1) : 0;
            $diskPct = $metric->disk_total > 0
                ? round($metric->disk_used / $metric->disk_total * 100, 1)   : 0;

            return response()->json([
                'success' => true,
                'message' => 'Data sistem berhasil diambil dari VM dan disimpan!',
                'data'    => [
                    'cpu_load'            => $metric->cpu_load,
                    'memory_used'         => $metric->memory_used,
                    'memory_total'        => $metric->memory_total,
                    'memory_pct'          => $memPct,
                    'disk_used'           => $metric->disk_used,
                    'disk_total'          => $metric->disk_total,
                    'disk_pct'            => $diskPct,
                    'processor_name'      => $metric->processor_name,
                    'processor_cores'     => $metric->processor_cores,
                    'processor_frequency' => $metric->processor_frequency,
                    'recorded_at'         => $metric->recorded_at->format('d M Y H:i:s'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Return data metrik terbaru sebagai JSON (untuk live polling)
     */
    public function latestJson(): JsonResponse
    {
        $metric = SystemMetric::latest('recorded_at')->first();

        if (!$metric) {
            return response()->json(['success' => false, 'message' => 'Belum ada data.']);
        }

        $memPct  = $metric->memory_total > 0
            ? round($metric->memory_used / $metric->memory_total * 100, 1) : 0;
        $diskPct = $metric->disk_total > 0
            ? round($metric->disk_used / $metric->disk_total * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'data'    => [
                'cpu_load'            => $metric->cpu_load,
                'memory_used'         => $metric->memory_used,
                'memory_total'        => $metric->memory_total,
                'memory_pct'          => $memPct,
                'disk_used'           => $metric->disk_used,
                'disk_total'          => $metric->disk_total,
                'disk_pct'            => $diskPct,
                'processor_name'      => $metric->processor_name,
                'processor_cores'     => $metric->processor_cores,
                'processor_frequency' => $metric->processor_frequency,
                'recorded_at'         => $metric->recorded_at->format('d M Y H:i:s'),
            ],
        ]);
    }
}
