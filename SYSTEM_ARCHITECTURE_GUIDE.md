# School Management System - Complete Architecture Guide

## Table of Contents
1. [System Overview](#system-overview)
2. [Authentication Flow](#authentication-flow)
3. [Interface Pattern (Repository Pattern)](#interface-pattern)
4. [Permission System](#permission-system)
5. [Seeders and Database Setup](#seeders-and-database-setup)
6. [Data Flow Architecture](#data-flow-architecture)
7. [User Roles and Access Control](#user-roles-and-access-control)
8. [Code Examples](#code-examples)

## System Overview

This is a Laravel-based School Management System that follows the **Repository Pattern** with **Interface-based architecture**. The system uses **Spatie Laravel Permission** package for role-based access control.

### Key Technologies:
- **Laravel Framework** (Latest version)
- **Spatie Laravel Permission** (Role & Permission management)
- **Repository Pattern** (Data access abstraction)
- **Interface-based architecture** (Dependency injection)
- **MySQL Database**

---

## Authentication Flow

### 1. Login Process
```
User Input (Email/Password) 
    ↓
AuthController::process()
    ↓
Laravel Auth::attempt()
    ↓
Check User Credentials
    ↓
Redirect to Dashboard (if successful)
```

### 2. Authentication Controller (`AuthController.php`)
```php
public function process(Request $request) {
    $validate = Validator::make($request->all(), [
        'email' => ['required', 'email'],
        'password' => ['required']
    ]);
    
    if($validate->passes()) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('dashboard');
        }
        return redirect()->back()->with('error', 'Either Email or Password is wrong');
    }
    return redirect()->back()->withErrors($validate);
}
```

### 3. User Model with Automatic Permission Assignment
```php
protected static function boot() {
    parent::boot();
    
    static::created(function ($user) {
        // Automatically assign permissions to students
        if ($user->role === 'student') {
            $user->givePermissionTo([
                'view notes',
                'view assignments', 
                'view syllabi',
                'view marks'
            ]);
        }
    });
}
```

---

## Interface Pattern (Repository Pattern)

### Why Interfaces?
1. **Dependency Injection**: Controllers depend on interfaces, not concrete classes
2. **Testability**: Easy to mock interfaces for testing
3. **Flexibility**: Can swap implementations without changing controllers
4. **Maintainability**: Clear contracts between layers

### Architecture Flow:
```
Controller → Interface → Repository → Model → Database
```

### Example Implementation:

#### 1. Interface Definition (`UserInterface.php`)
```php
interface UserInterface {
    public function createTeacher($request);
    public function updateTeacher($request);
    public function createStudent($request);
    public function getAllStudents($current_session, $class_id, $section_id);
    public function findStudent($id);
    public function getAllTeachers();
}
```

#### 2. Repository Implementation (`UserRepository.php`)
```php
class UserRepository implements UserInterface {
    
    public function createTeacher($request) {
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'email' => $request['email'],
                    'role' => 'teacher',
                    'password' => Hash::make($request['password']),
                ]);
                
                // Auto-assign teacher permissions
                $user->givePermissionTo([
                    'create exams',
                    'view exams',
                    'create assignments',
                    'view assignments',
                    'create notes',
                    'view notes',
                    'save marks',
                    'view users'
                ]);
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Teacher. '.$e->getMessage());
        }
    }
}
```

#### 3. Service Provider Binding (`AppServiceProvider.php`)
```php
public function register(): void {
    // Bind interface to repository
    $this->app->bind(\App\Interfaces\UserInterface::class, \App\Repositories\UserRepository::class);
    $this->app->bind(\App\Interfaces\NoteInterface::class, \App\Repositories\NoteRepository::class);
    $this->app->bind(\App\Interfaces\MarkInterface::class, \App\Repositories\MarkRepository::class);
    // ... more bindings
}
```

#### 4. Controller Usage (`UserController.php`)
```php
class UserController extends Controller {
    protected $userRepository;
    
    public function __construct(UserInterface $userRepository) {
        $this->userRepository = $userRepository; // Injected automatically
    }
    
    public function storeTeacher(TeacherStoreRequest $request) {
        try {
            $this->userRepository->createTeacher($request->validated());
            return back()->with('status', 'Teacher creation was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
```

---

## Permission System

### Spatie Laravel Permission Package
This system uses **Spatie Laravel Permission** for role and permission management.

### Permission Structure:
```
User → hasMany → Permissions
User → hasMany → Roles → hasMany → Permissions
```

### Available Permissions:
1. **User Management**: create users, view users, edit users, delete users
2. **Academic**: promote students, view marks, save marks
3. **Content**: create notes, view notes, create assignments, view assignments
4. **Exams**: create exams, view exams, create exam rules, edit exam rules
5. **Administrative**: create sessions, create semesters, assign teachers
6. **Fee Management**: create fee heads, view fee heads, manage student fees

### Permission Assignment Flow:

#### 1. Admin Gets All Permissions (in PermissionSeeder)
```php
$user->givePermissionTo([
    'create school sessions',
    'create users',
    'edit users', 
    'view users',
    'promote students',
    'create exams',
    'view exams',
    'create notes',
    'view notes',
    'save marks',
    'view marks'
    // ... all permissions
]);
```

#### 2. Teachers Get Specific Permissions (in UserRepository)
```php
$user->givePermissionTo([
    'create exams',
    'view exams',
    'create assignments',
    'view assignments', 
    'create notes',
    'view notes',
    'save marks',
    'view users'
]);
```

#### 3. Students Get Basic Permissions (in User Model boot method)
```php
if ($user->role === 'student') {
    $user->givePermissionTo([
        'view notes',
        'view assignments',
        'view syllabi', 
        'view marks'
    ]);
}
```

### Checking Permissions in Controllers:
```php
// Method 1: Middleware (commented in UserController)
$this->middleware(['can:view users']);

// Method 2: Direct check
if (auth()->user()->can('create notes')) {
    // Allow action
}

// Method 3: Gate check
Gate::allows('create notes')
```

---

## Seeders and Database Setup

### Database Seeding Flow:
```
DatabaseSeeder → PermissionSeeder → UserSeeder
```

### 1. PermissionSeeder.php
**Purpose**: Creates all permissions and the admin user

```php
public function run() {
    // Reset cached permissions
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    
    // Create permissions
    Permission::create(['name' => 'create users']);
    Permission::create(['name' => 'view users']);
    Permission::create(['name' => 'edit users']);
    // ... more permissions
    
    // Create admin user
    $user = \App\Models\User::create([
        'email' => 'admin@gmail.com',
        'first_name' => 'pema',
        'last_name' => 'lama',
        'password' => Hash::make('admin'),
        'role' => 'admin'
    ]);
    
    // Give all permissions to admin
    $user->givePermissionTo([
        'create school sessions',
        'create users',
        'edit users',
        // ... all permissions
    ]);
}
```

### 2. UserSeeder.php  
**Purpose**: Creates sample users for testing

```php
public function run(): void {
    // Create admin user
    DB::table('users')->insert([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@example.com',
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);
    
    // Create test users
    for ($i = 1; $i <= 10; $i++) {
        DB::table('users')->insert([
            'first_name' => 'User'.$i,
            'last_name' => 'Test',
            'email' => 'user'.$i.'@example.com',
            'role' => 'user',
            'password' => Hash::make('password'.$i),
        ]);
    }
}
```

### 3. DatabaseSeeder.php
**Purpose**: Main seeder that calls other seeders

```php
public function run(): void {
    $this->call([
        PermissionSeeder::class,
        UserSeeder::class,
    ]);
}
```

### Running Seeders:
```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=PermissionSeeder

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

---

## Data Flow Architecture

### 1. Request Lifecycle:
```
HTTP Request
    ↓
Route (web.php)
    ↓
Middleware (auth, permission)
    ↓
Controller
    ↓
Interface
    ↓ 
Repository
    ↓
Model
    ↓
Database
    ↓
Response (View/JSON)
```

### 2. Example: Creating a Note

#### Route Definition:
```php
Route::post('/notes', [NoteController::class, 'store'])
    ->middleware(['auth', 'can:create notes'])
    ->name('notes.store');
```

#### Controller:
```php
class NoteController extends Controller {
    protected $noteRepository;
    
    public function __construct(NoteInterface $noteRepository) {
        $this->noteRepository = $noteRepository;
    }
    
    public function store(Request $request) {
        return $this->noteRepository->create($request->validated());
    }
}
```

#### Repository:
```php
class NoteRepository implements NoteInterface {
    public function create($request) {
        return Note::create([
            'title' => $request['title'],
            'content' => $request['content'],
            'teacher_id' => auth()->id(),
        ]);
    }
}
```

### 3. Model Relationships:
```php
class User extends Authenticatable {
    // One-to-One
    public function academic_info() {
        return $this->hasOne(StudentAcademicInfo::class, 'student_id');
    }
    
    // One-to-Many 
    public function notes() {
        return $this->hasMany(Note::class, 'teacher_id');
    }
    
    public function marks() {
        return $this->hasMany(Mark::class, 'student_id');
    }
}
```

---

## User Roles and Access Control

### Role Hierarchy:
```
Admin (Full Access)
    ↓
Teacher (Limited Access)
    ↓  
Student (View Only)
```

### Role-Based Permissions:

#### Admin Capabilities:
- User management (create, edit, delete teachers/students)
- Academic settings (sessions, semesters, classes)
- System configuration
- All teacher and student capabilities

#### Teacher Capabilities:
- Create and manage exams
- Create assignments and notes
- Input and view student marks
- View student information
- Access course materials

#### Student Capabilities:
- View notes and assignments
- View marks and syllabi
- View routines and notices
- Update personal information (limited)

### Permission Checking Examples:

#### In Controllers:
```php
// Check permission before action
if (!auth()->user()->can('create notes')) {
    abort(403, 'Unauthorized action.');
}

// Or use middleware
$this->middleware(['can:view users']);
```

#### In Views (Blade):
```php
@can('create notes')
    <a href="{{ route('notes.create') }}" class="btn btn-primary">
        Create Note
    </a>
@endcan

@role('admin')
    <div class="admin-panel">
        <!-- Admin only content -->
    </div>
@endrole
```

#### In Routes:
```php
Route::group(['middleware' => ['auth', 'can:manage users']], function () {
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::post('/admin/users', [UserController::class, 'store']);
});
```

---

## Code Examples

### 1. Complete CRUD with Interface Pattern

#### Interface:
```php
interface StudentInterface {
    public function create(array $data);
    public function findById(int $id);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getAllByClass(int $classId);
}
```

#### Repository:
```php
class StudentRepository implements StudentInterface {
    public function create(array $data) {
        return DB::transaction(function () use ($data) {
            $student = User::create(array_merge($data, ['role' => 'student']));
            
            // Create academic info
            StudentAcademicInfo::create([
                'student_id' => $student->id,
                'session_id' => $data['session_id'],
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
            ]);
            
            // Auto-assign student permissions
            $student->givePermissionTo(['view notes', 'view assignments']);
            
            return $student;
        });
    }
    
    public function getAllByClass(int $classId) {
        return User::where('role', 'student')
            ->whereHas('academic_info', function ($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->with(['academic_info.class', 'academic_info.section'])
            ->get();
    }
}
```

### 2. Permission-Based Route Groups:
```php
// Admin routes
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::resource('users', UserController::class);
    Route::resource('sessions', SchoolSessionController::class);
});

// Teacher routes  
Route::group(['middleware' => ['auth', 'role:teacher']], function () {
    Route::resource('notes', NoteController::class);
    Route::resource('assignments', AssignmentController::class);
});

// Student routes
Route::group(['middleware' => ['auth', 'role:student']], function () {
    Route::get('my-marks', [MarkController::class, 'studentMarks']);
    Route::get('my-assignments', [AssignmentController::class, 'studentView']);
});
```

### 3. Advanced Permission Checking:
```php
class NoteController extends Controller {
    public function show(Note $note) {
        // Students can only view notes for their class
        if (auth()->user()->isStudent()) {
            $userClass = auth()->user()->academic_info->class_id;
            $noteClass = $note->assignedTeacher->class_id;
            
            if ($userClass !== $noteClass) {
                abort(403, 'You can only view notes for your class.');
            }
        }
        
        return view('notes.show', compact('note'));
    }
}
```

---

## Summary

This School Management System uses a robust architecture with:

1. **Repository Pattern** for clean data access
2. **Interface-based design** for flexibility and testability  
3. **Spatie Permission package** for comprehensive role/permission management
4. **Automatic permission assignment** based on user roles
5. **Transaction-based operations** for data integrity
6. **Comprehensive seeding system** for easy setup

The system automatically assigns permissions when users are created, maintains clean separation of concerns through interfaces, and provides fine-grained access control throughout the application.
