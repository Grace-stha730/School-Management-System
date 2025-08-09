<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'gender' => 'male',
            'nationality' => 'Nepali',
            'phone' => '9800000000',
            'address' => 'Kathmandu',
            'address2' => null,
            'city' => 'Kathmandu',
            'zip' => '44600',
            'photo' => null,
            'birthday' => '1990-01-01',
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add regular users
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'first_name' => 'User'.$i,
                'last_name' => 'Test',
                'email' => 'user'.$i.'@example.com',
                'gender' => $i % 2 == 0 ? 'male' : 'female',
                'nationality' => 'Nepali',
                'phone' => '980000000'.$i,
                'address' => 'Address '.$i,
                'address2' => $i % 3 == 0 ? 'Secondary Address '.$i : null,
                'city' => 'City '.$i,
                'zip' => '4460'.$i,
                'photo' => $i % 4 == 0 ? 'user'.$i.'.jpg' : null,
                'birthday' => date('Y-m-d', strtotime('-'.(20 + $i).' years')),
                'role' => 'user',
                'email_verified_at' => now(),
                'password' => Hash::make('password'.$i),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    } 
}