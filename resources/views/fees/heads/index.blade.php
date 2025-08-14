@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layouts.left-menu')
        <div class="main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-tags text-primary"></i> Fee Heads Management
                    </h1>
                    
                    @if (session()->has('browse_session_id'))
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Browsing Session:</strong> You are currently viewing data for a previous academic session.
                            Fee heads can only be created in the current session.
                        </div>
                    @endif

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Fee Heads</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col">
                    <div class="p-3 border bg-light shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="bi bi-list-ul text-info"></i> Fee Heads List</h5>
                            @if (!session()->has('browse_session_id'))
                            <a href="{{ route('fees.heads.create') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-circle"></i> Add New Fee Head
                            </a>
                            @endif
                        </div>

                        @if($fee_heads->count() > 0)
                        <div class="table-responsive col-10">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Fee Type</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created</th>
                                        @if (!session()->has('browse_session_id'))
                                        <th scope="col">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fee_heads as $index => $feeHead)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>
                                            <strong>{{ $feeHead->name }}</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $feeHead->description ?? 'No description' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @switch($feeHead->fee_type)
                                                    @case('one_time') bg-primary @break
                                                    @case('monthly') bg-success @break
                                                    @case('semester') bg-info @break
                                                    @case('annual') bg-warning @break
                                                    @default bg-secondary
                                                @endswitch
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $feeHead->fee_type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $feeHead->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $feeHead->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $feeHead->created_at->format('M d, Y') }}</small>
                                        </td>
                                        @if (!session()->has('browse_session_id'))
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Actions">
                                                <a href="{{ route('fees.heads.edit', $feeHead->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('fees.heads.destroy', $feeHead->id) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this fee head?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-tags text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">No Fee Heads Found</h5>
                            <p class="text-muted">Start by creating your first fee head to organize different types of fees.</p>
                            @if (!session()->has('browse_session_id'))
                            <a href="{{ route('fees.heads.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Fee Head
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
