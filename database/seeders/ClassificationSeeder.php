<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classification;
use App\Models\FixedAllowance;
use App\Models\VariableAllowance;

class ClassificationSeeder extends Seeder
{
    public function run()
    {
        $classifications = [
            ['code' => 'II-B', 'name' => 'Golongan II B', 'level' => 1],
            ['code' => 'II-A', 'name' => 'Golongan II A', 'level' => 2],
            ['code' => 'II-A1', 'name' => 'Golongan II A1', 'level' => 3],
            ['code' => 'I-B', 'name' => 'Golongan I B', 'level' => 4],
            ['code' => 'I-A', 'name' => 'Golongan I A', 'level' => 5],
        ];

        foreach ($classifications as $data) {
            Classification::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
