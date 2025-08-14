@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3"><i class="bi bi-journal-plus"></i> Upload Note</h1>
                    @include('session-messages')
                    <div class="bg-white border p-3 shadow-sm">
                        <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="class_id" value="{{ $class_id }}">
                            <input type="hidden" name="section_id" value="{{ $section_id }}">
                            <input type="hidden" name="course_id" value="{{ $course_id }}">
                            <input type="hidden" name="semester_id" value="{{ $semester_id }}">
                            <div class="mb-3">
                                <label class="form-label">Note Title</label>
                                <input type="text" name="note_name" class="form-control form-control-sm" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Select File</label>
                                <input type="file" name="file" class="form-control form-control-sm" required>
                                <small class="text-muted">Allowed: jpg,jpeg,bmp,png,doc,docx,csv,rtf,xlsx,xls,txt,pdf,zip</small>
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-check2"></i> Upload</button>
                        </form>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@endsection
