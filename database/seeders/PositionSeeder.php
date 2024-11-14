<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = ['HRD', 'Finance', 'Sales', 'Marketing', 'Operational', 'Pengawas', 'Admin'];
        foreach ($positions as $position) {
            Position::create(['name' => $position]);
        }
    }
}
