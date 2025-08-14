# School Management System - Practical Implementation Examples

## Table of Contents
1. [Setting Up a New Feature](#setting-up-a-new-feature)
2. [Understanding Seeders in Practice](#understanding-seeders-in-practice)
3. [Interface Implementation Walkthrough](#interface-implementation-walkthrough)
4. [Permission System Examples](#permission-system-examples)
5. [Common Debugging Tips](#common-debugging-tips)

## Setting Up a New Feature

Let's walk through creating a "Library Book" feature to understand the complete flow:

### Step 1: Create the Migration
```bash
php artisan make:migration create_books_table
```

```php
// database/migrations/xxxx_create_books_table.php
public function up()
{
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('author');
        $table->string('isbn')->unique();
        $table->integer('quantity');
        $table->integer('available_quantity');
        $table->foreignId('added_by')->constrained('users');
        $table->timestamps();
    });
}
```

### Step 2: Create the Model
```bash
php artisan make:model Book
```

```php
// app/Models/Book.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author', 
        'isbn',
        'quantity',
        'available_quantity',
        'added_by'
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
    
    public function borrowedBooks()
    {
        return $this->hasMany(BorrowedBook::class);
    }
}
```

### Step 3: Create the Interface
```php
// app/Interfaces/BookInterface.php
<?php

namespace App\Interfaces;

interface BookInterface
{
    public function create(array $data);
    public function getAll();
    public function findById(int $id);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getAvailableBooks();
    public function borrowBook(int $bookId, int $studentId);
    public function returnBook(int $bookId, int $studentId);
}
```

### Step 4: Create the Repository
```php
// app/Repositories/BookRepository.php
<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\BorrowedBook;
use App\Interfaces\BookInterface;
use Illuminate\Support\Facades\DB;

class BookRepository implements BookInterface
{
    public function create(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                return Book::create([
                    'title' => $data['title'],
                    'author' => $data['author'],
                    'isbn' => $data['isbn'],
                    'quantity' => $data['quantity'],
                    'available_quantity' => $data['quantity'], // Initially all available
                    'added_by' => auth()->id(),
                ]);
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to create book: ' . $e->getMessage());
        }
    }

    public function getAll()
    {
        return Book::with('addedBy')->orderBy('title')->get();
    }

    public function findById(int $id)
    {
        return Book::with(['addedBy', 'borrowedBooks.student'])->findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $book = Book::findOrFail($id);
                $book->update($data);
                return $book;
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to update book: ' . $e->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $book = Book::findOrFail($id);
                
                // Check if book is currently borrowed
                if ($book->borrowedBooks()->where('returned_at', null)->exists()) {
                    throw new \Exception('Cannot delete book that is currently borrowed');
                }
                
                return $book->delete();
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete book: ' . $e->getMessage());
        }
    }

    public function getAvailableBooks()
    {
        return Book::where('available_quantity', '>', 0)->get();
    }

    public function borrowBook(int $bookId, int $studentId)
    {
        try {
            return DB::transaction(function () use ($bookId, $studentId) {
                $book = Book::findOrFail($bookId);
                
                if ($book->available_quantity <= 0) {
                    throw new \Exception('Book is not available for borrowing');
                }
                
                // Create borrow record
                BorrowedBook::create([
                    'book_id' => $bookId,
                    'student_id' => $studentId,
                    'borrowed_at' => now(),
                    'due_date' => now()->addDays(14), // 2 weeks borrowing period
                ]);
                
                // Decrease available quantity
                $book->decrement('available_quantity');
                
                return true;
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to borrow book: ' . $e->getMessage());
        }
    }
}
```

### Step 5: Bind Interface in Service Provider
```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    // ... existing bindings
    $this->app->bind(\App\Interfaces\BookInterface::class, \App\Repositories\BookRepository::class);
}
```

### Step 6: Create the Controller
```php
// app/Http/Controllers/BookController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\BookInterface;
use App\Http\Requests\BookStoreRequest;

class BookController extends Controller
{
    protected $bookRepository;

    public function __construct(BookInterface $bookRepository)
    {
        $this->middleware(['auth']);
        $this->middleware(['can:view books'])->only(['index', 'show']);
        $this->middleware(['can:create books'])->only(['create', 'store']);
        $this->middleware(['can:edit books'])->only(['edit', 'update']);
        $this->middleware(['can:delete books'])->only(['destroy']);
        
        $this->bookRepository = $bookRepository;
    }

    public function index()
    {
        $books = $this->bookRepository->getAll();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(BookStoreRequest $request)
    {
        try {
            $this->bookRepository->create($request->validated());
            return redirect()->route('books.index')
                ->with('success', 'Book created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(int $id)
    {
        $book = $this->bookRepository->findById($id);
        return view('books.show', compact('book'));
    }

    public function borrow(Request $request, int $id)
    {
        try {
            $this->bookRepository->borrowBook($id, $request->student_id);
            return back()->with('success', 'Book borrowed successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
```

### Step 7: Create Form Request Validation
```php
// app/Http/Requests/BookStoreRequest.php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookStoreRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('create books');
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Book title is required',
            'isbn.unique' => 'A book with this ISBN already exists',
        ];
    }
}
```

### Step 8: Add Routes
```php
// routes/web.php
Route::group(['middleware' => ['auth']], function () {
    Route::resource('books', BookController::class);
    Route::post('books/{book}/borrow', [BookController::class, 'borrow'])
        ->name('books.borrow')
        ->middleware('can:borrow books');
});
```

### Step 9: Add Permissions to Seeder
```php
// database/seeders/PermissionSeeder.php
public function run()
{
    // ... existing permissions
    
    // Book permissions
    Permission::create(['name' => 'create books']);
    Permission::create(['name' => 'view books']);
    Permission::create(['name' => 'edit books']);
    Permission::create(['name' => 'delete books']);
    Permission::create(['name' => 'borrow books']);
    
    // Add to admin permissions
    $admin->givePermissionTo([
        // ... existing permissions
        'create books',
        'view books',
        'edit books',
        'delete books',
    ]);
    
    // Add to teacher permissions (in UserRepository)
    // Teachers can view and manage books
    $teacher->givePermissionTo(['view books', 'create books']);
    
    // Students can only view and borrow (in User model boot method)
    if ($user->role === 'student') {
        $user->givePermissionTo(['view books', 'borrow books']);
    }
}
```

---

## Understanding Seeders in Practice

### How Seeders Work in This System:

#### 1. PermissionSeeder - Creates the Foundation
```php
// This runs FIRST and creates:
// 1. All permissions in the system
// 2. One admin user with ALL permissions

public function run()
{
    // Step 1: Clear permission cache
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    
    // Step 2: Create all permissions
    Permission::create(['name' => 'create users']);
    Permission::create(['name' => 'view users']);
    // ... many more permissions
    
    // Step 3: Create admin user
    $admin = User::create([
        'email' => 'admin@gmail.com',
        'first_name' => 'Admin',
        'last_name' => 'User',
        'password' => Hash::make('admin'),
        'role' => 'admin'
    ]);
    
    // Step 4: Give admin ALL permissions
    $admin->givePermissionTo([
        'create users',
        'view users',
        'edit users',
        // ... ALL permissions
    ]);
}
```

#### 2. UserSeeder - Creates Sample Data
```php
// This creates test users for development
public function run(): void
{
    // Create more test users
    for ($i = 1; $i <= 5; $i++) {
        User::create([
            'first_name' => 'Teacher'.$i,
            'email' => 'teacher'.$i.'@example.com',
            'role' => 'teacher',
            'password' => Hash::make('password'),
        ]);
    }
    
    for ($i = 1; $i <= 10; $i++) {
        User::create([
            'first_name' => 'Student'.$i,
            'email' => 'student'.$i.'@example.com', 
            'role' => 'student',
            'password' => Hash::make('password'),
        ]);
    }
}
```

#### 3. Why This Approach Works:
1. **PermissionSeeder** ensures the admin can do everything immediately
2. **User::boot()** method automatically assigns permissions when new users are created
3. **UserRepository** assigns specific permissions when creating teachers
4. **System is always in a consistent state**

### Running Seeders:
```bash
# Fresh start (drops all tables, recreates, and seeds)
php artisan migrate:fresh --seed

# Just run seeders on existing database
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=PermissionSeeder
```

---

## Interface Implementation Walkthrough

### Why Interfaces Matter:

#### Problem Without Interfaces:
```php
// BAD: Controller directly uses repository
class UserController extends Controller
{
    public function store(Request $request)
    {
        // Tightly coupled to UserRepository
        $userRepo = new UserRepository();
        $userRepo->create($request->all());
    }
}
```

#### Solution With Interfaces:
```php
// GOOD: Controller depends on interface
class UserController extends Controller
{
    protected $userRepository;
    
    // Laravel automatically injects the bound repository
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function store(Request $request)
    {
        // Works with any implementation of UserInterface
        $this->userRepository->create($request->all());
    }
}
```

### Benefits:
1. **Testing**: Can easily mock `UserInterface` for unit tests
2. **Flexibility**: Can swap implementations without changing controller
3. **Maintainability**: Clear contract between layers
4. **Dependency Injection**: Laravel handles object creation

### How Laravel Resolves Interfaces:

1. **Service Provider Binding**:
```php
// AppServiceProvider::register()
$this->app->bind(UserInterface::class, UserRepository::class);
```

2. **Constructor Injection**:
```php
// When Laravel creates UserController, it sees UserInterface dependency
public function __construct(UserInterface $userRepository)
```

3. **Automatic Resolution**:
```php
// Laravel looks in container, finds binding, creates UserRepository instance
// Injects it into controller constructor
```

---

## Permission System Examples

### How Permissions Flow Through the System:

#### 1. Route-Level Protection:
```php
Route::group(['middleware' => ['auth', 'can:create notes']], function () {
    Route::post('/notes', [NoteController::class, 'store']);
});
```

#### 2. Controller-Level Protection:
```php
class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:view notes'])->only(['index', 'show']);
        $this->middleware(['can:create notes'])->only(['create', 'store']);
        $this->middleware(['can:edit notes'])->only(['edit', 'update']);
    }
}
```

#### 3. Method-Level Protection:
```php
public function store(Request $request)
{
    // Double-check permission
    if (!auth()->user()->can('create notes')) {
        abort(403);
    }
    
    return $this->noteRepository->create($request->validated());
}
```

#### 4. View-Level Protection:
```blade
{{-- resources/views/notes/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notes</h1>
    
    @can('create notes')
        <a href="{{ route('notes.create') }}" class="btn btn-primary">
            Create New Note
        </a>
    @endcan
    
    <div class="row">
        @foreach($notes as $note)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>{{ $note->title }}</h5>
                        <p>{{ Str::limit($note->content, 100) }}</p>
                        
                        @can('view notes')
                            <a href="{{ route('notes.show', $note) }}" class="btn btn-info">View</a>
                        @endcan
                        
                        @can('edit notes')
                            @if($note->teacher_id === auth()->id() || auth()->user()->isAdmin())
                                <a href="{{ route('notes.edit', $note) }}" class="btn btn-warning">Edit</a>
                            @endif
                        @endcan
                        
                        @can('delete notes')
                            @if($note->teacher_id === auth()->id() || auth()->user()->isAdmin())
                                <form action="{{ route('notes.destroy', $note) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
```

### Advanced Permission Scenarios:

#### 1. Students Can Only View Notes for Their Class:
```php
class NoteController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isStudent()) {
            // Students only see notes for their class
            $classId = $user->academic_info->class_id;
            $notes = $this->noteRepository->getByClass($classId);
        } elseif ($user->isTeacher()) {
            // Teachers see notes they created
            $notes = $this->noteRepository->getByTeacher($user->id);
        } else {
            // Admins see all notes
            $notes = $this->noteRepository->getAll();
        }
        
        return view('notes.index', compact('notes'));
    }
}
```

#### 2. Teachers Can Only Edit Their Own Notes:
```php
public function update(Request $request, Note $note)
{
    // Check if teacher owns this note
    if (auth()->user()->isTeacher() && $note->teacher_id !== auth()->id()) {
        abort(403, 'You can only edit your own notes');
    }
    
    return $this->noteRepository->update($note->id, $request->validated());
}
```

---

## Common Debugging Tips

### 1. Permission Issues:

#### Problem: User can't access a page
```php
// Debug in controller
public function index()
{
    $user = auth()->user();
    
    // Check user details
    dd([
        'user_id' => $user->id,
        'role' => $user->role,
        'permissions' => $user->getAllPermissions()->pluck('name'),
        'can_view_notes' => $user->can('view notes'),
    ]);
}
```

#### Problem: Permissions not working after seeding
```bash
# Clear permission cache
php artisan cache:clear

# Or in code
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
```

### 2. Interface Binding Issues:

#### Problem: Interface not bound
```
Target [App\Interfaces\UserInterface] is not instantiable.
```

**Solution**: Check `AppServiceProvider::register()` method:
```php
$this->app->bind(\App\Interfaces\UserInterface::class, \App\Repositories\UserRepository::class);
```

### 3. Repository Issues:

#### Problem: Method not found in repository
```
Call to undefined method App\Repositories\UserRepository::someMethod()
```

**Solution**: 
1. Add method to interface first
2. Then implement in repository

```php
// Add to UserInterface.php
public function someMethod($param);

// Then implement in UserRepository.php  
public function someMethod($param)
{
    // Implementation
}
```

### 4. Database Issues:

#### Problem: Foreign key constraints
```bash
# Check migration order
php artisan migrate:status

# Reset if needed
php artisan migrate:fresh --seed
```

### 5. Useful Debugging Commands:

```bash
# Check routes and middleware
php artisan route:list

# Check permissions in database
php artisan tinker
>>> \Spatie\Permission\Models\Permission::all();
>>> \App\Models\User::find(1)->permissions;

# Clear all cache
php artisan optimize:clear

# Check logs
tail -f storage/logs/laravel.log
```

This practical guide should help you understand how to implement features, debug issues, and work with the interface/permission system effectively!
