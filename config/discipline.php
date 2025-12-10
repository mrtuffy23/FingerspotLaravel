<?php
return [
    'initial_points' => 100,
    'rules' => [
        'izin' => ['point' => -20, 'compensated_hours' => 7],
        'sakit' => ['point' => -5, 'compensated_hours' => 7],
        'sakit_sabtu' => ['point' => -5, 'compensated_hours' => 5],
        'kecelakaan' => ['point' => -1, 'compensated_hours' => 7],
        'alpha' => ['point' => -40, 'compensated_hours' => 0],
        'cuti' => ['point' => 0, 'compensated_hours' => 7],
        'terlambat' => ['point' => -5, 'compensated_hours' => 0],
        'pulang_cepat' => ['point' => -10, 'compensated_hours' => 0],
        'izin_keluar' => ['point' => -5, 'compensated_hours' => 0],
        'libur' => ['point' => 0, 'compensated_hours' => 0],
    ],
    'tolerance_minutes' => 5
];
