@extends('layouts.app')

@section('content')
<script src="{{ asset('js/masonry.pkgd.min.js') }}"></script>
<div class="container-fluid">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10 main-content">
            <div class="row pt-2">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3">
                        <i class="bi bi-tools"></i> Academic Settings
                    </h1>

                    <!-- Current Session Info -->
                    <div class="mb-4">
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <div>
                                @if(session()->has('browse_session_id'))
                                    <strong>Browsing Session:</strong> 
                                    @php 
                                        $browse_session = \App\Models\SchoolSession::find(session('browse_session_id'));
                                    @endphp
                                    {{ $browse_session ? $browse_session->session_name : 'Unknown' }}
                                    <small class="text-muted">(Use sidebar to switch sessions)</small>
                                @else
                                    <strong>Current Session:</strong> 
                                    @php 
                                        $current_session = \App\Models\SchoolSession::latest()->first();
                                    @endphp
                                    {{ $current_session ? $current_session->session_name : 'No session created' }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Session Management Note -->
                    
                    <div class="mb-4">
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>
                                <strong>Managing Current Session:</strong> You are managing the current active academic session.
                                <br><small class="text-muted">Use the sidebar to browse previous sessions if needed.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Setup Section -->
                    <div class="mb-5">
                        <h4 class="mb-3"><i class="bi bi-gear-fill text-primary"></i> Academic Setup</h4>
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                @if ($latest_school_session_id == $current_school_session_id)
                                <div class="mb-4">
                                    <div class="p-3 border bg-light shadow-sm h-100">
                                        <h6><i class="bi bi-calendar-plus text-success"></i> Create Session</h6>
                                        <p class="text-danger small">
                                            <i class="bi bi-exclamation-diamond-fill me-1"></i> Create one Session per academic year. Last created session will be considered as the latest academic session.
                                        </p>
                                        <form action="{{ route('session.store') }}"  method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <input type="text" class="form-control form-control-sm" placeholder="2021 - 2022" aria-label="Current Session" name="session_name" required>
                                            </div>
                                            <button class="btn btn-sm btn-outline-primary" type="submit"><i class="bi bi-check2"></i> Create</button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Create Class -->
                                <div class="mb-4">
                                    <div class="p-3 border bg-light shadow-sm h-100">
                                        <h6><i class="bi bi-building text-primary"></i> Create Class</h6>
                                        <p class="small text-muted">Add new classes for the selected session</p>
                                        <form action="{{ route('class.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                                            <div class="mb-3">
                                                <input type="text" class="form-control form-control-sm" name="class_name" placeholder="Class name" aria-label="Class name" required>
                                            </div>
                                            <button class="btn btn-sm btn-outline-primary" type="submit"><i class="bi bi-check2"></i> Create</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <!-- Create Semester -->
                                <div class="mb-4">
                                    <div class="p-3 border bg-light shadow-sm h-100">
                                        <h6><i class="bi bi-calendar-range text-info"></i> Create Semester</h6>
                                        <p class="small text-muted">Add semesters for the selected session</p>
                                        <form action="{{ route('semester.store') }}" method="POST">
                                            @csrf
                                        <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                                        <div class="mb-3">
                                            <label class="form-label small">Semester name<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="text" class="form-control form-control-sm" placeholder="First Semester" aria-label="Semester name" name="semester_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="inputStarts" class="form-label small">Starts<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="date" class="form-control form-control-sm" id="inputStarts" placeholder="Starts" name="start_date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="inputEnds" class="form-label small">Ends<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                            <input type="date" class="form-control form-control-sm" id="inputEnds" placeholder="Ends" name="end_date" required>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-check2"></i> Create</button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Create Section -->
                                <div class="mb-4">
                                    <div class="p-3 border bg-light shadow-sm h-100">
                                        <h6><i class="bi bi-collection text-secondary"></i> Create Section</h6>
                                        <p class="small text-muted">Add section and assign to classes</p>
                                        <form action="{{ route('section.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                                            <div class="mb-3">
                                                <label class="form-label small">Section name</label>
                                                <input class="form-control form-control-sm" name="section_name" type="text" placeholder="Section name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Room No.</label>
                                                <input class="form-control form-control-sm" name="room_no" type="text" placeholder="Room No." required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Assign to class:</label>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm" name="class_id" required>
                                                    @isset($school_classes)
                                                        @foreach ($school_classes as $school_class)
                                                        <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-check2"></i> Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course & Teacher Management Section -->
                    <div class="mb-5">
                        <h4 class="mb-3"><i class="bi bi-journal-text text-success"></i> Course & Teacher Management</h4>
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <!-- Create Course -->
                                <div class="mb-4">
                                    <div class="p-3 border bg-light shadow-sm h-100">
                                        <h6><i class="bi bi-book text-info"></i> Create Course</h6>
                                        <p class="small text-muted">Add courses and assign to semesters and classes</p>
                                        <form action="{{ route('course.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                                            <div class="mb-3">
                                                <label class="form-label small">Course name</label>
                                                <input type="text" class="form-control form-control-sm" name="course_name" placeholder="Course name" aria-label="Course name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Course Type<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                                <select class="form-select form-select-sm" name="course_type" aria-label=".form-select-sm" required>
                                                    <option value="Core">Core</option>
                                                    <option value="General">General</option>
                                                    <option value="Elective">Elective</option>
                                                    <option value="Optional">Optional</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Assign to semester<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm" name="semester_id" required>
                                                    @isset($semesters)
                                                        @foreach ($semesters as $semester)
                                                        <option value="{{$semester->id}}">{{$semester->semester_name}}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Assign to class<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm" name="class_id" required>
                                                    @isset($school_classes)
                                                        @foreach ($school_classes as $school_class)
                                                        <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <button class="btn btn-sm btn-outline-primary" type="submit"><i class="bi bi-check2"></i> Create</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <!-- Assign Teacher -->
                                <div class="mb-4">
                                    <div class="p-3 border bg-light shadow-sm h-100">
                                        <h6><i class="bi bi-person-plus text-warning"></i> Assign Teacher</h6>
                                        <p class="small text-muted">Assign teachers to courses for the selected session</p>
                                        <form action="{{ route('teacher.assign') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="session_id" value="{{$current_school_session_id}}">
                                            <div class="mb-3">
                                                <label class="form-label small">Select Teacher<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm" name="teacher_id" required>
                                                    @isset($teachers)
                                                        @foreach ($teachers as $teacher)
                                                        <option value="{{$teacher->id}}">{{$teacher->first_name}} {{$teacher->last_name}}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Assign to semester<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm" name="semester_id" required>
                                                    @isset($semesters)
                                                        @foreach ($semesters as $semester)
                                                        <option value="{{$semester->id}}">{{$semester->semester_name}}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Assign to class<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                                <select id="inputAssignToClass" class="form-select form-select-sm" aria-label=".form-select-sm" name="class_id" required>
                                                    @isset($school_classes)
                                                        <option selected disabled>Please select a class</option>
                                                        @foreach ($school_classes as $school_class)
                                                        <option value="{{$school_class->id}}">{{$school_class->class_name}}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Assign to section<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                                <select class="form-select form-select-sm" id="section-select" aria-label=".form-select-sm" name="section_id" required>
                                                    @if ($school_sections->isNotEmpty())
                                                        <option>Select a section</option>
                                                        @foreach ($school_sections as $school_section)
                                                            <option value="{{ $school_section->id }}">{{ $school_section->section_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Assign to course<sup><i class="bi bi-asterisk text-primary"></i></sup></label>
                                                <select class="form-select form-select-sm" id="course-select" aria-label=".form-select-sm" name="course_id" required>
                                                @if ($school_sections->isNotEmpty())
                                                        <option>Select a course</option>
                                                        @foreach ($courses as $courses)
                                                            <option value="{{ $courses->id }}">{{ $courses->course_name }}</option>
                                                        @endforeach
                                                @endif
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-check2"></i> Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Session Management Section -->
                    <div class="mb-5">
                        <h4 class="mb-3"><i class="bi bi-calendar-x text-danger"></i> Session Management</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="p-3 border bg-light shadow-sm">
                                    <h6><i class="bi bi-trash text-danger"></i> Delete Academic Sessions</h6>
                                    <p class="small text-muted mb-3">
                                        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                                        Warning: Deleting an academic session will permanently remove all associated data. You cannot delete the latest academic session.
                                    </p>
                                    
                                    @if($school_sessions->count() > 1)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Session Name</th>
                                                        <th>Created</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($school_sessions as $session)
                                                        <tr>
                                                            <td>{{ $session->session_name }}</td>
                                                            <td>{{ $session->created_at->format('M d, Y') }}</td>
                                                            <td>
                                                                @if($session->id == $latest_school_session_id)
                                                                    <span class="badge bg-success">Current</span>
                                                                @elseif(session()->has('browse_session_id') && session('browse_session_id') == $session->id)
                                                                    <span class="badge bg-info">Browsing</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Previous</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($session->id != $latest_school_session_id)
                                                                    <form action="{{ route('session.destroy', $session->id) }}" method="POST" 
                                                                          onsubmit="return confirm('Are you sure you want to delete this session? This action cannot be undone.')" 
                                                                          class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                            <i class="bi bi-trash"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <span class="text-muted small">Cannot delete current session</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Only one academic session exists. You need at least one session to maintain the system.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#inputAssignToClass').change(function(){
            var classId = $(this).val(); 
            var url = '{{ route("get.sections.courses.by.classId", "classId") }}'; 
            url = url.replace('classId', classId);
            $.ajax({
                url:url ,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if(response.sections){
                        var sectionsDropdown = $('#inputAssignToSection');
                        sectionsDropdown.empty(); 
                        sectionsDropdown.append($('<option>').text('Please select a section').attr('value', 0))
                        response.sections.forEach(function(section) {
                            sectionsDropdown.append($('<option>').text(section.section_name).attr('value', section.id));
                        });
                    }
                }
            })            
        })
    });
</script>
@endsection
