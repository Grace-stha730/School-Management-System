@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('layouts.left-menu')
        <div class="main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-arrow-up-circle text-primary"></i> Student Promotions
                    </h1>
                    @include('session-messages')
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Academic</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Student Promotions</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col">
                    <div class="p-3 border bg-light shadow-sm">
                        <h5><i class="bi bi-list-ul text-info"></i> Previous Session Classes</h5>
                        <p class="text-muted">Select a class to promote students to the current academic session.</p>
                        
                        @if($previousSessionClasses->count() > 0)
                        <div class="row">
                            @foreach($previousSessionClasses as $class)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $class->schoolClass->class_name }}</h6>
                                        <p class="card-text text-muted">
                                            Students from previous session
                                        </p>
                                        
                                        @if($previousSessionSections->count() > 0)
                                        <div class="mt-2">
                                            <h6 class="text-sm">Sections:</h6>
                                            @foreach($previousSessionSections as $section)
                                                <a href="{{ route('promotions.create', [
                                                    'previous_class_id' => $class->class_id,
                                                    'previous_section_id' => $section->section_id,
                                                    'previousSessionId' => $previousSessionId
                                                ]) }}" class="btn btn-sm btn-outline-primary me-1 mb-1">
                                                    {{ $section->section->section_name }}
                                                </a>
                                            @endforeach
                                        </div>
                                        @else
                                        <a href="{{ route('promotions.create', [
                                            'previous_class_id' => $class->class_id,
                                            'previous_section_id' => 1,
                                            'previousSessionId' => $previousSessionId
                                        ]) }}" class="btn btn-primary">
                                            Promote Students
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No Previous Session Classes Found</h5>
                            <p class="text-muted">There are no classes from the previous session available for promotion.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
