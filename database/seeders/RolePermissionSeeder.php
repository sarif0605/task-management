<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void{
        $roles = ['kontraktor', 'interior', 'advertising', 'catering', 'travel', 'admin'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
