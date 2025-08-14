@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-3">
                <div class="col ps-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>My Subjects</h4>
                        </div>
                        <div class="card-body">
                    <!-- Optional Semester Filter -->
                    <form class="mb-4" method="GET">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="semester_id" class="form-label">Filter by Semester (Optional)</label>
                                <select name="semester_id" id="semester_id" class="form-control">
                                    <option value="">Current Semester (Default)</option>
                                    @if(isset($semesters))
                                        @foreach ($semesters as $semester)
                                            <option value="{{ $semester->id }}" {{ (isset($selected_semester_id) && $selected_semester_id == $semester->id) ? 'selected' : '' }}>
                                                {{ $semester->semester_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Filter Subjects</button>
                                <a href="{{ route('course.teacher.list') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Subjects Display -->
                    @if(isset($courses) && count($courses) > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Showing {{ isset($course_count) ? $course_count : count($courses) }} assigned subject(s)
                            @if(isset($selected_semester_id) && $selected_semester_id)
                                for the selected semester
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Department</th>
                                        <th>Batch</th>
                                        <th>Semester</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courses as $course)
                                        <tr>
                                            <td>{{ $course->course->course_name ?? 'N/A' }}</td>
                                            <td>{{ $course->schoolClass->class_name ?? 'N/A' }}</td>
                                            <td>{{ $course->section->section_name ?? 'N/A' }}</td>
                                            <td>{{ $course->semester->semester_name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @can('create notes')
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('notes.create', [
                                                                    'class_id' => $course->class_id,
                                                                    'section_id' => $course->section_id,
                                                                    'course_id' => $course->course_id,
                                                                    'semester_id' => $course->semester_id
                                                                ]) }}">
                                                                    📝 Add Notes
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('view notes')
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('notes.list', [
                                                                    'class_id' => $course->class_id,
                                                                    'section_id' => $course->section_id,
                                                                    'course_id' => $course->course_id,
                                                                    'semester_id' => $course->semester_id
                                                                ]) }}">
                                                                    📖 View Notes
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('course.syllabus.index', [
                                                                'course_id' => $course->course_id
                                                            ]) }}">
                                                                📄 View Syllabus
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('course.mark.create', [
                                                                'class_id' => $course->class_id,
                                                                'section_id' => $course->section_id,
                                                                'course_id' => $course->course_id,
                                                                'semester_id' => $course->semester_id
                                                            ]) }}">
                                                                ✏️ Give Marks
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('course.mark.list.show', [
                                                                'class_id' => $course->class_id,
                                                                'section_id' => $course->section_id,
                                                                'course_id' => $course->course_id,
                                                                'semester_id' => $course->semester_id
                                                            ]) }}">
                                                                ☀️ View Final Results
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            No courses assigned to you
                            @if(isset($selected_semester_id) && $selected_semester_id)
                                for the selected semester. Try selecting a different semester or contact administration.
                            @else
                                . Please contact administration if you believe this is an error.
                            @endif
                        </div>
                    @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
