<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Notes Permissions Check ===\n";

// Check if notes permissions exist
$createNotesPermission = \Spatie\Permission\Models\Permission::where('name', 'create notes')->first();
$viewNotesPermission = \Spatie\Permission\Models\Permission::where('name', 'view notes')->first();

if ($createNotesPermission) {
    echo "✓ 'create notes' permission exists\n";
} else {
    echo "✗ 'create notes' permission missing\n";
}

if ($viewNotesPermission) {
    echo "✓ 'view notes' permission exists\n";
} else {
    echo "✗ 'view notes' permission missing\n";
}

// Check teacher role permissions
$teacherRole = \Spatie\Permission\Models\Role::where('name', 'teacher')->first();
if ($teacherRole) {
    echo "✓ Teacher role exists\n";
    $permissions = $teacherRole->permissions->pluck('name')->toArray();
    
    if (in_array('create notes', $permissions)) {
        echo "✓ Teacher role has 'create notes' permission\n";
    } else {
        echo "✗ Teacher role missing 'create notes' permission\n";
    }
    
    if (in_array('view notes', $permissions)) {
        echo "✓ Teacher role has 'view notes' permission\n";
    } else {
        echo "✗ Teacher role missing 'view notes' permission\n";
    }
} else {
    echo "✗ Teacher role not found\n";
}

// Check sample teacher user
$teacher = \App\Models\User::where('role', 'teacher')->first();
if ($teacher) {
    echo "✓ Sample teacher user found: " . $teacher->name . "\n";
    
    if ($teacher->can('create notes')) {
        echo "✓ Teacher can create notes\n";
    } else {
        echo "✗ Teacher cannot create notes\n";
    }
    
    if ($teacher->can('view notes')) {
        echo "✓ Teacher can view notes\n";
    } else {
        echo "✗ Teacher cannot view notes\n";
    }
} else {
    echo "✗ No teacher users found\n";
}

echo "\n=== End Check ===\n";
    $feePermissions = [
        'create fee heads',
        'view fee heads',
        'edit fee heads',
        'delete fee heads',
        'create fee structures',
        'view fee structures',
        'edit fee structures',
        'delete fee structures',
        'view student fees',
        'manage student fees',
        'update fee payments',
        'add fee discounts'
    ];
    
    echo "\nFee permissions status:\n";
    foreach ($feePermissions as $permission) {
        $hasPermission = $admin->hasPermissionTo($permission);
        echo "- $permission: " . ($hasPermission ? "YES" : "NO") . "\n";
    }
    
    // Try to create missing permissions and assign them
    foreach ($feePermissions as $permissionName) {
        try {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permissionName]);
            if (!$admin->hasPermissionTo($permissionName)) {
                $admin->givePermissionTo($permissionName);
                echo "Assigned permission: $permissionName\n";
            }
        } catch (Exception $e) {
            echo "Error with permission $permissionName: " . $e->getMessage() . "\n";
        }
    }
