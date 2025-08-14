@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3"><i class="bi bi-calendar4-range"></i> Routine Management</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Routines</li>
                        </ol>
                    </nav>
                    
                    <div class="row mb-3">
                        <div class="col">
                            <a href="{{ route('routine.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Routine
                            </a>
                        </div>
                    </div>

                    @php
                        function getDayName($weekday) {
                            if($weekday == 1) {
                                return "MONDAY";
                            } else if($weekday == 2) {
                                return "TUESDAY";
                            } else if($weekday == 3) {
                                return "WEDNESDAY";
                            } else if($weekday == 4) {
                                return "THURSDAY";
                            } else if($weekday == 5) {
                                return "FRIDAY";
                            } else if($weekday == 6) {
                                return "SATURDAY";
                            } else if($weekday == 7) {
                                return "SUNDAY";
                            } else {
                                return "Noday";
                            }
                        }
                    @endphp
                    
                    @if(count($routines) > 0)
                        <div class="bg-white p-3 border shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Class Schedule</h5>
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'teacher')
                                    <div>
                                        <a href="{{ route('routine.create') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit Schedule
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Day</th>
                                            <th>Subjects & Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($routines as $day => $courses)
                                            <tr>
                                                <th class="bg-light">{{getDayName($day)}}</th>
                                                <td>
                                                    @php
                                                        $courses = $courses->sortBy('start');
                                                    @endphp
                                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                        @foreach($courses as $course)
                                                            <div class="badge bg-primary p-2">
                                                                <div class="fw-bold">{{$course->course->course_name}}</div>
                                                                <small>{{$course->start}} - {{$course->end}}</small>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-white p-5 border shadow-sm text-center">
                            <i class="bi bi-calendar4-range display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">No routine found</h5>
                            <p class="text-muted">Start by creating a class routine.</p>
                            <a href="{{ route('routine.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create First Routine
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@endsection
