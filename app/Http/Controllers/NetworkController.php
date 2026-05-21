<?php

namespace App\Http\Controllers;

use App\Models\NetworkTraffic;
use App\Services\SnmpService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NetworkController extends Controller
{
    /**
     * Fetch network data dari Mikrotik via SNMP (hanya baca, tidak simpan)
     */
    public function fetchFromVm(): JsonResponse
    {
        try {
            $snmpService = new SnmpService();
            $data = $snmpService->fetchNetworkData();

            return response()->json([
                'success' => true,
                'data'    => $data,
                'message' => 'Data berhasil diambil dari Mikrotik via SNMP!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari Mikrotik: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Fetch dari Mikrotik via SNMP + simpan otomatis ke database (untuk realtime auto-update)
     */
    public function autoFetchAndSave(): JsonResponse
    {
        try {
            $snmpService = new SnmpService();
            $data = $snmpService->fetchNetworkData();

            $data['recorded_at'] = now();
            $traffic = NetworkTraffic::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diambil dari VM dan disimpan!',
                'data' => [
                    'id'                    => $traffic->id,
                    'interface_name'        => $traffic->interface_name,
                    'download_speed'        => $traffic->download_speed,
                    'upload_speed'          => $traffic->upload_speed,
                    'packets_sent'          => $traffic->packets_sent,
                    'packets_received'      => $traffic->packets_received,
                    'bytes_sent'            => $traffic->bytes_sent,
                    'bytes_received'        => $traffic->bytes_received,
                    'active_connections'    => $traffic->active_connections,
                    'established_connections' => $traffic->established_connections,
                    'recorded_at'           => $traffic->recorded_at->format('d M Y H:i:s'),
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
     * Return data terbaru sebagai JSON (untuk live polling di halaman index)
     */
    public function latestJson(): JsonResponse
    {
        $traffic = NetworkTraffic::latest('recorded_at')->first();

        if (!$traffic) {
            return response()->json(['success' => false, 'message' => 'Belum ada data.']);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'interface_name'          => $traffic->interface_name,
                'download_speed'          => $traffic->download_speed,
                'upload_speed'            => $traffic->upload_speed,
                'packets_sent'            => $traffic->packets_sent,
                'packets_received'        => $traffic->packets_received,
                'bytes_sent'              => $traffic->bytes_sent,
                'bytes_received'          => $traffic->bytes_received,
                'active_connections'      => $traffic->active_connections,
                'established_connections' => $traffic->established_connections,
                'recorded_at'             => $traffic->recorded_at->format('d M Y H:i:s'),
            ],
        ]);
    }

    public function index()
    {
        $traffics = NetworkTraffic::latest('recorded_at')->paginate(10);
        $latestTraffic = NetworkTraffic::latest('recorded_at')->first();

        // Chart data: ambil data 24 jam terakhir
        $chartRecords = NetworkTraffic::where('recorded_at', '>=', now()->subHours(24))
            ->orderBy('recorded_at')
            ->get();

        $chartLabels = $chartRecords->map(fn($r) => $r->recorded_at->format('H:i'))->values()->toArray();
        $chartDownload = $chartRecords->pluck('download_speed')->toArray();
        $chartUpload = $chartRecords->pluck('upload_speed')->toArray();

        return view('network.index', compact(
            'traffics', 'latestTraffic',
            'chartLabels', 'chartDownload', 'chartUpload'
        ));
    }

    public function create()
    {
        return view('network.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'interface_name' => 'required|string',
            'download_speed' => 'required|numeric|min:0',
            'upload_speed' => 'required|numeric|min:0',
            'packets_sent' => 'required|integer|min:0',
            'packets_received' => 'required|integer|min:0',
            'bytes_sent' => 'required|integer|min:0',
            'bytes_received' => 'required|integer|min:0',
            'active_connections' => 'required|integer|min:0',
            'established_connections' => 'required|integer|min:0',
        ]);

        $validated['recorded_at'] = now();

        NetworkTraffic::create($validated);

        return redirect()->route('network')->with('success', 'Data traffic jaringan berhasil ditambahkan!');
    }

    public function show(NetworkTraffic $networkTraffic)
    {
        return view('network.show', compact('networkTraffic'));
    }

    public function edit(NetworkTraffic $networkTraffic)
    {
        return view('network.edit', compact('networkTraffic'));
    }

    public function update(Request $request, NetworkTraffic $networkTraffic)
    {
        $validated = $request->validate([
            'interface_name' => 'required|string',
            'download_speed' => 'required|numeric|min:0',
            'upload_speed' => 'required|numeric|min:0',
            'packets_sent' => 'required|integer|min:0',
            'packets_received' => 'required|integer|min:0',
            'bytes_sent' => 'required|integer|min:0',
            'bytes_received' => 'required|integer|min:0',
            'active_connections' => 'required|integer|min:0',
            'established_connections' => 'required|integer|min:0',
        ]);

        $networkTraffic->update($validated);

        return redirect()->route('network')->with('success', 'Data traffic jaringan berhasil diperbarui!');
    }

    public function destroy(NetworkTraffic $networkTraffic)
    {
        $networkTraffic->delete();

        return redirect()->route('network')->with('success', 'Data traffic jaringan berhasil dihapus!');
    }
}
