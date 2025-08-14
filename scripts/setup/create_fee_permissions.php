<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Fee permissions to create
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

echo "Creating fee permissions...\n";

foreach ($feePermissions as $permissionName) {
    try {
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate([
            'name' => $permissionName,
            'guard_name' => 'web'
        ]);
        echo "Created/found permission: $permissionName\n";
    } catch (Exception $e) {
        echo "Error creating permission $permissionName: " . $e->getMessage() . "\n";
    }
}

// Assign permissions to admin
$admin = \App\Models\User::where('email', 'admin@gmail.com')->first();

if ($admin) {
    echo "\nAssigning permissions to admin...\n";
    foreach ($feePermissions as $permissionName) {
        try {
            if (!$admin->hasPermissionTo($permissionName)) {
                $admin->givePermissionTo($permissionName);
                echo "Assigned permission: $permissionName\n";
            } else {
                echo "Admin already has: $permissionName\n";
            }
        } catch (Exception $e) {
            echo "Error assigning permission $permissionName: " . $e->getMessage() . "\n";
        }
    }
    echo "\nPermissions assigned successfully!\n";
} else {
    echo "Admin user not found!\n";
}
