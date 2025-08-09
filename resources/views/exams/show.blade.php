@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-file-text"></i> View Exam
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('exam.list')}}">Exams</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Exam</li>
                        </ol>
                    </nav>

                    <div class="bg-white mt-4 p-4 border shadow-sm">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Exam Details</h5>
                                <table class="table">
                                    <tr>
                                        <th>Exam Name:</th>
                                        <td>{{ $exam->exam_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Course:</th>
                                        <td>{{ $exam->course->course_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Start Date:</th>
                                        <td>{{ $exam->start_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>End Date:</th>
                                        <td>{{ $exam->end_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $exam->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <div class="btn-group" role="group">
                                    <a href="{{route('exam.rule.create', ['exam_id' => $exam->id])}}" role="button" class="btn btn-primary">
                                        <i class="bi bi-plus"></i> Add Rule
                                    </a>
                                    <a href="{{route('exam.rule.list', ['exam_id' => $exam->id])}}" role="button" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> View Rules
                                    </a>
                                    <a href="{{route('exam.list')}}" role="button" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Back to Exams
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
