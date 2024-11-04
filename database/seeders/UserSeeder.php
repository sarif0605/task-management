<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'position' => 'admin',
            'email' => 'admin@test.com',
            'email_verified_at' => Carbon::now(),
            'status_account' => 'active',
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole('admin');
    }
}
