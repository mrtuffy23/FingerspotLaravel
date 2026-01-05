<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classification;
use App\Models\FixedAllowance;
use App\Models\VariableAllowance;

class AllowanceSeeder extends Seeder
{
    public function run()
    {
        // Struktur tunjangan per golongan (dari tertinggi ke terendah)
        $allowanceStructure = [
            'I-A' => [
                'fixed' => [
                    'TJ' => 500000,    // Tunjangan Jabatan
                    'TMK' => 300000,   // Tunjangan Masa Kerja
                ],
                'variable' => [
                    'PP' => 50000,     // Premi Prestasi/hari
                    'PK' => 30000,     // Premi Kehadiran/hari
                    'UM' => 25000,     // Uang Makan/hari
                ]
            ],
            'I-B' => [
                'fixed' => [
                    'TJ' => 400000,
                    'TMK' => 250000,
                ],
                'variable' => [
                    'PP' => 45000,
                    'PK' => 27000,
                    'UM' => 25000,
                ]
            ],
            'II-A1' => [
                'fixed' => [
                    'TJ' => 300000,
                    'TMK' => 200000,
                ],
                'variable' => [
                    'PP' => 40000,
                    'PK' => 24000,
                    'UM' => 25000,
                ]
            ],
            'II-A' => [
                'fixed' => [
                    'TJ' => 250000,
                    'TMK' => 150000,
                ],
                'variable' => [
                    'PP' => 35000,
                    'PK' => 21000,
                    'UM' => 25000,
                ]
            ],
            'II-B' => [
                'fixed' => [
                    'TJ' => 200000,
                    'TMK' => 100000,
                ],
                'variable' => [
                    'PP' => 30000,
                    'PK' => 18000,
                    'UM' => 25000,
                ]
            ],
        ];

        foreach ($allowanceStructure as $code => $allowances) {
            $classification = Classification::where('code', $code)->first();
            
            if (!$classification) {
                continue;
            }

            // Create Fixed Allowances
            $fixedNames = [
                'TJ' => 'Tunjangan Jabatan',
                'TMK' => 'Tunjangan Masa Kerja',
            ];

            foreach ($allowances['fixed'] as $code_fixed => $amount) {
                FixedAllowance::updateOrCreate(
                    [
                        'classification_id' => $classification->id,
                        'code' => $code_fixed,
                    ],
                    [
                        'name' => $fixedNames[$code_fixed],
                        'amount' => $amount,
                    ]
                );
            }

            // Create Variable Allowances
            $variableNames = [
                'PP' => 'Premi Prestasi/hari',
                'PK' => 'Premi Kehadiran/hari',
                'UM' => 'Uang Makan/hari',
            ];

            foreach ($allowances['variable'] as $code_var => $amount_per_day) {
                VariableAllowance::updateOrCreate(
                    [
                        'classification_id' => $classification->id,
                        'code' => $code_var,
                    ],
                    [
                        'name' => $variableNames[$code_var],
                        'amount_per_day' => $amount_per_day,
                    ]
                );
            }
        }

        echo "✅ Allowances configured for all classifications\n";
    }
}
