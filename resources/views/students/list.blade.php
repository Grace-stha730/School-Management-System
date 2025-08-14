@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-person-lines-fill"></i> Student List
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Student List</li>
                        </ol>
                    </nav>
                    @include('session-messages')
                    <h6>Filter students by class and section:</h6>
                    <div class="mb-4 mt-4">
                        <form class="row g-3" action="{{route('student.list')}}" method="GET">
                            <div class="col-md-4">
                                <label for="class-select" class="form-label">Class</label>
                                <select onchange="getSections(this);" class="form-select" id="class-select" aria-label="Class" name="class_id" required>
                                    @isset($school_classes)
                                        <option selected disabled>Please select a class</option>
                                        @foreach ($school_classes as $school_class)
                                            <option value="{{$school_class->id}}" {{($school_class->id == request()->query('class_id'))?'selected="selected"':''}}>{{$school_class->class_name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="section-select" class="form-label">Section</label>
                                <select class="form-select" id="section-select" aria-label="Section" name="section_id" required>
                                    @if(request()->query('section_id') && request()->query('section_name'))
                                        <option value="{{request()->query('section_id')}}" selected>{{request()->query('section_name')}}</option>
                                    @else
                                        <option selected disabled>Please select a section</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="pt-4">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Load Students</button>
                                    @if(request()->query('class_id') || request()->query('section_id'))
                                        <a href="{{route('student.list')}}" class="btn btn-outline-secondary ms-2"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                        @foreach ($studentList as $student)
                            @if ($loop->first)
                                <p class="mt-3"><b>Section:</b> {{$student->section->section_name}}</p>
                                @break
                            @endif
                        @endforeach
                        <div class="bg-white border shadow-sm p-3 mt-4">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th scope="col">ID Card Number</th>
                                        <th scope="col">Photo</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($studentList as $student)
                                    <tr>
                                        <th scope="row">{{$student->id_card_number}}</th>
                                        <td>
                                            @if (isset($student->student->photo))
                                                <img src="{{asset($student->student->photo)}}" class="rounded" alt="Profile picture" height="30" width="30">
                                            @else
                                                <i class="bi bi-person-square"></i>
                                            @endif
                                        </td>
                                        <td>{{$student->student->first_name}}</td>
                                        <td>{{$student->student->last_name}}</td>
                                        <td>{{$student->student->email}}</td>
                                        <td>{{$student->student->phone}}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{route('student.profile',['id'=>$student->student->id])}}" role="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Profile</a>
                                                @can('edit users')
                                                <a href="{{route('student.edit', ['id' => $student->student->id])}}" role="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-pen"></i> Edit</a>
                                                @endcan
                                                {{-- <button type="button" class="btn btn-sm btn-primary"><i class="bi bi-trash2"></i> Delete</button> --}}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
<script>
    function getSections(obj) {
        var class_id = obj.options[obj.selectedIndex].value;

        var url = "{{route('get.sections.courses.by.classId', ':class_id')}}";
        url = url.replace(':class_id', class_id);

        fetch(url)
        .then((resp) => resp.json())
        .then(function(data) {
            var sectionSelect = document.getElementById('section-select');
            sectionSelect.options.length = 0;
            data.sections.unshift({'id': 0,'section_name': 'Please select a section'})
            data.sections.forEach(function(section, key) {
                sectionSelect[key] = new Option(section.section_name, section.id);
            });
        })
        .catch(function(error) {
            console.log(error);
        });
    }

    // Load sections on page load if class is already selected
    document.addEventListener('DOMContentLoaded', function() {
        var classSelect = document.getElementById('class-select');
        var currentClassId = {{request()->query('class_id', 0)}};
        
        if (currentClassId > 0) {
            // Trigger getSections to load sections for the currently selected class
            getSections(classSelect);
        }
    });
</script>
@endsection
