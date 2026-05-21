@extends('layouts.app')

@section('title', 'Edit Data Network Traffic')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Edit Data Network Traffic</h2>
        <p class="text-muted">Ubah data traffic jaringan</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('network.update', $networkTraffic) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="interface_name" class="form-label">Nama Interface</label>
                            <input 
                                type="text" 
                                class="form-control @error('interface_name') is-invalid @enderror" 
                                id="interface_name" 
                                name="interface_name"
                                placeholder="eth0"
                                value="{{ old('interface_name', $networkTraffic->interface_name) }}"
                                required>
                            @error('interface_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="download_speed" class="form-label">Download Speed (Mbps)</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('download_speed') is-invalid @enderror" 
                                    id="download_speed" 
                                    name="download_speed"
                                    placeholder="1.2"
                                    step="0.01"
                                    value="{{ old('download_speed', $networkTraffic->download_speed) }}"
                                    required>
                                @error('download_speed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="upload_speed" class="form-label">Upload Speed (Mbps)</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('upload_speed') is-invalid @enderror" 
                                    id="upload_speed" 
                                    name="upload_speed"
                                    placeholder="0.8"
                                    step="0.01"
                                    value="{{ old('upload_speed', $networkTraffic->upload_speed) }}"
                                    required>
                                @error('upload_speed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="packets_sent" class="form-label">Packets Sent</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('packets_sent') is-invalid @enderror" 
                                    id="packets_sent" 
                                    name="packets_sent"
                                    placeholder="0"
                                    value="{{ old('packets_sent', $networkTraffic->packets_sent) }}"
                                    required>
                                @error('packets_sent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="packets_received" class="form-label">Packets Received</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('packets_received') is-invalid @enderror" 
                                    id="packets_received" 
                                    name="packets_received"
                                    placeholder="0"
                                    value="{{ old('packets_received', $networkTraffic->packets_received) }}"
                                    required>
                                @error('packets_received')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bytes_sent" class="form-label">Bytes Sent</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('bytes_sent') is-invalid @enderror" 
                                    id="bytes_sent" 
                                    name="bytes_sent"
                                    placeholder="0"
                                    value="{{ old('bytes_sent', $networkTraffic->bytes_sent) }}"
                                    required>
                                @error('bytes_sent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bytes_received" class="form-label">Bytes Received</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('bytes_received') is-invalid @enderror" 
                                    id="bytes_received" 
                                    name="bytes_received"
                                    placeholder="0"
                                    value="{{ old('bytes_received', $networkTraffic->bytes_received) }}"
                                    required>
                                @error('bytes_received')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="active_connections" class="form-label">Active Connections</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('active_connections') is-invalid @enderror" 
                                    id="active_connections" 
                                    name="active_connections"
                                    placeholder="127"
                                    value="{{ old('active_connections', $networkTraffic->active_connections) }}"
                                    required>
                                @error('active_connections')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="established_connections" class="form-label">Established Connections</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('established_connections') is-invalid @enderror" 
                                    id="established_connections" 
                                    name="established_connections"
                                    placeholder="89"
                                    value="{{ old('established_connections', $networkTraffic->established_connections) }}"
                                    required>
                                @error('established_connections')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-pencil me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('network') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Informasi</h6>
                    <div class="small text-muted">
                        <p><strong>Dibuat:</strong> {{ $networkTraffic->created_at->format('d M Y H:i') }}</p>
                        <p><strong>Diperbarui:</strong> {{ $networkTraffic->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
