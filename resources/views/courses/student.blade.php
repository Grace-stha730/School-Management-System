@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-journal-medical"></i> My Subjects
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">My subjects</li>
                        </ol>
                    </nav>
                    <div class="mb-4 mt-4">
                        @if(isset($error_message))
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Class Assignment Required:</strong> {{ $error_message }}
                            </div>
                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'super-admin')
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Administrator Note:</strong> You can assign this student to a class using the
                                    <a href="{{ route('promotions.create') }}" class="alert-link">Student Promotions</a> feature.
                                </div>
                            @endif
                        @else
                        <div class="p-3 mt-3 bg-white border shadow-sm">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Subject Name</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($courses)
                                        @foreach ($courses as $course)
                                        <tr>
                                            <td>{{$course->course_name}}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if($class_info)
                                                    <a href="{{route('course.mark', [
                                                        'course_id' => $course->id,
                                                        'course_name' => $course->course_name,
                                                        'semester_id' => $course->semester_id,
                                                        'class_id'  => $class_info->class_id,
                                                        'session_id' => $course->session_id,
                                                        'section_id' => $class_info->section_id,
                                                        'student_id' => Auth::user()->id
                                                        ])}}" role="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-cloud-sun"></i> View Marks</a>
                                                    @endif
                                                    <a href="{{route('syllabus.list', ['course_id'  => $course->id])}}" role="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-journal-text"></i> View Syllabus</a>
                                                    @can('view notes')
                                                    <a href="{{route('notes.list', ['course_id' => $course->id, 'course_name' => $course->course_name])}}" role="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-journals"></i> View Notes</a>
                                                    @endcan
                                                    <a href="{{route('assignment.list', ['course_id' => $course->id])}}" role="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-post"></i> View Assignments</a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@endsection
