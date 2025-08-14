@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-journal-text"></i> Syllabus Management
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Syllabus</li>
                        </ol>
                    </nav>
                    
                    <div class="row mb-3">
                        <div class="col">
                            <a href="{{ route('syllabus.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Syllabus
                            </a>
                        </div>
                    </div>

                    <div class="mb-4 mt-4">
                        <div class="p-3 mt-3 bg-white border shadow-sm">
                            @if(isset($syllabi) && count($syllabi) > 0)
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Class</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Created</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($syllabi as $syllabus)
                                        <tr>
                                            <td>{{ $syllabus->schoolClass->class_name ?? 'N/A' }}</td>
                                            <td>{{ $syllabus->course->course_name ?? 'N/A' }}</td>
                                            <td>{{ $syllabus->title }}</td>
                                            <td>{{ $syllabus->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('syllabus.download', $syllabus->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download"></i> Download
                                                    </a>
                                                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teacher')
                                                        <a href="{{ route('syllabus.edit', $syllabus->id) }}" 
                                                           class="btn btn-sm btn-outline-warning">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </a>
                                                        <form action="{{ route('syllabus.destroy', $syllabus->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this syllabus?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-journal-text display-1 text-muted"></i>
                                    <h5 class="mt-3 text-muted">No syllabus found</h5>
                                    <p class="text-muted">Start by adding a new syllabus.</p>
                                    <a href="{{ route('syllabus.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Add First Syllabus
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@endsection
