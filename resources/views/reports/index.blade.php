@extends('app')

@push('page_title', 'Reports')

@section('content')
<div class="fade-in-up">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="page-header">
        <div class="breadcrumb-custom mb-1">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            Reports
        </div>
        <h1><i class="fa-solid fa-chart-bar me-2" style="color:var(--warning);font-size:1.3rem;"></i>Reports &amp; Analytics</h1>
        <p>Track monthly registrations, counts, and system insights</p>
    </div>

    {{-- ── Summary Cards ────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-user-graduate"></i></div>
                <div class="stat-info">
                    <h3>{{ number_format($totalStudents) }}</h3>
                    <p>Total Students</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-chalkboard-user"></i></div>
                <div class="stat-info">
                    <h3>{{ number_format($totalTeachers) }}</h3>
                    <p>Total Teachers</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fa-solid fa-book-open"></i></div>
                <div class="stat-info">
                    <h3>{{ number_format($totalSubjects) }}</h3>
                    <p>Total Subjects</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Chart ────────────────────────────────────────────────────────── --}}
    <div class="card-dark mb-4">
        <div class="card-dark-header">
            <h5><i class="fa-solid fa-chart-line me-2" style="color:var(--accent);"></i>Monthly Registrations (Last 12 Months)</h5>
            <div class="d-flex gap-2">
                <span class="badge-dark badge-blue"><i class="fa-solid fa-circle me-1" style="font-size:0.6rem;"></i>Students</span>
                <span class="badge-dark badge-green"><i class="fa-solid fa-circle me-1" style="font-size:0.6rem;"></i>Teachers</span>
            </div>
        </div>
        <div class="card-dark-body">
            <canvas id="monthlyChart" height="80"></canvas>
        </div>
    </div>

    {{-- ── Tables ───────────────────────────────────────────────────────── --}}
    <div class="row g-3">

        {{-- Student Summary --}}
        <div class="col-12 col-xl-6">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-user-graduate me-2" style="color:var(--accent);"></i>Student Report</h5>
                    <span class="badge-dark badge-blue">{{ $students->count() }} total</span>
                </div>
                <div class="table-wrapper-dark">
                    <table class="table-dark-custom">
                        <thead>
                            <tr>
                                <th>Reg No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($students->take(10) as $student)
                            <tr>
                                <td><span class="badge-dark badge-blue">{{ $student->reg_No }}</span></td>
                                <td style="font-weight:500;">{{ $student->Name }}</td>
                                <td style="font-size:0.82rem;color:var(--text-secondary);">{{ $student->email }}</td>
                                <td style="font-size:0.82rem;color:var(--text-muted);">{{ $student->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="empty-state" style="padding:20px;"><i class="fa-solid fa-user-graduate"></i><p>No data.</p></div></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Teacher Summary --}}
        <div class="col-12 col-xl-6">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-chalkboard-user me-2" style="color:var(--success);"></i>Teacher Report</h5>
                    <span class="badge-dark badge-green">{{ $teachers->count() }} total</span>
                </div>
                <div class="table-wrapper-dark">
                    <table class="table-dark-custom">
                        <thead>
                            <tr>
                                <th>Teacher ID</th>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($teachers->take(10) as $teacher)
                            <tr>
                                <td><span class="badge-dark badge-green">{{ $teacher->teacher_id ?? 'N/A' }}</span></td>
                                <td style="font-weight:500;">{{ $teacher->Teacher_Name }}</td>
                                <td><span class="badge-dark badge-purple" style="font-size:0.7rem;">{{ $teacher->subject ?? '—' }}</span></td>
                                <td style="font-size:0.82rem;color:var(--text-muted);">{{ $teacher->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4"><div class="empty-state" style="padding:20px;"><i class="fa-solid fa-chalkboard-user"></i><p>No data.</p></div></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Subject Summary --}}
        <div class="col-12">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-book-open me-2" style="color:var(--purple);"></i>Subject Report</h5>
                    <span class="badge-dark badge-purple">{{ $subjects->count() }} total</span>
                </div>
                <div class="table-wrapper-dark">
                    <table class="table-dark-custom">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Subject Name</th>
                                <th>Teacher</th>
                                <th>Credits</th>
                                <th>Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td><span class="badge-dark badge-purple">{{ $subject->subject_code }}</span></td>
                                <td style="font-weight:500;">{{ $subject->subject_name }}</td>
                                <td style="font-size:0.85rem;">{{ $subject->teacher ? $subject->teacher->Teacher_Name : '—' }}</td>
                                <td><span class="badge-dark badge-amber">{{ $subject->credits ?? $subject->credit_hours }}</span></td>
                                <td style="font-size:0.85rem;color:var(--text-secondary);">{{ $subject->semester ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="empty-state" style="padding:20px;"><i class="fa-solid fa-book-open"></i><p>No data.</p></div></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('monthlyChart').getContext('2d');

    const gradBlue = ctx.createLinearGradient(0, 0, 0, 300);
    gradBlue.addColorStop(0, 'rgba(59,130,246,0.3)');
    gradBlue.addColorStop(1, 'rgba(59,130,246,0)');

    const gradGreen = ctx.createLinearGradient(0, 0, 0, 300);
    gradGreen.addColorStop(0, 'rgba(16,185,129,0.3)');
    gradGreen.addColorStop(1, 'rgba(16,185,129,0)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($monthlyLabels),
            datasets: [
                {
                    label: 'Students',
                    data: @json($monthlyStudentData),
                    backgroundColor: 'rgba(59,130,246,0.7)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    borderRadius: 6,
                },
                {
                    label: 'Teachers',
                    data: @json($monthlyTeacherData),
                    backgroundColor: 'rgba(16,185,129,0.7)',
                    borderColor: '#10b981',
                    borderWidth: 1,
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    labels: { color: '#94a3b8', font: { family: 'Inter' }, boxWidth: 12, borderRadius: 4 }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    borderColor: 'rgba(59,130,246,0.4)',
                    borderWidth: 1,
                    titleColor: '#f1f5f9',
                    bodyColor: '#94a3b8',
                    padding: 12,
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { color: '#64748b', font: { family: 'Inter', size: 11 }, maxRotation: 45 }
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { color: '#64748b', font: { family: 'Inter', size: 12 }, stepSize: 1 },
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
