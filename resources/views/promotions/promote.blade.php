@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layouts.left-menu')
        <div class="main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-person-plus-fill text-primary"></i> Promote Students
                    </h1>
                    @include('session-messages')
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('promotions.list') }}">Student Promotions</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Promote Students</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col">
                    <div class="p-3 border bg-light shadow-sm">
                        <h5><i class="bi bi-arrow-up-circle text-info"></i> Promoting Students</h5>
                        <p class="text-muted">
                            From: <strong>{{ $schoolClass->class_name }}</strong>
                            @if($section) - <strong>{{ $section->section_name }}</strong> @endif
                        </p>
                        
                        @if($students->count() > 0)
                        <form action="{{ route('promotions.store') }}" method="POST">
                            @csrf
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="promote_to_class" class="form-label">Promote to Class <span class="text-danger">*</span></label>
                                    <select class="form-select" id="promote_to_class" name="promote_to_class" required>
                                        <option value="">Choose New Class...</option>
                                        @foreach($school_classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="promote_to_section" class="form-label">Promote to Section</label>
                                    <select class="form-select" id="promote_to_section" name="promote_to_section">
                                        <option value="">Choose Section...</option>
                                        <!-- Sections will be loaded via JavaScript -->
                                    </select>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="select_all" class="form-check-input">
                                            </th>
                                            <th>Student Name</th>
                                            <th>Student ID</th>
                                            <th>Current Class</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $promotion)
                                        @if($promotion->student)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="student_ids[]" value="{{ $promotion->student->id }}" class="form-check-input student-checkbox">
                                            </td>
                                            <td>{{ $promotion->student->first_name }} {{ $promotion->student->last_name }}</td>
                                            <td>{{ $promotion->student->id }}</td>
                                            <td>{{ $schoolClass->class_name }} @if($section) - {{ $section->section_name }} @endif</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('promotions.list') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to Promotions
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-arrow-up-circle"></i> Promote Selected Students
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No Students Found</h5>
                            <p class="text-muted">There are no students assigned to this class/section in the previous session.</p>
                            <a href="{{ route('promotions.list') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> Back to Promotions
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Select all functionality
document.getElementById('select_all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Load sections when class is selected
document.getElementById('promote_to_class').addEventListener('change', function() {
    const classId = this.value;
    const sectionSelect = document.getElementById('promote_to_section');
    
    // Clear existing options
    sectionSelect.innerHTML = '<option value="">Choose Section...</option>';
    
    if (classId) {
        // You can implement AJAX call here to load sections for the selected class
        // For now, we'll add a basic option
        sectionSelect.innerHTML += '<option value="1">Section A</option>';
        sectionSelect.innerHTML += '<option value="2">Section B</option>';
        sectionSelect.innerHTML += '<option value="3">Section C</option>';
    }
});
</script>
@endsection
