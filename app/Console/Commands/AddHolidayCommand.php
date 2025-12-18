<?php

namespace App\Console\Commands;

use App\Models\WorkCalendar;
use Illuminate\Console\Command;

class AddHolidayCommand extends Command
{
    protected $signature = 'holiday:add {date?} {description?} {type?}';
    protected $description = 'Tambah atau kelola hari libur (tanggal merah)';

    public function handle()
    {
        $date = $this->argument('date');
        $description = $this->argument('description');
        $type = $this->argument('type');

        // Jika parameter tidak lengkap, tampilkan menu interaktif
        if (!$date || !$description || !$type) {
            return $this->showMenu();
        }

        // Validasi format tanggal
        if (!strtotime($date)) {
            $this->error('❌ Format tanggal salah! Gunakan format: YYYY-MM-DD (misal: 2025-12-25)');
            return 1;
        }

        // Validasi type
        if (!in_array($type, ['national_holiday', 'collective_leave'])) {
            $this->error('❌ Tipe tidak valid! Gunakan: national_holiday atau collective_leave');
            return 1;
        }

        // Check apakah tanggal sudah ada
        $existing = WorkCalendar::where('date', $date)->first();
        if ($existing) {
            $this->error("❌ Tanggal {$date} sudah terdaftar sebagai: {$existing->description}");
            return 1;
        }

        // Insert
        try {
            WorkCalendar::create([
                'date' => $date,
                'description' => $description,
                'type' => $type,
            ]);

            $typeLabel = $type === 'national_holiday' ? 'Hari Libur Nasional' : 'Cuti Bersama';
            $this->info("✅ BERHASIL! Ditambahkan:");
            $this->line("   📅 Tanggal: {$date}");
            $this->line("   📝 Deskripsi: {$description}");
            $this->line("   🏷️  Tipe: {$typeLabel}");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
    }

    protected function showMenu()
    {
        $this->info("\n╔════════════════════════════════════════════╗");
        $this->info("║   TAMBAH/KELOLA HARI LIBUR (TANGGAL MERAH)  ║");
        $this->info("╚════════════════════════════════════════════╝\n");

        $choice = $this->choice(
            'Pilih aksi:',
            [
                'Tambah 1 hari libur',
                'Tambah banyak hari libur sekaligus',
                'Lihat daftar hari libur',
                'Hapus hari libur',
            ]
        );

        switch ($choice) {
            case 'Tambah 1 hari libur':
                return $this->addSingleHoliday();
            case 'Tambah banyak hari libur sekaligus':
                return $this->addMultipleHolidays();
            case 'Lihat daftar hari libur':
                return $this->showHolidays();
            case 'Hapus hari libur':
                return $this->deleteHoliday();
        }
    }

    protected function addSingleHoliday()
    {
        $this->info("\n📅 TAMBAH 1 HARI LIBUR\n");

        $date = $this->ask('Tanggal (format: YYYY-MM-DD)', '2025-12-25');
        $description = $this->ask('Deskripsi (misal: "Hari Raya Natal")');
        $type = $this->choice('Tipe:', ['national_holiday' => 'Hari Libur Nasional', 'collective_leave' => 'Cuti Bersama'], 0);

        // Mapping choice ke value
        $typeValue = array_keys(['national_holiday' => 'Hari Libur Nasional', 'collective_leave' => 'Cuti Bersama'])[$type];

        if (!strtotime($date)) {
            $this->error('❌ Format tanggal salah!');
            return 1;
        }

        $existing = WorkCalendar::where('date', $date)->first();
        if ($existing) {
            $this->error("❌ Tanggal {$date} sudah ada: {$existing->description}");
            return 1;
        }

        try {
            WorkCalendar::create([
                'date' => $date,
                'description' => $description,
                'type' => $typeValue,
            ]);

            $this->info("\n✅ BERHASIL DITAMBAHKAN!");
            $this->line("   📅 {$date}");
            $this->line("   📝 {$description}");
            $this->line("   🏷️  " . ($typeValue === 'national_holiday' ? 'Hari Libur Nasional' : 'Cuti Bersama'));

            if ($this->confirm('\nTambah hari libur lagi?')) {
                return $this->addSingleHoliday();
            }
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
    }

    protected function addMultipleHolidays()
    {
        $this->info("\n📅 TAMBAH BANYAK HARI LIBUR SEKALIGUS\n");

        $choice = $this->choice(
            'Pilih opsi:',
            [
                'Template Kalender 2026',
                'Input manual satu per satu',
            ]
        );

        if ($choice === 'Template Kalender 2026') {
            return $this->addTemplate2026();
        } else {
            return $this->addMultipleManual();
        }
    }

    protected function addTemplate2026()
    {
        $holidays = [
            ['2026-01-01', 'Tahun Baru Masehi', 'national_holiday'],
            ['2026-01-29', 'Tahun Baru Imlek', 'national_holiday'],
            ['2026-02-10', 'Isra dan Miraj', 'national_holiday'],
            ['2026-03-11', 'Nyepi', 'national_holiday'],
            ['2026-03-29', 'Paskah', 'national_holiday'],
            ['2026-05-01', 'Hari Buruh', 'national_holiday'],
            ['2026-05-14', 'Waisak', 'national_holiday'],
            ['2026-05-23', 'Hari Raya Idul Fitri', 'national_holiday'],
            ['2026-05-24', 'Cuti Bersama Idul Fitri', 'collective_leave'],
            ['2026-05-25', 'Cuti Bersama Idul Fitri', 'collective_leave'],
            ['2026-06-01', 'Hari Raya Idul Adha', 'national_holiday'],
            ['2026-06-21', 'Tahun Baru Hijriah', 'national_holiday'],
            ['2026-08-17', 'Hari Kemerdekaan Indonesia', 'national_holiday'],
            ['2026-08-31', 'Hari Mawlid Nabi Muhammad', 'national_holiday'],
            ['2026-12-25', 'Hari Raya Natal', 'national_holiday'],
            ['2026-12-26', 'Cuti Bersama Natal', 'collective_leave'],
        ];

        $this->info("\n📋 Template Kalender Nasional 2026\n");
        $this->info("Akan ditambahkan " . count($holidays) . " hari libur:");

        foreach ($holidays as $holiday) {
            $this->line("  • {$holiday[0]} - {$holiday[1]}");
        }

        if (!$this->confirm('\nLanjutkan menambahkan?')) {
            $this->info('Dibatalkan.');
            return 0;
        }

        $added = 0;
        $skipped = 0;

        foreach ($holidays as $holiday) {
            $existing = WorkCalendar::where('date', $holiday[0])->first();
            if ($existing) {
                $skipped++;
                continue;
            }

            try {
                WorkCalendar::create([
                    'date' => $holiday[0],
                    'description' => $holiday[1],
                    'type' => $holiday[2],
                ]);
                $added++;
            } catch (\Exception $e) {
                // Skip
            }
        }

        $this->info("\n✅ SELESAI!");
        $this->line("   ✓ Ditambahkan: {$added} hari libur");
        if ($skipped > 0) {
            $this->line("   ⊘ Terlewat (sudah ada): {$skipped}");
        }

        return 0;
    }

    protected function addMultipleManual()
    {
        $this->info("\n📅 INPUT MANUAL BANYAK HARI LIBUR\n");
        $this->info("Gunakan format: YYYY-MM-DD | Deskripsi | type");
        $this->info("Type: 'nh' untuk national_holiday, 'cl' untuk collective_leave");
        $this->info("Contoh: 2025-12-25 | Hari Raya Natal | nh\n");

        $input = $this->ask('Masukkan data (pisahkan dengan Enter, ketik "selesai" untuk selesai)');

        $holidays = [];
        while ($input !== 'selesai') {
            $parts = explode('|', $input);
            if (count($parts) === 3) {
                $date = trim($parts[0]);
                $description = trim($parts[1]);
                $type = trim($parts[2]) === 'nh' ? 'national_holiday' : 'collective_leave';

                $holidays[] = [$date, $description, $type];
            }
            $input = $this->ask('Data berikutnya (atau "selesai")');
        }

        if (empty($holidays)) {
            $this->info('Tidak ada data.');
            return 0;
        }

        $added = 0;
        foreach ($holidays as $holiday) {
            $existing = WorkCalendar::where('date', $holiday[0])->first();
            if ($existing) continue;

            try {
                WorkCalendar::create([
                    'date' => $holiday[0],
                    'description' => $holiday[1],
                    'type' => $holiday[2],
                ]);
                $added++;
            } catch (\Exception $e) {
                // Skip
            }
        }

        $this->info("\n✅ Ditambahkan: {$added} hari libur");
        return 0;
    }

    protected function showHolidays()
    {
        $holidays = WorkCalendar::where('type', '!=', 'workday')
            ->orderBy('date')
            ->get();

        if ($holidays->isEmpty()) {
            $this->info('Belum ada hari libur terdaftar.');
            return 0;
        }

        $this->info("\n📅 DAFTAR HARI LIBUR\n");
        
        $headers = ['Tanggal', 'Deskripsi', 'Tipe'];
        $rows = [];

        foreach ($holidays as $holiday) {
            $type = $holiday->type === 'national_holiday' ? 'Nasional' : 'Cuti Bersama';
            $rows[] = [$holiday->date, $holiday->description, $type];
        }

        $this->table($headers, $rows);
        $this->info("\nTotal: " . count($holidays) . " hari libur");

        return 0;
    }

    protected function deleteHoliday()
    {
        $this->info("\n🗑️  HAPUS HARI LIBUR\n");

        $date = $this->ask('Masukkan tanggal (format: YYYY-MM-DD)');

        $holiday = WorkCalendar::where('date', $date)->first();
        if (!$holiday) {
            $this->error("❌ Tanggal {$date} tidak ditemukan.");
            return 1;
        }

        $this->line("Akan menghapus:");
        $this->line("  📅 {$holiday->date}");
        $this->line("  📝 {$holiday->description}");

        if (!$this->confirm('Yakin ingin menghapus?')) {
            $this->info('Dibatalkan.');
            return 0;
        }

        try {
            $holiday->delete();
            $this->info("✅ BERHASIL DIHAPUS!");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
    }
}
