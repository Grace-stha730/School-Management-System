<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Creating Notes Permissions ===\n";

// Create notes permissions
$notesPermissions = ['create notes', 'view notes'];
foreach ($notesPermissions as $permissionName) {
    try {
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permissionName]);
        echo "✓ Permission '$permissionName' created/found\n";
    } catch (Exception $e) {
        echo "✗ Error creating permission '$permissionName': " . $e->getMessage() . "\n";
    }
}

// Create teacher role and assign permissions
$teacherRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'teacher']);
echo "✓ Teacher role created/found\n";

foreach ($notesPermissions as $permissionName) {
    $permission = \Spatie\Permission\Models\Permission::where('name', $permissionName)->first();
    if ($permission) {
        if (!$teacherRole->hasPermissionTo($permissionName)) {
            $teacherRole->givePermissionTo($permissionName);
            echo "✓ Assigned '$permissionName' to teacher role\n";
        } else {
            echo "✓ Teacher role already has '$permissionName'\n";
        }
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

echo "\n=== Testing Teacher Permissions ===\n";
$teacher = \App\Models\User::where('role', 'teacher')->first();
if ($teacher) {
    echo "Testing user: " . $teacher->name . " (" . $teacher->email . ")\n";
    echo "Can create notes: " . ($teacher->can('create notes') ? 'YES' : 'NO') . "\n";
    echo "Can view notes: " . ($teacher->can('view notes') ? 'YES' : 'NO') . "\n";
} else {
    echo "No teacher users found\n";
}

echo "\n=== Done ===\n";
