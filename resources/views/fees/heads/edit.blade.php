@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layouts.left-menu')
        <div class="main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-pencil-square text-primary"></i> Edit Fee Head
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fees.heads.index') }}">Fee Heads</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="p-4 border bg-light shadow-sm">
                        <h5 class="mb-4"><i class="bi bi-tags text-info"></i> Fee Head Information</h5>
                        
                        <form action="{{ route('fees.heads.update', $fee_head->id) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Fee Head Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $fee_head->name) }}" 
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
                                        <option value="">Choose Fee Type...</option>
                                        <option value="monthly" {{ old('fee_type', $fee_head->fee_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('fee_type', $fee_head->fee_type) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ old('fee_type', $fee_head->fee_type) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                        <option value="one_time" {{ old('fee_type', $fee_head->fee_type) == 'one_time' ? 'selected' : '' }}>One Time</option>
                                        <option value="exam" {{ old('fee_type', $fee_head->fee_type) == 'exam' ? 'selected' : '' }}>Exam Fee</option>
                                        <option value="transport" {{ old('fee_type', $fee_head->fee_type) == 'transport' ? 'selected' : '' }}>Transport</option>
                                        <option value="hostel" {{ old('fee_type', $fee_head->fee_type) == 'hostel' ? 'selected' : '' }}>Hostel</option>
                                        <option value="library" {{ old('fee_type', $fee_head->fee_type) == 'library' ? 'selected' : '' }}>Library</option>
                                        <option value="laboratory" {{ old('fee_type', $fee_head->fee_type) == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                                        <option value="other" {{ old('fee_type', $fee_head->fee_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('fee_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3" 
                                              placeholder="Describe the purpose and details of this fee">{{ old('description', $fee_head->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               value="1" 
                                               id="is_active" 
                                               name="is_active"
                                               {{ old('is_active', $fee_head->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Status
                                        </label>
                                    </div>
                                    <small class="text-muted">Uncheck to make this fee head inactive</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               value="1" 
                                               id="is_required" 
                                               name="is_required"
                                               {{ old('is_required', $fee_head->is_required) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_required">
                                            Required Fee
                                        </label>
                                    </div>
                                    <small class="text-muted">Check if this fee is mandatory for all students</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-outline-primary me-2">
                                        <i class="bi bi-check-lg"></i> Update Fee Head
                                    </button>
                                    <a href="{{ route('fees.heads.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Back to List
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="p-3 border bg-light shadow-sm">
                        <h6 class="mb-3"><i class="bi bi-info-circle text-info"></i> Current Information</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><strong>Current Name:</strong> {{ $fee_head->name }}</li>
                            <li class="mb-2"><strong>Fee Type:</strong> 
                                <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $fee_head->fee_type)) }}</span>
                            </li>
                            <li class="mb-2"><strong>Status:</strong> 
                                <span class="badge {{ $fee_head->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $fee_head->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </li>
                            <li class="mb-2"><strong>Required:</strong> 
                                <span class="badge {{ $fee_head->is_required ? 'bg-warning' : 'bg-info' }}">
                                    {{ $fee_head->is_required ? 'Yes' : 'No' }}
                                </span>
                            </li>
                            <li class="mb-2"><strong>Created:</strong> {{ $fee_head->created_at->format('M d, Y') }}</li>
                        </ul>
                    </div>
                    
                    <div class="p-3 border bg-light shadow-sm mt-3">
                        <h6 class="mb-3"><i class="bi bi-graph-up text-success"></i> Usage Statistics</h6>
                        <small class="text-muted">Fee structures using this head: <strong>{{ $fee_head->feeStructures->count() }}</strong></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
