# School Management System - Visual Flow Diagrams

## 1. System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                        FRONTEND (Blade Views)                   │
├─────────────────────────────────────────────────────────────────┤
│                         ROUTES (web.php)                        │
├─────────────────────────────────────────────────────────────────┤
│                    MIDDLEWARE LAYER                             │
│  ┌─────────┐  ┌─────────────┐  ┌─────────────────┐            │
│  │  Auth   │  │ Permission  │  │   Role-Based    │            │
│  │ Check   │  │    Check    │  │   Middleware    │            │
│  └─────────┘  └─────────────┘  └─────────────────┘            │
├─────────────────────────────────────────────────────────────────┤
│                       CONTROLLERS                               │
├─────────────────────────────────────────────────────────────────┤
│                      INTERFACES                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │
│  │ UserInterface│  │ NoteInterface│  │ MarkInterface│        │
│  └──────────────┘  └──────────────┘  └──────────────┘        │
├─────────────────────────────────────────────────────────────────┤
│                     REPOSITORIES                                │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │
│  │UserRepository│  │NoteRepository│  │MarkRepository│        │
│  └──────────────┘  └──────────────┘  └──────────────┘        │
├─────────────────────────────────────────────────────────────────┤
│                       MODELS                                    │
│  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐          │
│  │  User   │  │  Note   │  │  Mark   │  │  Exam   │          │
│  └─────────┘  └─────────┘  └─────────┘  └─────────┘          │
├─────────────────────────────────────────────────────────────────┤
│                       DATABASE                                  │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  MySQL Database with Tables for Users, Permissions,    │   │
│  │  Roles, Notes, Marks, Classes, Sessions, etc.         │   │
│  └─────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

## 2. Authentication Flow

```
┌─────────────┐    ┌──────────────┐    ┌─────────────┐    ┌──────────────┐
│ User Login  │───▶│ Validation   │───▶│ Auth Check  │───▶│ Dashboard    │
│ Form        │    │ (Email/Pass) │    │ & Session   │    │ Redirect     │
└─────────────┘    └──────────────┘    └─────────────┘    └──────────────┘
                            │                   │
                            ▼                   ▼
                   ┌──────────────┐    ┌─────────────┐
                   │ Error        │    │ Permission  │
                   │ Response     │    │ Assignment  │
                   └──────────────┘    └─────────────┘
```

## 3. Permission System Flow

```
User Registration/Creation
         │
         ▼
┌─────────────────┐
│ User::boot()    │ ◄─── Automatic Permission Assignment
│ Method Called   │
└─────────────────┘
         │
         ▼
┌─────────────────┐
│ Check Role      │
│ - Admin         │
│ - Teacher       │ 
│ - Student       │
└─────────────────┘
         │
         ▼
┌─────────────────┐
│ Assign          │
│ Role-Specific   │
│ Permissions     │
└─────────────────┘

Admin Permissions:
├── User Management
├── Academic Settings  
├── Fee Management
├── All Teacher Permissions
└── All Student Permissions

Teacher Permissions:
├── Create/View Exams
├── Create/View Notes
├── Create/View Assignments
├── Save/View Marks
└── View Students

Student Permissions:
├── View Notes
├── View Assignments
├── View Syllabi
└── View Marks
```

## 4. Repository Pattern Flow

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Controller    │───▶│   Interface     │───▶│   Repository    │
│                 │    │   (Contract)    │    │ (Implementation)│
│ UserController  │    │ UserInterface   │    │ UserRepository  │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         ▲                       ▲                       │
         │                       │                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Dependency      │    │ Service         │    │     Model       │
│ Injection       │    │ Provider        │    │                 │
│ (Auto-resolve)  │    │ Binding         │    │   User Model    │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                                       │
                                                       ▼
                                            ┌─────────────────┐
                                            │    Database     │
                                            └─────────────────┘
```

## 5. Data Flow for Creating a Student

```
┌─────────────┐
│ Admin       │
│ Creates     │
│ Student     │
└─────┬───────┘
      │
      ▼
┌─────────────┐    POST Request
│ User        │ ──────────────┐
│ Controller  │               │
└─────┬───────┘               │
      │                       ▼
      │ Inject           ┌─────────────┐
      ▼ Interface        │ Route with  │
┌─────────────┐          │ Middleware  │
│ User        │          │ 'can:create │
│ Repository  │          │ users'      │
└─────┬───────┘          └─────────────┘
      │
      ▼ DB Transaction
┌─────────────┐
│ Create User │
│ Model       │
└─────┬───────┘
      │
      ▼ Automatic (User::boot)
┌─────────────┐
│ Assign      │
│ Student     │
│ Permissions │
└─────┬───────┘
      │
      ▼
┌─────────────┐
│ Create      │
│ Academic    │
│ Info        │
└─────┬───────┘
      │
      ▼
┌─────────────┐
│ Success     │
│ Response    │
└─────────────┘
```

## 6. Permission Checking Flow

```
User Action Request
         │
         ▼
┌─────────────────┐
│ Route           │
│ Middleware      │
│ Check           │
└─────┬───────────┘
      │
      ▼
┌─────────────────┐     NO    ┌─────────────────┐
│ Has Permission? │──────────▶│ 403 Forbidden   │
└─────┬───────────┘           └─────────────────┘
      │ YES
      ▼
┌─────────────────┐
│ Execute         │
│ Controller      │
│ Action          │
└─────┬───────────┘
      │
      ▼
┌─────────────────┐
│ Return          │
│ Response/View   │
└─────────────────┘
```

## 7. Database Seeding Flow

```
php artisan db:seed
         │
         ▼
┌─────────────────┐
│ DatabaseSeeder  │
│ ::run()         │
└─────┬───────────┘
      │
      ▼
┌─────────────────┐
│ PermissionSeeder│
│ ::run()         │
└─────┬───────────┘
      │
      ▼ Creates all permissions
┌─────────────────┐
│ Permission      │
│ ::create()      │
│ - create users  │
│ - view users    │
│ - create notes  │
│ - etc...        │
└─────┬───────────┘
      │
      ▼ Creates admin user
┌─────────────────┐
│ User::create()  │
│ - email: admin  │
│ - role: admin   │
└─────┬───────────┘
      │
      ▼ Assigns all permissions
┌─────────────────┐
│ $user->         │
│ givePermissionTo│
│ (all perms)     │
└─────┬───────────┘
      │
      ▼
┌─────────────────┐
│ UserSeeder      │
│ ::run()         │
│ (creates test   │
│ users)          │
└─────────────────┘
```

## 8. Role-Based Access Control Matrix

```
╔══════════════╦═══════╦═════════╦═════════╗
║ Permission   ║ Admin ║ Teacher ║ Student ║
╠══════════════╬═══════╬═════════╬═════════╣
║ create users ║   ✓   ║    ✗    ║    ✗    ║
║ view users   ║   ✓   ║    ✓    ║    ✗    ║
║ create notes ║   ✓   ║    ✓    ║    ✗    ║
║ view notes   ║   ✓   ║    ✓    ║    ✓    ║
║ save marks   ║   ✓   ║    ✓    ║    ✗    ║
║ view marks   ║   ✓   ║    ✓    ║    ✓    ║
║ create exams ║   ✓   ║    ✓    ║    ✗    ║
║ view syllabi ║   ✓   ║    ✓    ║    ✓    ║
║ promote      ║   ✓   ║    ✗    ║    ✗    ║
║ students     ║       ║         ║         ║
╚══════════════╩═══════╩═════════╩═════════╝
```

## 9. Interface Binding Flow

```
Application Start
         │
         ▼
┌─────────────────┐
│ AppServiceProvider│
│ ::register()    │
└─────┬───────────┘
      │
      ▼ Bind interfaces to repositories
┌─────────────────┐
│ $app->bind(     │
│ UserInterface,  │
│ UserRepository) │
└─────┬───────────┘
      │
      ▼ When controller needs interface
┌─────────────────┐
│ Laravel         │
│ Container       │
│ Resolves        │
└─────┬───────────┘
      │
      ▼ Automatically injects
┌─────────────────┐
│ Controller      │
│ Constructor     │
│ Gets Repository │
│ Instance        │
└─────────────────┘
```

## 10. Complete Request Lifecycle Example

```
User clicks "Create Note" button
         │
         ▼
┌─────────────────┐
│ POST /notes     │
│ Route           │
└─────┬───────────┘
      │
      ▼ Route middleware
┌─────────────────┐    FAIL   ┌─────────────────┐
│ auth middleware │──────────▶│ Redirect to     │
│ check           │           │ login           │
└─────┬───────────┘           └─────────────────┘
      │ PASS
      ▼ Permission middleware  
┌─────────────────┐    FAIL   ┌─────────────────┐
│ can:create      │──────────▶│ 403 Forbidden   │
│ notes           │           │ Response        │
└─────┬───────────┘           └─────────────────┘
      │ PASS
      ▼
┌─────────────────┐
│ NoteController  │
│ ::store()       │
└─────┬───────────┘
      │ Inject Interface
      ▼
┌─────────────────┐
│ NoteRepository  │
│ ::create()      │
└─────┬───────────┘
      │
      ▼ Database Transaction
┌─────────────────┐
│ Note::create()  │
│ Save to DB      │
└─────┬───────────┘
      │
      ▼
┌─────────────────┐
│ Success         │
│ Response        │
│ Back to View    │
└─────────────────┘
```

This visual guide should help you understand how all the components work together in the School Management System!
