@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layouts.left-menu')
        <div class="main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-diagram-3 text-primary"></i> Fee Structures
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Fee Structures</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col">
                    <div class="p-3 border bg-light shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="bi bi-list-ul text-info"></i> Fee Structures List</h5>
                            @if (!session()->has('browse_session_id'))
                            <a href="{{ route('fees.structures.create') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-circle"></i> Add Fee Structure
                            </a>
                            @endif
                        </div>

                        @if($fee_structures->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Fee Head</th>
                                        <th scope="col">Class</th>
                                        <th scope="col">Section</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fee_structures as $index => $structure)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>
                                            <strong>{{ $structure->feeHead->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $structure->feeHead->fee_type)) }}</small>
                                        </td>
                                        <td>{{ $structure->schoolClass->class_name ?? 'All Classes' }}</td>
                                        <td>{{ $structure->section->section_name ?? 'All Sections' }}</td>
                                        <td>
                                            <strong>₹{{ number_format($structure->amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($structure->due_date)
                                                {{ $structure->due_date->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">No due date</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $structure->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $structure->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('fees.structures.edit', $structure->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-diagram-3 text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">No Fee Structures Found</h5>
                            <p class="text-muted">Create fee structures to assign specific amounts to classes and sections.</p>
                            @if (!session()->has('browse_session_id'))
                            <a href="{{ route('fees.structures.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Fee Structure
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>

@if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif

@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif
@endsection
