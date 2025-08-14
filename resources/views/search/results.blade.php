@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-search"></i> Search Results
                    </h1>
                    
                    @if($query)
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Search: "{{ $query }}"</li>
                            </ol>
                        </nav>

                        <div class="mb-3">
                            <p class="text-muted">Found {{ $total_results }} results for "{{ $query }}"</p>
                        </div>

                        @if($total_results > 0)
                            <!-- Students Results -->
                            @if(count($results['students']) > 0)
                                <div class="mb-4">
                                    <h4><i class="bi bi-person-circle me-2"></i>Students ({{ count($results['students']) }})</h4>
                                    <div class="row">
                                        @foreach($results['students'] as $student)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-person-circle fs-2 text-primary me-3"></i>
                                                            <div>
                                                                <h6 class="card-title mb-1">{{ $student->first_name }} {{ $student->last_name }}</h6>
                                                                <p class="card-text text-muted small mb-1">{{ $student->email ?? 'No email' }}</p>
                                                                <a href="{{ route('student.list') }}" class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-eye"></i> View Profile
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Teachers Results -->
                            @if(count($results['teachers']) > 0)
                                <div class="mb-4">
                                    <h4><i class="bi bi-person-badge me-2"></i>Teachers ({{ count($results['teachers']) }})</h4>
                                    <div class="row">
                                        @foreach($results['teachers'] as $teacher)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-person-badge fs-2 text-success me-3"></i>
                                                            <div>
                                                                <h6 class="card-title mb-1">{{ $teacher->first_name }} {{ $teacher->last_name }}</h6>
                                                                <p class="card-text text-muted small mb-1">{{ $teacher->email ?? 'No email' }}</p>
                                                                <a href="{{ route('teacher.list') }}" class="btn btn-sm btn-outline-success">
                                                                    <i class="bi bi-eye"></i> View Profile
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Classes Results -->
                            @if(count($results['classes']) > 0)
                                <div class="mb-4">
                                    <h4><i class="bi bi-diagram-3 me-2"></i>Classes ({{ count($results['classes']) }})</h4>
                                    <div class="row">
                                        @foreach($results['classes'] as $class)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-diagram-3 fs-2 text-info me-3"></i>
                                                            <div>
                                                                <h6 class="card-title mb-1">{{ $class->class_name }}</h6>
                                                                <p class="card-text text-muted small mb-1">Academic Class</p>
                                                                <a href="{{ route('class.list') }}" class="btn btn-sm btn-outline-info">
                                                                    <i class="bi bi-eye"></i> View Class
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Courses Results -->
                            @if(count($results['courses']) > 0)
                                <div class="mb-4">
                                    <h4><i class="bi bi-book me-2"></i>Courses ({{ count($results['courses']) }})</h4>
                                    <div class="row">
                                        @foreach($results['courses'] as $course)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-book fs-2 text-warning me-3"></i>
                                                            <div>
                                                                <h6 class="card-title mb-1">{{ $course->course_name }}</h6>
                                                                <p class="card-text text-muted small mb-1">{{ $course->course_type ?? 'No type' }}</p>
                                                                <a href="{{ route('course.edit', $course->id) }}" class="btn btn-sm btn-outline-warning">
                                                                    <i class="bi bi-eye"></i> View Course
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-search display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">No results found</h5>
                                <p class="text-muted">Try searching with different keywords or check the spelling.</p>
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                    <i class="bi bi-house"></i> Back to Dashboard
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-search display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Enter a search term</h5>
                            <p class="text-muted">Use the search box above to find students, teachers, classes, or courses.</p>
                        </div>
                    @endif
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@endsection
