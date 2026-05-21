@extends('layouts.app')

@section('title', 'Tambah Data Network Traffic')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Tambah Data Network Traffic</h2>
        <p class="text-muted">Masukkan data traffic jaringan terbaru</p>
    </div>

    {{-- Alert untuk hasil fetch VM --}}
    <div id="vm-alert" class="alert d-none" role="alert">
        <span id="vm-alert-message"></span>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">

                    {{-- Tombol Ambil Data dari VM --}}
                    <div class="mb-4 p-3 bg-light border rounded">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-bold mb-1">
                                    <i class="bi bi-pc-display me-2"></i>Ambil Data Otomatis
                                </h6>
                                <small class="text-muted">Ambil data langsung dari VM VirtualBox via SSH</small>
                            </div>
                            <button type="button" class="btn btn-success" id="btn-fetch-vm" onclick="fetchFromVM()">
                                <i class="bi bi-cloud-download me-2" id="fetch-icon"></i>
                                <span id="fetch-text">Ambil Data dari VM</span>
                                <span id="fetch-spinner" class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('network.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="interface_name" class="form-label">Nama Interface</label>
                            <input 
                                type="text" 
                                class="form-control @error('interface_name') is-invalid @enderror" 
                                id="interface_name" 
                                name="interface_name"
                                placeholder="eth0"
                                value="{{ old('interface_name') }}"
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
                                    value="{{ old('download_speed') }}"
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
                                    value="{{ old('upload_speed') }}"
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
                                    value="{{ old('packets_sent') }}"
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
                                    value="{{ old('packets_received') }}"
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
                                    value="{{ old('bytes_sent') }}"
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
                                    value="{{ old('bytes_received') }}"
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
                                    value="{{ old('active_connections') }}"
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
                                    value="{{ old('established_connections') }}"
                                    required>
                                @error('established_connections')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Tambah Data
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
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Panduan Input</h6>
                    <div class="small text-muted">
                        <p><strong>Interface:</strong> Nama adapter jaringan (eth0, wlan0, dll)</p>
                        <p><strong>Speed:</strong> Kecepatan dalam Mbps</p>
                        <p><strong>Packets:</strong> Jumlah paket yang dikirim/diterima</p>
                        <p><strong>Bytes:</strong> Jumlah byte yang dikirim/diterima</p>
                        <p><strong>Connections:</strong> Jumlah koneksi aktif/established</p>
                    </div>
                </div>
            </div>

            <div class="card bg-light mt-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-pc-display me-2"></i>Konfigurasi VM</h6>
                    <div class="small text-muted">
                        <p><strong>Host:</strong> {{ config('vm.ssh_host') }}:{{ config('vm.ssh_port') }}</p>
                        <p><strong>Interface:</strong> {{ config('vm.ssh_interface') }}</p>
                        <p class="mb-0"><strong>Tip:</strong> Klik tombol hijau <em>"Ambil Data dari VM"</em> untuk mengisi form otomatis dari VM VirtualBox.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fetchFromVM() {
    const btn = document.getElementById('btn-fetch-vm');
    const icon = document.getElementById('fetch-icon');
    const text = document.getElementById('fetch-text');
    const spinner = document.getElementById('fetch-spinner');
    const alertDiv = document.getElementById('vm-alert');
    const alertMsg = document.getElementById('vm-alert-message');

    // Show loading state
    btn.disabled = true;
    icon.classList.add('d-none');
    spinner.classList.remove('d-none');
    text.textContent = 'Mengambil data...';
    alertDiv.classList.add('d-none');

    fetch("{{ route('network.fetch-vm') }}", {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Fill form fields
            document.getElementById('interface_name').value = result.data.interface_name || '';
            document.getElementById('download_speed').value = result.data.download_speed || 0;
            document.getElementById('upload_speed').value = result.data.upload_speed || 0;
            document.getElementById('packets_sent').value = result.data.packets_sent || 0;
            document.getElementById('packets_received').value = result.data.packets_received || 0;
            document.getElementById('bytes_sent').value = result.data.bytes_sent || 0;
            document.getElementById('bytes_received').value = result.data.bytes_received || 0;
            document.getElementById('active_connections').value = result.data.active_connections || 0;
            document.getElementById('established_connections').value = result.data.established_connections || 0;

            // Show success alert
            alertDiv.className = 'alert alert-success';
            alertMsg.innerHTML = '<i class="bi bi-check-circle me-2"></i>' + result.message;

            // Highlight filled fields briefly
            const fields = ['interface_name', 'download_speed', 'upload_speed', 'packets_sent', 
                          'packets_received', 'bytes_sent', 'bytes_received', 
                          'active_connections', 'established_connections'];
            fields.forEach(field => {
                const el = document.getElementById(field);
                el.style.transition = 'background-color 0.3s';
                el.style.backgroundColor = '#d4edda';
                setTimeout(() => { el.style.backgroundColor = ''; }, 2000);
            });
        } else {
            alertDiv.className = 'alert alert-danger';
            alertMsg.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>' + result.message;
        }
    })
    .catch(error => {
        alertDiv.className = 'alert alert-danger';
        alertMsg.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Gagal terhubung ke server. Pastikan VM berjalan dan konfigurasi SSH benar.';
        console.error('Fetch VM error:', error);
    })
    .finally(() => {
        // Reset button
        btn.disabled = false;
        icon.classList.remove('d-none');
        spinner.classList.add('d-none');
        text.textContent = 'Ambil Data dari VM';
    });
}
</script>
@endsection

