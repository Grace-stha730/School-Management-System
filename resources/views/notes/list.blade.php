@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3"><i class="bi bi-journals"></i> Notes @if($course_name) <small class="text-muted">( {{ $course_name }} )</small>@endif</h1>
                    @include('session-messages')
                    <div class="bg-white border p-3 shadow-sm">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    @if(!$course_name)
                                        <th>Course</th>
                                        <th>Class</th>
                                    @endif
                                    <th>Uploaded</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notes as $note)
                                    <tr>
                                        <td>{{ $note->note_name }}</td>
                                        @if(!$course_name)
                                            <td>
                                                @if($note->course)
                                                    {{ $note->course->course_name }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($note->schoolClass)
                                                    {{ $note->schoolClass->class_name }}
                                                    @if($note->section)
                                                        - {{ $note->section->section_name }}
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td>{{ $note->created_at->format('Y-m-d H:i') }}</td>
                                        <td><a href="{{ route('notes.download', $note->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Download</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ !$course_name ? '5' : '3' }}" class="text-center text-muted">No notes uploaded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@endsection
