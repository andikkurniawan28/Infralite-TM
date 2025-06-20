<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use App\Models\TicketStatus;
use App\Models\TicketCategory;
use Illuminate\Database\Seeder;
use App\Models\TicketPriorities;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::insert([
            ['name' => 'Admin'],
            ['name' => 'Technician'],
            ['name' => 'User'],
        ]);

        User::insert([
            [
                'role_id' => 1,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin888'),
                'is_active' => 1,
            ],
            [
                'role_id' => 2,
                'name' => 'Technician',
                'email' => 'technician@gmail.com',
                'password' => bcrypt('technician888'),
                'is_active' => 1,
            ],
            [
                'role_id' => 3,
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => bcrypt('user888'),
                'is_active' => 1,
            ]
        ]);

        TicketCategory::insert([
            ['name' => 'Software'],
            ['name' => 'Hardware'],
            ['name' => 'Credential'],
            ['name' => 'Network'],
        ]);

        TicketStatus::insert([
            ['name' => 'Open'],
            ['name' => 'In Progress'],
            ['name' => 'Resolved'],
            ['name' => 'Closed'],
        ]);

        TicketPriorities::insert([
            ['name' => 'Low'],
            ['name' => 'Medium'],
            ['name' => 'High'],
            ['name' => 'Urgent'],
        ]);
    }
}
