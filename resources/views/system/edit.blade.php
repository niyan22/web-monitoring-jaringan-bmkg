@extends('layouts.app')

@section('title', 'Edit Data System')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Edit Data System</h2>
        <p class="text-muted">Ubah data metrik sistem</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('system.update', $systemMetric) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cpu_load" class="form-label">CPU Load (%)</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('cpu_load') is-invalid @enderror" 
                                    id="cpu_load" 
                                    name="cpu_load" 
                                    placeholder="0-100"
                                    step="0.01"
                                    value="{{ old('cpu_load', $systemMetric->cpu_load) }}"
                                    required>
                                @error('cpu_load')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="processor_name" class="form-label">Nama Processor</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('processor_name') is-invalid @enderror" 
                                    id="processor_name" 
                                    name="processor_name"
                                    placeholder="Intel Core i7-9700K"
                                    value="{{ old('processor_name', $systemMetric->processor_name) }}"
                                    required>
                                @error('processor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="processor_cores" class="form-label">Jumlah Cores</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('processor_cores') is-invalid @enderror" 
                                    id="processor_cores" 
                                    name="processor_cores"
                                    placeholder="8"
                                    value="{{ old('processor_cores', $systemMetric->processor_cores) }}"
                                    required>
                                @error('processor_cores')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="processor_frequency" class="form-label">Frekuensi (GHz)</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('processor_frequency') is-invalid @enderror" 
                                    id="processor_frequency" 
                                    name="processor_frequency"
                                    placeholder="3.60"
                                    step="0.01"
                                    value="{{ old('processor_frequency', $systemMetric->processor_frequency) }}"
                                    required>
                                @error('processor_frequency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="memory_used" class="form-label">Memory Used (GB)</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('memory_used') is-invalid @enderror" 
                                    id="memory_used" 
                                    name="memory_used"
                                    placeholder="7.68"
                                    step="0.01"
                                    value="{{ old('memory_used', $systemMetric->memory_used) }}"
                                    required>
                                @error('memory_used')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="memory_total" class="form-label">Memory Total (GB)</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('memory_total') is-invalid @enderror" 
                                    id="memory_total" 
                                    name="memory_total"
                                    placeholder="16"
                                    step="0.01"
                                    value="{{ old('memory_total', $systemMetric->memory_total) }}"
                                    required>
                                @error('memory_total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="disk_used" class="form-label">Disk Used (GB)</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('disk_used') is-invalid @enderror" 
                                    id="disk_used" 
                                    name="disk_used"
                                    placeholder="150"
                                    step="0.01"
                                    value="{{ old('disk_used', $systemMetric->disk_used) }}"
                                    required>
                                @error('disk_used')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="disk_total" class="form-label">Disk Total (GB)</label>
                                <input 
                                    type="number" 
                                    class="form-control @error('disk_total') is-invalid @enderror" 
                                    id="disk_total" 
                                    name="disk_total"
                                    placeholder="500"
                                    step="0.01"
                                    value="{{ old('disk_total', $systemMetric->disk_total) }}"
                                    required>
                                @error('disk_total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-pencil me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('system') }}" class="btn btn-secondary">
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
                        <p><strong>Dibuat:</strong> {{ $systemMetric->created_at->format('d M Y H:i') }}</p>
                        <p><strong>Diperbarui:</strong> {{ $systemMetric->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
