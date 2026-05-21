@extends('layouts.app')

@section('title', 'Create Setting')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">Create New Setting</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="setting_key" class="form-label fw-bold">Setting Key</label>
                            <input type="text" class="form-control @error('setting_key') is-invalid @enderror" id="setting_key" name="setting_key" value="{{ old('setting_key') }}" placeholder="e.g., app_name" required>
                            @error('setting_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="setting_value" class="form-label fw-bold">Setting Value</label>
                            <textarea class="form-control @error('setting_value') is-invalid @enderror" id="setting_value" name="setting_value" rows="4" placeholder="Enter the value" required>{{ old('setting_value') }}</textarea>
                            @error('setting_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label fw-bold">Category</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">-- Select Category --</option>
                                <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General</option>
                                <option value="network" {{ old('category') === 'network' ? 'selected' : '' }}>Network</option>
                                <option value="monitoring" {{ old('category') === 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                                <option value="notifications" {{ old('category') === 'notifications' ? 'selected' : '' }}>Notifications</option>
                                <option value="security" {{ old('category') === 'security' ? 'selected' : '' }}>Security</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2" placeholder="Enter description (optional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create Setting
                            </button>
                            <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
