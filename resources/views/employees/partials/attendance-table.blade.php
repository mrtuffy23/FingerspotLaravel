<table class="table table-hover">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($employee->attendances()->latest('date')->limit(10)->get() as $attendance)
            <tr>
                <td>{{ $attendance->date->format('d-m-Y') }}</td>
                <td>{{ $attendance->first_in?->format('H:i') ?? '-' }}</td>
                <td>{{ $attendance->last_out?->format('H:i') ?? '-' }}</td>
                <td>
                    @if($attendance->status === 'present')
                        <span class="badge bg-success">Hadir</span>
                    @elseif($attendance->status === 'late')
                        <span class="badge bg-warning">Terlambat</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($attendance->status) }}</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">Belum ada data absensi</td>
            </tr>
        @endforelse
    </tbody>
</table>
