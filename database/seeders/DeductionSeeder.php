<?php

namespace Database\Seeders;

use App\Models\FixedDeduction;
use App\Models\VariableDeduction;
use App\Models\Classification;
use Illuminate\Database\Seeder;

class DeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all classifications
        $classifications = Classification::all();

        if ($classifications->isEmpty()) {
            echo "Tidak ada classification ditemukan. Silakan buat classification terlebih dahulu.\n";
            return;
        }

        // Add fixed deductions untuk semua classifications
        foreach ($classifications as $classification) {
            // BPJS Kesehatan - 4% dari gaji dasar (aprox)
            FixedDeduction::firstOrCreate(
                [
                    'classification_id' => $classification->id,
                    'code' => 'BPJS_KES'
                ],
                [
                    'name' => 'BPJS Kesehatan',
                    'amount' => 100000 // Sesuaikan dengan kebutuhan
                ]
            );

            // BPJS Ketenagakerjaan
            FixedDeduction::firstOrCreate(
                [
                    'classification_id' => $classification->id,
                    'code' => 'BPJS_TK'
                ],
                [
                    'name' => 'BPJS Ketenagakerjaan',
                    'amount' => 50000
                ]
            );

            // Asuransi Tambahan (Optional)
            FixedDeduction::firstOrCreate(
                [
                    'classification_id' => $classification->id,
                    'code' => 'ASURANSI'
                ],
                [
                    'name' => 'Asuransi Jiwa',
                    'amount' => 25000
                ]
            );

            // Variable Deduction untuk keterlambatan (contoh)
            VariableDeduction::firstOrCreate(
                [
                    'classification_id' => $classification->id,
                    'code' => 'POT_TERLAMBAT'
                ],
                [
                    'name' => 'Potongan Keterlambatan',
                    'amount_per_day' => 0 // Set ke 0, update sesuai absensi aktual
                ]
            );

            // Variable Deduction untuk pinjaman (contoh)
            VariableDeduction::firstOrCreate(
                [
                    'classification_id' => $classification->id,
                    'code' => 'POT_PINJAMAN'
                ],
                [
                    'name' => 'Potongan Pinjaman',
                    'amount_per_day' => 0
                ]
            );
        }

        echo "Fixed Deductions dan Variable Deductions berhasil dibuat untuk semua classifications.\n";
    }
}
