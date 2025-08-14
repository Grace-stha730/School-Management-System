@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layouts.left-menu')
        <div class="main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-plus-circle text-primary"></i> Create Fee Head
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fees.heads.index') }}">Fee Heads</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-10">
                    <div class="p-4 border bg-light shadow-sm">
                        <h5 class="mb-4"><i class="bi bi-tags text-info"></i> Fee Head Information</h5>
                        
                        <form action="{{ route('fees.heads.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Fee Head Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="e.g., Tuition Fee, Lab Fee"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="fee_type" class="form-label">Fee Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('fee_type') is-invalid @enderror" 
                                            id="fee_type"
                                            name="fee_type"
                                            required>
                                        <option value="">Select Fee Type</option>
                                        <option value="monthly" {{ old('fee_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('fee_type') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ old('fee_type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                        <option value="one_time" {{ old('fee_type') == 'one_time' ? 'selected' : '' }}>One Time</option>
                                        <option value="exam" {{ old('fee_type') == 'exam' ? 'selected' : '' }}>Exam Fee</option>
                                        <option value="transport" {{ old('fee_type') == 'transport' ? 'selected' : '' }}>Transport</option>
                                        <option value="hostel" {{ old('fee_type') == 'hostel' ? 'selected' : '' }}>Hostel</option>
                                        <option value="library" {{ old('fee_type') == 'library' ? 'selected' : '' }}>Library</option>
                                        <option value="laboratory" {{ old('fee_type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                                        <option value="other" {{ old('fee_type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('fee_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="3"
                                          placeholder="Describe the purpose of this fee head">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" 
                                       type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Fee head can be used for creating fee structures)
                                </label>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_required"
                                       name="is_required"
                                       value="1"
                                       {{ old('is_required', false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_required">
                                    Required Fee (Mandatory for all students)
                                </label>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Create Fee Head
                                </button>
                                <a href="{{ route('fees.heads.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="p-3 border bg-info bg-opacity-10 shadow-sm">
                        <h6><i class="bi bi-info-circle text-info"></i> Fee Head Guidelines</h6>
                        <small class="text-muted">
                            <ul class="mb-0">
                                <li><strong>One Time:</strong> Fees charged once (e.g., Admission Fee, Registration Fee)</li>
                                <li><strong>Monthly:</strong> Fees charged every month (e.g., Tuition Fee)</li>
                                <li><strong>Semester:</strong> Fees charged per semester (e.g., Exam Fee)</li>
                                <li><strong>Annual:</strong> Fees charged once per year (e.g., Annual Fee)</li>
                            </ul>
                        </small>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>

@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif
@endsection
