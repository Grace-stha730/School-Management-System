@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layouts.left-menu')
        <div class="main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-pencil-square text-primary"></i> Edit Fee Structure
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fees.structures.index') }}">Fee Structures</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="p-4 border bg-light shadow-sm">
                        <h5 class="mb-4"><i class="bi bi-diagram-3 text-info"></i> Fee Structure Information</h5>
                        
                        <form action="{{ route('fees.structures.update', $fee_structure->id) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fee_head_id" class="form-label">Fee Head <span class="text-danger">*</span></label>
                                    <select class="form-select @error('fee_head_id') is-invalid @enderror" 
                                            id="fee_head_id"
                                            name="fee_head_id"
                                            required>
                                        <option value="">Choose Fee Head...</option>
                                        @foreach($fee_heads as $head)
                                            <option value="{{ $head->id }}" 
                                                {{ (old('fee_head_id', $fee_structure->fee_head_id) == $head->id) ? 'selected' : '' }}>
                                                {{ $head->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('fee_head_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" 
                                               class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" 
                                               name="amount" 
                                               value="{{ old('amount', $fee_structure->amount) }}" 
                                               placeholder="0.00"
                                               step="0.01"
                                               min="0"
                                               required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" 
                                            id="class_id"
                                            name="class_id"
                                            required>
                                        <option value="">Choose Class...</option>
                                        @foreach($school_classes as $class)
                                            <option value="{{ $class->id }}" 
                                                {{ (old('class_id', $fee_structure->class_id) == $class->id) ? 'selected' : '' }}>
                                                {{ $class->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="section_id" class="form-label">Section</label>
                                    <select class="form-select @error('section_id') is-invalid @enderror" 
                                            id="section_id"
                                            name="section_id">
                                        <option value="">All Sections</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" 
                                                {{ (old('section_id', $fee_structure->section_id) == $section->id) ? 'selected' : '' }}>
                                                {{ $section->section_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="semester_id" class="form-label">Semester</label>
                                    <select class="form-select @error('semester_id') is-invalid @enderror" 
                                            id="semester_id"
                                            name="semester_id">
                                        <option value="">All Semesters</option>
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester->id }}" 
                                                {{ (old('semester_id', $fee_structure->semester_id) == $semester->id) ? 'selected' : '' }}>
                                                {{ $semester->semester_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('semester_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" 
                                           class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" 
                                           name="due_date" 
                                           value="{{ old('due_date', $fee_structure->due_date ? $fee_structure->due_date->format('Y-m-d') : '') }}">
                                    @error('due_date')
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
                                              placeholder="Optional description or notes about this fee structure">{{ old('description', $fee_structure->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               value="1" 
                                               id="is_active" 
                                               name="is_active"
                                               {{ old('is_active', $fee_structure->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Status
                                        </label>
                                    </div>
                                    <small class="text-muted">Uncheck to make this fee structure inactive</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-outline-primary me-2">
                                        <i class="bi bi-check-lg"></i> Update Fee Structure
                                    </button>
                                    <a href="{{ route('fees.structures.index') }}" class="btn btn-outline-secondary">
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
                            <li class="mb-2"><strong>Fee Head:</strong> {{ $fee_structure->feeHead->name }}</li>
                            <li class="mb-2"><strong>Current Amount:</strong> ${{ number_format($fee_structure->amount, 2) }}</li>
                            <li class="mb-2"><strong>Class:</strong> {{ $fee_structure->schoolClass->class_name ?? 'Not specified' }}</li>
                            <li class="mb-2"><strong>Section:</strong> {{ $fee_structure->section->section_name ?? 'All sections' }}</li>
                            <li class="mb-2"><strong>Status:</strong> 
                                <span class="badge {{ $fee_structure->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $fee_structure->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
