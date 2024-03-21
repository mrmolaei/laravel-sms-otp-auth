<?php

namespace Database\Seeders;


use App\Enums\Roles;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name'        => Roles::MEMBER,
            'description' => 'Member role',
        ]);

        Role::create([
            'name'        => Roles::ADMIN,
            'description' => 'Admin role',
        ]);

        Role::create([
            'name'        => Roles::SUPER_ADMIN,
            'description' => 'Super Admin role',
        ]);
    }
}
