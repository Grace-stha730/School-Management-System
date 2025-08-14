@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layouts.left-menu')
        <div class="main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-person-badge text-primary"></i> Student Fees Management
                    </h1>
                    
                    @if (session()->has('browse_session_id'))
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Browsing Session:</strong> You are currently viewing data for a previous academic session.
                            Fee assignments can only be made in the current session.
                        </div>
                    @endif

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Student Fees</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col">
                    <div class="p-3 border bg-light shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="bi bi-list-ul text-info"></i> Student Fees Overview</h5>
                            @if (!session()->has('browse_session_id'))
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#assignFeeModal">
                                    <i class="bi bi-plus-circle"></i> Assign Fees
                                </button>
                            </div>
                            @endif
                        </div>

                        @if($student_fees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Student Name</th>
                                        <th scope="col">Class & Section</th>
                                        <th scope="col">Fee Head</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Paid Amount</th>
                                        <th scope="col">Outstanding</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student_fees as $index => $fee)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <div class="fw-bold">{{ $fee->student->first_name }} {{ $fee->student->last_name }}</div>
                                                    <small class="text-muted">ID: {{ $fee->student->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($fee->student->studentAcademicInfo)
                                                <span class="badge bg-info">
                                                    {{ $fee->student->studentAcademicInfo->schoolClass->class_name }}
                                                    @if($fee->student->studentAcademicInfo->section)
                                                        - {{ $fee->student->studentAcademicInfo->section->section_name }}
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $fee->feeStructure->feeHead->name }}</div>
                                            <small class="text-muted">{{ $fee->feeStructure->feeHead->fee_type }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary">₹{{ number_format($fee->amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">₹{{ number_format($fee->paid_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $fee->amount - $fee->paid_amount > 0 ? 'text-danger' : 'text-success' }}">
                                                ₹{{ number_format($fee->amount - $fee->paid_amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($fee->payment_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($fee->payment_status == 'partial')
                                                <span class="badge bg-warning">Partial</span>
                                            @elseif($fee->payment_status == 'overdue')
                                                <span class="badge bg-danger">Overdue</span>
                                            @else
                                                <span class="badge bg-secondary">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fee->due_date)
                                                <span class="text-muted">{{ $fee->due_date->format('M d, Y') }}</span>
                                            @else
                                                <span class="text-muted">No due date</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('student.fees.show', $fee->student_id) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($fee->payment_status != 'paid' && !session()->has('browse_session_id'))
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#paymentModal{{ $fee->id }}"
                                                        title="Record Payment">
                                                    <i class="bi bi-credit-card"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">No student fees found</h5>
                            <p class="text-muted">Start by assigning fee structures to students.</p>
                            @if (!session()->has('browse_session_id'))
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignFeeModal">
                                <i class="bi bi-plus-circle"></i> Assign Fees to Students
                            </button>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (!session()->has('browse_session_id'))
<!-- Assign Fee Modal -->
<div class="modal fade" id="assignFeeModal" tabindex="-1" aria-labelledby="assignFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignFeeModalLabel">Assign Fees to Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('student.fees.assign') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fee_structure_id" class="form-label">Fee Structure <span class="text-danger">*</span></label>
                            <select class="form-select" id="fee_structure_id" name="fee_structure_id" required>
                                <option value="">Choose Fee Structure...</option>
                                @foreach($fee_structures as $structure)
                                    <option value="{{ $structure->id }}">
                                        {{ $structure->feeHead->name }} - ₹{{ number_format($structure->amount, 2) }}
                                        @if($structure->class_id)
                                            ({{ $structure->schoolClass->class_name }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="assignment_type" class="form-label">Assignment Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="assignment_type" name="assignment_type" required>
                                <option value="">Choose Type...</option>
                                <option value="class">Entire Class</option>
                                <option value="individual">Individual Student</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="classSelection" class="d-none">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="class_id" class="form-label">Class</label>
                                <select class="form-select" id="class_id" name="class_id">
                                    <option value="">Choose Class...</option>
                                    @foreach($school_classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="section_id" class="form-label">Section</label>
                                <select class="form-select" id="section_id" name="section_id">
                                    <option value="">All Sections</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="studentSelection" class="d-none">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student</label>
                            <select class="form-select" id="student_id" name="student_id">
                                <option value="">Choose Student...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Fees</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
document.getElementById('assignment_type').addEventListener('change', function() {
    const type = this.value;
    const classSelection = document.getElementById('classSelection');
    const studentSelection = document.getElementById('studentSelection');
    
    if (type === 'class') {
        classSelection.classList.remove('d-none');
        studentSelection.classList.add('d-none');
    } else if (type === 'individual') {
        classSelection.classList.add('d-none');
        studentSelection.classList.remove('d-none');
    } else {
        classSelection.classList.add('d-none');
        studentSelection.classList.add('d-none');
    }
});

// Get sections when class is selected
document.getElementById('class_id').addEventListener('change', function() {
    const classId = this.value;
    const sectionSelect = document.getElementById('section_id');
    
    // Clear existing options except the first one
    sectionSelect.innerHTML = '<option value="">All Sections</option>';
    
    if (classId) {
        fetch(`/section/${classId}`)
            .then(response => response.json())
            .then(data => {
                if (data.sections) {
                    data.sections.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = section.section_name;
                        sectionSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching sections:', error);
            });
    }
});
</script>
@endsection
