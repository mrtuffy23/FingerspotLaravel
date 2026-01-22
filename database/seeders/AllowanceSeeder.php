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
                    'TJ' => 0,    // Tunjangan Jabatan
                    'TMK' => 0,   // Tunjangan Masa Kerja
                ],
                'variable' => [
                    'PP' => 0,     // Premi Prestasi/hari
                    'PK' => 0,     // Premi Kehadiran/hari
                    'UM' => 10000,     // Uang Makan/hari
                ]
            ],
            'I-B' => [
                'fixed' => [
                    'TJ' => 0,
                    'TMK' => 0,
                ],
                'variable' => [
                    'PP' => 0,
                    'PK' => 0,
                    'UM' => 10000,
                ]
            ],
            'II-A1' => [
                'fixed' => [
                    'TJ' => 0,
                    'TMK' => 25000,
                ],
                'variable' => [
                    'PP' => 1000,
                    'PK' => 2000,
                    'UM' => 10000,
                ]
            ],
            'II-A' => [
                'fixed' => [
                    'TJ' => 50000,
                    'TMK' => 25000,
                ],
                'variable' => [
                    'PP' => 1000,
                    'PK' => 2000,
                    'UM' => 10000,
                ]
            ],
            'II-B' => [
                'fixed' => [
                    'TJ' => 150000,
                    'TMK' => 25000,
                ],
                'variable' => [
                    'PP' => 2000,
                    'PK' => 3000,
                    'UM' => 10000,
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
