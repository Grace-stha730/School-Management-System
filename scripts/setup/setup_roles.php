<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Roles and Users Check ===\n";

// Check what roles exist
$roles = \Spatie\Permission\Models\Role::all();
echo "Existing roles:\n";
foreach ($roles as $role) {
    echo "- " . $role->name . "\n";
}

// Check users and their roles
$users = \App\Models\User::all();
echo "\nUsers and their roles:\n";
foreach ($users as $user) {
    echo "- " . $user->name . " (" . $user->email . ") - Role: " . $user->role . "\n";
}

echo "\n=== Creating Teacher Role and Assigning Permissions ===\n";

// Create teacher role if it doesn't exist
$teacherRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'teacher']);
echo "✓ Teacher role created/found\n";

// Check and assign notes permissions
$notesPermissions = ['create notes', 'view notes'];
foreach ($notesPermissions as $permissionName) {
    $permission = \Spatie\Permission\Models\Permission::where('name', $permissionName)->first();
    if ($permission) {
        if (!$teacherRole->hasPermissionTo($permissionName)) {
            $teacherRole->givePermissionTo($permissionName);
            echo "✓ Assigned '$permissionName' to teacher role\n";
        } else {
            echo "✓ Teacher role already has '$permissionName'\n";
        }
    } else {
        echo "✗ Permission '$permissionName' not found\n";
    }
}

// Assign teacher role to users with role='teacher'
$teacherUsers = \App\Models\User::where('role', 'teacher')->get();
foreach ($teacherUsers as $user) {
    if (!$user->hasRole('teacher')) {
        $user->assignRole('teacher');
        echo "✓ Assigned teacher role to user: " . $user->name . "\n";
    } else {
        echo "✓ User already has teacher role: " . $user->name . "\n";
    }
}

echo "\n=== End Setup ===\n";
