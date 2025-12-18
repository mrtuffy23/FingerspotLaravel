<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 4 shifts: 3 untuk daily + 1 untuk monthly standard
        Shift::create([
            'code' => 'SHIFT_1',
            'start_time' => '07:00:00',
            'end_time' => '15:00:00',
            'break_minutes' => 60,
        ]);

        Shift::create([
            'code' => 'SHIFT_2',
            'start_time' => '15:00:00',
            'end_time' => '23:00:00',
            'break_minutes' => 60,
        ]);

        Shift::create([
            'code' => 'SHIFT_3',
            'start_time' => '23:00:00',
            'end_time' => '07:00:00',
            'break_minutes' => 60,
        ]);

        Shift::create([
            'code' => 'STANDARD',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'break_minutes' => 60,
        ]);

        $this->command->info('✅ 4 shifts created successfully!');
    }
}
