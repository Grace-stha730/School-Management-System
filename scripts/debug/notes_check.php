<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Notes Permissions Check ===\n";

// Check teacher role permissions
$teacherRole = \Spatie\Permission\Models\Role::where('name', 'teacher')->first();
if ($teacherRole) {
    echo "✓ Teacher role exists\n";
    $permissions = $teacherRole->permissions->pluck('name')->toArray();
    
    if (in_array('create notes', $permissions)) {
        echo "✓ Teacher role has 'create notes' permission\n";
    } else {
        echo "✗ Teacher role missing 'create notes' permission\n";
        // Assign the permission
        $permission = \Spatie\Permission\Models\Permission::where('name', 'create notes')->first();
        if ($permission) {
            $teacherRole->givePermissionTo($permission);
            echo "✓ Assigned 'create notes' permission to teacher role\n";
        }
    }
    
    if (in_array('view notes', $permissions)) {
        echo "✓ Teacher role has 'view notes' permission\n";
    } else {
        echo "✗ Teacher role missing 'view notes' permission\n";
        // Assign the permission
        $permission = \Spatie\Permission\Models\Permission::where('name', 'view notes')->first();
        if ($permission) {
            $teacherRole->givePermissionTo($permission);
            echo "✓ Assigned 'view notes' permission to teacher role\n";
        }
    }
} else {
    echo "✗ Teacher role not found\n";
}

echo "\n=== End Check ===\n";
