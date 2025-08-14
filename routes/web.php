<?php

use App\Http\Controllers\AcademicSettingController;
use App\Http\Controllers\FeeHeadController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentFeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssignedTeacherController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\NoteController;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamRuleController;
use App\Http\Controllers\GradeRuleController;
use App\Http\Controllers\GradingSystemController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolSessionController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GlobalSearchController;




    Route::group(['middleware'=>'guest'],function(){
        Route::get('/',[AuthController::class,'login'])->name('login');
        Route::get('register',[AuthController::class,'register'])->name('register');
        Route::post('/login',[AuthController::class,'process'])->name('login.process');
    });
    
    Route::group(['middleware'=>'auth'],function(){
        Route::get('/home',[HomeController::class,'index'])->name('dashboard');
        Route::get('/logout',[AuthController::class,'logout'])->name('logout');
        Route::get('/change-password',[AuthController::class,'updatePasword'])->name('password.edit');
        Route::post('/change-password',[AuthController::class,'processUpdatePasword'])->name('password.update');

        // Global Search
        Route::get('/search', [GlobalSearchController::class, 'search'])->name('global.search');
        Route::get('/search/ajax', [GlobalSearchController::class, 'ajaxSearch'])->name('global.search.ajax');

        //academic setting
        Route::get('/academics/settings',[AcademicSettingController::class,'index'])->name('academic.setting');


        //session
        Route::post('session/store',[SchoolSessionController::class,'store'])->name('session.store');
        Route::post('session/browse', [SchoolSessionController::class, 'browse'])->name('session.browse');
        Route::get('session/reset', [SchoolSessionController::class, 'reset'])->name('session.reset');
        Route::delete('session/{id}', [SchoolSessionController::class, 'destroy'])->name('session.destroy');

        //semester
        Route::post('semester/store',[SemesterController::class,'store'])->name('semester.store');


        //class
        Route::get('/classes', [SchoolClassController::class, 'index'])->name('class.list');
        Route::post('class/store',[SchoolClassController::class,'store'])->name('class.store');
        Route::get('/class/edit/{id}', [SchoolClassController::class, 'edit'])->name('class.edit');
        Route::post('class/update', [SchoolClassController::class, 'update'])->name('class.update');


        //section
        Route::post('section/create', [SectionController::class, 'store'])->name('section.create');
        Route::post('section/store', [SectionController::class, 'store'])->name('section.store');
        Route::get('/section/edit/{id}', [SectionController::class, 'edit'])->name('section.edit');
        Route::post('section/update', [SectionController::class, 'update'])->name('section.update');
        Route::get('/section/{id}', [SectionController::class, 'getByClassId'])->name('get.sections.courses.by.classId');

        // Courses
        Route::post('course/store', [CourseController::class, 'store'])->name('course.store');
        Route::post('course/update', [CourseController::class, 'update'])->name('course.update');
        Route::get('course/edit/{id}', [CourseController::class, 'edit'])->name('course.edit');

        // Fee Management
        Route::get('/fees/heads', [FeeHeadController::class, 'index'])->name('fees.heads.index');
        Route::get('/fees/heads/create', [FeeHeadController::class, 'create'])->name('fees.heads.create');
        Route::post('/fees/heads/store', [FeeHeadController::class, 'store'])->name('fees.heads.store');
        Route::get('/fees/heads/{id}/edit', [FeeHeadController::class, 'edit'])->name('fees.heads.edit');
        Route::post('/fees/heads/{id}/update', [FeeHeadController::class, 'update'])->name('fees.heads.update');
        Route::delete('/fees/heads/{id}', [FeeHeadController::class, 'destroy'])->name('fees.heads.destroy');

        Route::get('/fees/structures', [FeeStructureController::class, 'index'])->name('fees.structures.index');
        Route::get('/fees/structures/create', [FeeStructureController::class, 'create'])->name('fees.structures.create');
        Route::post('/fees/structures/store', [FeeStructureController::class, 'store'])->name('fees.structures.store');
        Route::get('/fees/structures/{id}/edit', [FeeStructureController::class, 'edit'])->name('fees.structures.edit');
        Route::post('/fees/structures/{id}/update', [FeeStructureController::class, 'update'])->name('fees.structures.update');
        Route::delete('/fees/structures/{id}', [FeeStructureController::class, 'destroy'])->name('fees.structures.destroy');
        Route::post('/fees/structures/assign-to-class', [FeeStructureController::class, 'assignToClass'])->name('fees.structures.assign.class');

        Route::get('/student-fees', [StudentFeeController::class, 'index'])->name('student.fees.index');
        Route::get('/student-fees/{student_id}', [StudentFeeController::class, 'show'])->name('student.fees.show');
        Route::post('/student-fees/{id}/payment', [StudentFeeController::class, 'updatePayment'])->name('student.fees.payment');
        Route::post('/student-fees/{id}/discount', [StudentFeeController::class, 'addDiscount'])->name('student.fees.discount');
        Route::post('/student-fees/assign', [StudentFeeController::class, 'assign'])->name('student.fees.assign');


        //teacher
        Route::get('teacher/add',[AcademicSettingController::class,'create'])->name('teacher.create');
        Route::post('teacher/store',[UserController::class,'storeTeacher'])->name('teacher.store');
        Route::post('teacher/assign', [AssignedTeacherController::class, 'store'])->name('teacher.assign');
        Route::get('/teachers', [UserController::class, 'getTeacherList'])->name('teacher.list');
        Route::get('teacher/profile/{id}', [UserController::class, 'showTeacherProfile'])->name('teacher.profile');
        Route::get('/teachers/{id}', [UserController::class, 'editTeacher'])->name('teacher.edit');
        Route::post('teacher/update', [UserController::class, 'updateTeacher'])->name('teacher.update');

        //student
        Route::get('/students/add', [UserController::class, 'createStudent'])->name('student.create');
        Route::post('student/store', [UserController::class, 'storeStudent'])->name('student.store');
        Route::get('/students', [UserController::class, 'getStudentList'])->name('student.list');
        Route::get('/students/{id}', [UserController::class, 'editStudent'])->name('student.edit');
        Route::post('student/update', [UserController::class, 'updateStudent'])->name('student.update');
        Route::get('/students/profile/{id}', [UserController::class, 'showStudentProfile'])->name('student.profile');

        //exams
        Route::get('/exams', [ExamController::class, 'index'])->name('exam.list');
        Route::get('/exams/view/{exam}', [ExamController::class, 'show'])->name('exam.view');
        Route::get('/exams/add', [ExamController::class, 'create'])->name('exam.create');
        Route::post('/exams/store', [ExamController::class, 'store'])->name('exam.store');

        //exam rules
        Route::get('/exams/rules', [ExamRuleController::class, 'index'])->name('exam.rule.list');
        Route::get('/exams/rules/add', [ExamRuleController::class, 'create'])->name('exam.rule.create');
        Route::post('/exams/rules/store', [ExamRuleController::class, 'store'])->name('exam.rule.store');
        Route::get('/exams/rules/edit', [ExamRuleController::class, 'edit'])->name('exam.rule.edit');
        Route::post('/exams/rules/update', [ExamRuleController::class, 'update'])->name('exam.rule.update');

        //grade
        Route::get('/exams/grade/create', [GradingSystemController::class, 'create'])->name('exam.grade.system.create');
        Route::post('/exams/grade/store', [GradingSystemController::class, 'store'])->name('exam.grade.system.store');
        Route::get('/exams/grade', [GradingSystemController::class, 'index'])->name('exam.grade.system.list');
        Route::get('/exams/grade/rules/add', [GradeRuleController::class, 'create'])->name('exam.grade.system.rule.create');
        Route::post('/exams/grade/rule/store', [GradeRuleController::class, 'store'])->name('exam.grade.system.rule.store');
        Route::get('/exams/grade/rules', [GradeRuleController::class, 'index'])->name('exam.grade.system.rule.list');
        Route::post('/exams/grade/rule/delete', [GradeRuleController::class, 'destroy'])->name('exam.grade.system.rule.delete');


        //mark
        Route::get('/marks', [MarkController::class, 'showCourseMark'])->name('course.mark');
        Route::get('/marks/create', [MarkController::class, 'create'])->name('course.mark.create');
        Route::get('/marks/list', [MarkController::class, 'showCourseMark'])->name('course.mark.list.show');

        //notice
        Route::get('/notice/create', [NoticeController::class, 'create'])->name('notice.create');
        Route::post('/notice/store', [NoticeController::class, 'store'])->name('notice.store');

        //syllabus
        Route::get('/syllabuses', [SyllabusController::class, 'index'])->name('syllabus.index');
        Route::get('/syllabuses', [SyllabusController::class, 'index'])->name('syllabus.list');
        Route::get('/syllabus/add', [SyllabusController::class, 'create'])->name('syllabus.create');
        Route::post('/syllabus/store', [SyllabusController::class, 'store'])->name('syllabus.store');
        Route::get('/syllabus/{id}/edit', [SyllabusController::class, 'edit'])->name('syllabus.edit');
        Route::put('/syllabus/{id}', [SyllabusController::class, 'update'])->name('syllabus.update');
        Route::delete('/syllabus/{id}', [SyllabusController::class, 'destroy'])->name('syllabus.destroy');
        Route::get('/syllabus/{id}/download', [SyllabusController::class, 'download'])->name('syllabus.download');
        Route::get('/course/syllabus', [SyllabusController::class, 'index'])->name('course.syllabus.index');


        // Routines
        Route::get('/routines', [RoutineController::class, 'show'])->name('routine.list');
        Route::get('/routine/add', [RoutineController::class, 'create'])->name('routine.create');
        Route::post('/routine/store', [RoutineController::class, 'store'])->name('routine.store');
        Route::get('/routine/{id}/edit', [RoutineController::class, 'edit'])->name('routine.edit');
        Route::put('/routine/{id}', [RoutineController::class, 'update'])->name('routine.update');
        Route::delete('/routine/{id}', [RoutineController::class, 'destroy'])->name('routine.destroy');

        
        //promotion
        Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.list');
        Route::get('/promotions/promote', [PromotionController::class, 'create'])->name('promotions.create');
        Route::post('/promotions/promote', [PromotionController::class, 'store'])->name('promotions.store');

        //course teacher
        Route::get('courses/teachers', [AssignedTeacherController::class, 'getTeacherCourses'])->name('course.teacher.list');
        Route::get('courses/student/{student_id}', [CourseController::class, 'getStudentCourses'])->name('course.student.list');

        // Assignment
    Route::get('courses/assignments', [AssignmentController::class, 'getCourseAssignments'])->name('assignment.list');
    Route::get('courses/assignments/show', [AssignmentController::class, 'getCourseAssignments'])->name('assignment.list.show');
        Route::get('courses/assignments/create', [AssignmentController::class, 'create'])->name('assignment.create');
        Route::post('courses/assignments/create', [AssignmentController::class, 'store'])->name('assignment.store');

    // Student & Teacher can view assignments by course (existing name compatibility)

    // Notes
    Route::get('courses/notes', [NoteController::class, 'list'])->name('notes.list');
    Route::get('courses/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('courses/notes/store', [NoteController::class, 'store'])->name('notes.store');
    Route::get('notes/download/{id}', [NoteController::class, 'download'])->name('notes.download');
    });
 

