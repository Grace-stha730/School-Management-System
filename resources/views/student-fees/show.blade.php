@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layouts.left-menu')
        <div class="main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-person-badge text-primary"></i> 
                        @if(auth()->user()->role == "student")
                            My Fees
                        @else
                            {{ $student->first_name }} {{ $student->last_name }}'s Fees
                        @endif
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            @if(auth()->user()->role != "student")
                            <li class="breadcrumb-item"><a href="{{ route('student.fees.index') }}">Student Fees</a></li>
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">Fee Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col">
                    <div class="p-3 border bg-light shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="bi bi-currency-dollar text-info"></i> Fee Summary</h5>
                            <div>
                                <span class="badge bg-info">Session: {{ $current_school_session_id }}</span>
                            </div>
                        </div>

                        @if($student_fees->count() > 0)
                        @php
                            $totalAssigned = $student_fees->sum('assigned_amount');
                            $totalPaid = $student_fees->sum('paid_amount');
                            $totalDiscount = $student_fees->sum('discount_amount');
                            $totalRemaining = $totalAssigned - $totalPaid - $totalDiscount;
                        @endphp
                        
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">Total Assigned</h6>
                                        <h4 class="text-primary">₹{{ number_format($totalAssigned, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="card-title text-success">Total Paid</h6>
                                        <h4 class="text-success">₹{{ number_format($totalPaid, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="card-title text-info">Total Discount</h6>
                                        <h4 class="text-info">₹{{ number_format($totalDiscount, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning">Remaining</h6>
                                        <h4 class="text-warning">₹{{ number_format($totalRemaining, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Fee Head</th>
                                        <th scope="col">Assigned Amount</th>
                                        <th scope="col">Paid Amount</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">Remaining</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Due Date</th>
                                        @if(auth()->user()->role == "admin")
                                        <th scope="col">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student_fees as $index => $fee)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>
                                            <strong>{{ $fee->feeStructure->feeHead->name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $fee->feeStructure->schoolClass->class_name ?? 'All Classes' }}
                                                @if($fee->feeStructure->section)
                                                - {{ $fee->feeStructure->section->section_name }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>₹{{ number_format($fee->assigned_amount, 2) }}</td>
                                        <td>₹{{ number_format($fee->paid_amount, 2) }}</td>
                                        <td>₹{{ number_format($fee->discount_amount, 2) }}</td>
                                        <td>₹{{ number_format($fee->remaining_amount, 2) }}</td>
                                        <td>
                                            <span class="badge 
                                                @switch($fee->payment_status)
                                                    @case('paid') bg-success @break
                                                    @case('partial') bg-warning @break
                                                    @case('overdue') bg-danger @break
                                                    @default bg-secondary
                                                @endswitch
                                            ">
                                                {{ ucfirst($fee->payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($fee->due_date)
                                                {{ $fee->due_date->format('M d, Y') }}
                                                @if($fee->is_overdue)
                                                    <br><small class="text-danger">Overdue</small>
                                                @endif
                                            @else
                                                <span class="text-muted">No due date</span>
                                            @endif
                                        </td>
                                        @if(auth()->user()->role == "admin")
                                        <td>
                                            @if($fee->payment_status != 'paid')
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal{{ $fee->id }}">
                                                <i class="bi bi-credit-card"></i> Payment
                                            </button>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-currency-dollar text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">No Fees Assigned</h5>
                            <p class="text-muted">No fees have been assigned to this student for the current session.</p>
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
