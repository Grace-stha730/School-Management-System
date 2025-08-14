@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-pencil-square"></i> Edit Syllabus
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('syllabus.index')}}">Syllabus</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>

                    <div class="mb-4 mt-4">
                        <form action="{{ route('syllabus.update', $syllabus->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12">
                                    <div class="p-3 border bg-light shadow-sm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="class_id" name="class_id" required>
                                                        <option value="">Select Class</option>
                                                        @isset($school_classes)
                                                            @foreach ($school_classes as $school_class)
                                                                <option value="{{ $school_class->id }}" 
                                                                    {{ old('class_id', $syllabus->class_id) == $school_class->id ? 'selected' : '' }}>
                                                                    {{ $school_class->class_name }}
                                                                </option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="course_id" class="form-label">Course <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="course_id" name="course_id" required>
                                                        <option value="">Select Course</option>
                                                        @isset($courses)
                                                            @foreach ($courses as $course)
                                                                <option value="{{ $course->id }}"
                                                                    {{ old('course_id', $syllabus->course_id) == $course->id ? 'selected' : '' }}>
                                                                    {{ $course->course_name }}
                                                                </option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="title" name="title" 
                                                           value="{{ old('title', $syllabus->title) }}" 
                                                           placeholder="Enter syllabus title" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="file" class="form-label">Syllabus File (PDF only)</label>
                                                    <input type="file" class="form-control" id="file" name="file" accept=".pdf">
                                                    <small class="form-text text-muted">Leave blank to keep current file</small>
                                                    @if($syllabus->file_path)
                                                        <div class="mt-2">
                                                            <span class="badge bg-info">Current file: {{ basename($syllabus->file_path) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-check-circle"></i> Update Syllabus
                                                    </button>
                                                    <a href="{{ route('syllabus.index') }}" class="btn btn-secondary">
                                                        <i class="bi bi-arrow-left"></i> Back to List
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@endsection
