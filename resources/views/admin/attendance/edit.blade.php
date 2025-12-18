@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">
            <i class="bi bi-pencil-square me-2"></i> Edit Kehadiran
        </h1>
        <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- ERROR ALERT -->
    @if ($errors->any())
    <div class="alert alert-danger shadow-sm">
        <strong>Terjadi Kesalahan:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- FORM CARD -->
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Form Edit Kehadiran</h5>
        </div>

        <form action="{{ route('attendance.update', $attendance) }}" method="POST" class="p-4">
            @csrf
            @method('PUT')

            <!-- ROW 1 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Karyawan</label>
                    <select class="form-select @error('employee_id') is-invalid @enderror" 
                            id="employee_id"
                            name="employee_id" 
                            onchange="updateScheduleTime()"
                            required>
                        <option value="">Pilih Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" 
                                    data-employment-type="{{ $emp->employment_type }}"
                                {{ old('employee_id', $attendance->employee_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" 
                        class="form-control @error('date') is-invalid @enderror"
                        id="date"
                        name="date"
                        value="{{ old('date', $attendance->date?->format('Y-m-d')) }}"
                        onchange="updateScheduleTime()"
                        required>
                </div>
            </div>

            <!-- ROW 2 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Waktu Masuk</label>
                    <input type="datetime-local"
                        class="form-control @error('first_in') is-invalid @enderror"
                        id="first_in"
                        name="first_in"
                        value="{{ old('first_in', $attendance->first_in?->format('Y-m-d\TH:i')) }}"
                        onchange="calculateWorkHours()">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Waktu Keluar</label>
                    <input type="datetime-local"
                        class="form-control @error('last_out') is-invalid @enderror"
                        id="last_out"
                        name="last_out"
                        value="{{ old('last_out', $attendance->last_out?->format('Y-m-d\TH:i')) }}"
                        onchange="calculateWorkHours()">
                </div>
            </div>

            <!-- ROW 3 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jam Kerja</label>
                    <input type="number" step="any"
                        class="form-control @error('work_hours') is-invalid @enderror"
                        id="work_hours"
                        name="work_hours"
                        value="{{ old('work_hours', $attendance->work_hours) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jam Kompensasi (Otomatis)</label>
                    <input type="number" step="any"
                        class="form-control @error('compensated_hours') is-invalid @enderror"
                        id="compensated_hours"
                        name="compensated_hours"
                        value="{{ old('compensated_hours', $attendance->compensated_hours) }}"
                        readonly>
                    <small class="text-muted">Akan otomatis terisi berdasarkan izin/cuti</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3" id="leave_info_container" style="display: none;">
                    <div class="alert alert-info border-info bg-info bg-opacity-10" id="leave_info_box">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-calendar-x me-2"></i> Informasi Cuti/Izin Terdeteksi
                        </h6>
                        <div id="leave_info_content"></div>
                    </div>
                </div>
            </div>

            <!-- ROW 4 -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status"
                            name="status" required>
                        <option value="present" {{ old('status', $attendance->status) === 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="late" {{ old('status', $attendance->status) === 'late' ? 'selected' : '' }}>Telat Masuk</option>
                        <option value="absent" {{ old('status', $attendance->status) === 'absent' ? 'selected' : '' }}>Alpa</option>
                        <option value="sick" {{ old('status', $attendance->status) === 'sick' ? 'selected' : '' }}>Sakit</option>
                        <option value="on_leave" {{ old('status', $attendance->status) === 'on_leave' ? 'selected' : '' }}>Cuti</option>
                        <option value="early_leave" {{ old('status', $attendance->status) === 'early_leave' ? 'selected' : '' }}>Pulang Cepat</option>
                        <option value="accident" {{ old('status', $attendance->status) === 'accident' ? 'selected' : '' }}>Kecelakaan</option>
                        <option value="permission" {{ old('status', $attendance->status) === 'permission' ? 'selected' : '' }}>Izin</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-clock-fill me-1"></i> Jam Masuk Seharusnya
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="schedule_time"
                           readonly>
                    <small class="text-muted">Waktu masuk yang seharusnya berdasarkan jenis karyawan</small>
                </div>
            </div>

            <!-- NOTES -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea class="form-control" name="notes" rows="3">{{ old('notes', $attendance->notes) }}</textarea>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Update Kehadiran
                </button>
                <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary px-4">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<script>
// Employee schedule data
const employeeScheduleMap = {
    @foreach($employees as $emp)
        {{ $emp->id }}: {
            name: '{{ $emp->name }}',
            employment_type: '{{ $emp->employment_type }}',
            schedule_time: '{{ $emp->employment_type === "monthly" ? "08:00" : "Check shift assignment" }}'
        },
    @endforeach
};

function updateScheduleTime() {
    const employeeSelect = document.getElementById('employee_id');
    const scheduleTimeInput = document.getElementById('schedule_time');
    const dateInput = document.getElementById('date');
    
    if (employeeSelect.value && dateInput.value) {
        const employee = employeeScheduleMap[employeeSelect.value];
        
        if (employee.employment_type === 'monthly') {
            scheduleTimeInput.value = '08:00 (Karyawan Bulanan)';
        } else {
            scheduleTimeInput.value = 'Cek penugasan shift (Karyawan Harian)';
        }

        // Check for leave on this date
        checkLeaveOnDate(employeeSelect.value, dateInput.value);
    } else {
        scheduleTimeInput.value = '';
    }
}

function checkLeaveOnDate(employeeId, date) {
    if (!employeeId || !date) return;

    // Make AJAX call to check if employee has leave on this date
    fetch(`{{ route('attendance.check-leave') }}?employee_id=${employeeId}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            const leaveInfoContainer = document.getElementById('leave_info_container');
            const leaveInfoContent = document.getElementById('leave_info_content');
            const compensatedHoursInput = document.getElementById('compensated_hours');

            if (data.has_leave) {
                leaveInfoContainer.style.display = 'block';
                leaveInfoContent.innerHTML = `
                    <p class="mb-1"><strong>Tipe:</strong> ${data.leave_type}</p>
                    <p class="mb-1"><strong>Alasan:</strong> ${data.reason || '-'}</p>
                    <p class="mb-0"><strong>Kompensasi Otomatis:</strong> <span class="badge bg-success">+${data.compensation} Jam</span></p>
                `;
                compensatedHoursInput.value = data.compensation;
            } else {
                leaveInfoContainer.style.display = 'none';
                compensatedHoursInput.value = '';
            }
        })
        .catch(error => {
            console.log('Error checking leave:', error);
        });
}

function calculateWorkHours() {
    const firstInInput = document.getElementById('first_in');
    const lastOutInput = document.getElementById('last_out');
    const workHoursInput = document.getElementById('work_hours');
    const statusSelect = document.getElementById('status');
    const employeeSelect = document.getElementById('employee_id');
    const dateInput = document.getElementById('date');
    
    if (firstInInput.value && lastOutInput.value) {
        const firstIn = new Date(firstInInput.value);
        const lastOut = new Date(lastOutInput.value);
        
        // Hitung selisih dalam menit
        const diffMs = lastOut - firstIn;
        const diffMins = diffMs / (1000 * 60);
        const diffHours = diffMins / 60;
        
        // Kurangi 1 jam untuk istirahat
        const workHours = Math.max(0, diffHours - 1);
        
        // Set nilai dengan 2 desimal
        workHoursInput.value = Math.round(workHours * 100) / 100;
        
        // Check if employee is late
        if (employeeSelect.value && dateInput.value) {
            const employee = employeeScheduleMap[employeeSelect.value];
            const checkInTime = firstIn.getHours().toString().padStart(2, '0') + ':' + 
                               firstIn.getMinutes().toString().padStart(2, '0');
            
            // Schedule time based on employment type
            let scheduleTime = '08:00'; // default for monthly
            
            if (employee.employment_type !== 'monthly') {
                // For daily employees, we would need to check from server
                // For now, we'll just show the check-in time
                console.log('Daily employee - check shift assignment from server');
            }
            
            // Auto-set status if late (only if both times are filled and status not already set)
            if (statusSelect.value === '' || statusSelect.value === 'present') {
                if (checkInTime > scheduleTime) {
                    statusSelect.value = 'late';
                    statusSelect.style.borderColor = '#ff6b6b';
                } else {
                    statusSelect.value = 'present';
                    statusSelect.style.borderColor = '';
                }
            }
        }
    }
}

// Initialize schedule time on page load
document.addEventListener('DOMContentLoaded', function() {
    updateScheduleTime();
});
</script>
@endsection
