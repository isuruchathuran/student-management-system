@extends('app')

@push('page_title', 'Dashboard')

@section('content')

<div class="fade-in-up">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="breadcrumb-custom mb-1">
                    <i class="fa-solid fa-gauge me-1"></i>
                    Dashboard
                </div>
                <h1>Welcome back, Admin 👋</h1>
                <p>Here's what's happening with your system today.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span style="font-size:0.8rem;color:var(--text-muted);">
                    <i class="fa-regular fa-clock me-1"></i>
                    {{ now()->format('l, d M Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── Summary Stat Cards ───────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card" style="padding:16px;">
                <div class="stat-icon blue" style="width:40px;height:40px;font-size:1rem;">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <h3 style="font-size:1.4rem;">{{ number_format($totalStudents) }}</h3>
                    <p style="font-size:0.75rem;">Total Students</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card" style="padding:16px;">
                <div class="stat-icon green" style="width:40px;height:40px;font-size:1rem;">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div class="stat-info">
                    <h3 style="font-size:1.4rem;">{{ number_format($totalTeachers) }}</h3>
                    <p style="font-size:0.75rem;">Total Teachers</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card" style="padding:16px;">
                <div class="stat-icon purple" style="width:40px;height:40px;font-size:1rem;">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div class="stat-info">
                    <h3 style="font-size:1.4rem;">{{ number_format($totalQuizzes) }}</h3>
                    <p style="font-size:0.75rem;">Total Quizzes</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card" style="padding:16px;">
                <div class="stat-icon amber" style="width:40px;height:40px;font-size:1rem;">
                    <i class="fa-solid fa-clipboard-question"></i>
                </div>
                <div class="stat-info">
                    <h3 style="font-size:1.4rem;">{{ number_format($totalSubjects) }}</h3>
                    <p style="font-size:0.75rem;">Total Subjects</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card" style="padding:16px;">
                <div class="stat-icon blue" style="width:40px;height:40px;font-size:1rem;">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div class="stat-info">
                    <h3 style="font-size:1.4rem;">{{ number_format($publishedQuizzes) }}</h3>
                    <p style="font-size:0.75rem;">Published Quizzes</p>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card" style="padding:16px;">
                <div class="stat-icon green" style="width:40px;height:40px;font-size:1rem;">
                    <i class="fa-solid fa-pen-nib"></i>
                </div>
                <div class="stat-info">
                    <h3 style="font-size:1.4rem;">{{ number_format($quizAttempts) }}</h3>
                    <p style="font-size:0.75rem;">Quiz Attempts</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Row: Chart + Activities ──────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        {{-- Chart --}}
        <div class="col-12 col-lg-8">
            <div class="card-dark h-100">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-chart-line me-2" style="color:var(--accent);"></i>Monthly Student Registrations</h5>
                    <span class="badge-dark badge-blue">Last 6 Months</span>
                </div>
                <div class="card-dark-body">
                    <canvas id="registrationsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent Activities --}}
        <div class="col-12 col-lg-4">
            <div class="card-dark h-100">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-bolt me-2" style="color:var(--warning);"></i>Recent Activities</h5>
                </div>
                <div class="card-dark-body" style="padding-top:8px;">
                    @forelse($recentActivities as $activity)
                        @php
                            $icon = 'fa-user';
                            $color = 'purple';
                            if ($activity->user_type === 'Admin') {
                                $icon = 'fa-shield-halved';
                                $color = 'purple';
                            } elseif ($activity->user_type === 'Teacher') {
                                $icon = 'fa-chalkboard-user';
                                $color = 'green';
                            } elseif ($activity->user_type === 'Student') {
                                $icon = 'fa-user-graduate';
                                $color = 'blue';
                            }
                        @endphp
                        <div class="activity-item">
                            <div class="activity-dot stat-icon {{ $color }}"
                                style="width:36px;height:36px;border-radius:10px;flex-shrink:0;">
                                <i class="fa-solid {{ $icon }}" style="font-size:0.8rem;"></i>
                            </div>
                            <div class="activity-content">
                                <p>{{ $activity->action }}</p>
                                <small>{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fa-solid fa-inbox"></i>
                            <p>No recent activities.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- ── Row: Recent Students + Recent Teachers ───────────────────────── --}}
    <div class="row g-3">

        {{-- Recent Students --}}
        <div class="col-12 col-lg-6">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-user-graduate me-2" style="color:var(--accent);"></i>Recent Students</h5>
                    <a href="{{ route('admin.students.index') }}" class="btn-accent" style="padding:6px 14px;font-size:0.8rem;">
                        View All <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-wrapper-dark">
                    <table class="table-dark-custom">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Reg No</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentStudents as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-circle" style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);width:32px;height:32px;font-size:0.75rem;">
                                                {{ strtoupper(substr($student->Name, 0, 1)) }}
                                            </div>
                                            <span>{{ $student->Name }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge-dark badge-blue">{{ $student->reg_No }}</span></td>
                                    <td style="font-size:0.8rem;color:var(--text-secondary);">{{ $student->email }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state" style="padding:24px;">
                                            <i class="fa-solid fa-user-graduate"></i>
                                            <p>No students yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Teachers --}}
        <div class="col-12 col-lg-6">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-chalkboard-user me-2" style="color:var(--success);"></i>Recent Teachers</h5>
                    <a href="{{ route('admin.teachers.index') }}" class="btn-accent" style="padding:6px 14px;font-size:0.8rem;">
                        View All <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-wrapper-dark">
                    <table class="table-dark-custom">
                        <thead>
                            <tr>
                                <th>Teacher</th>
                                <th>ID</th>
                                <th>Subject</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTeachers as $teacher)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-circle" style="background:linear-gradient(135deg,#10b981,#3b82f6);width:32px;height:32px;font-size:0.75rem;">
                                                {{ strtoupper(substr($teacher->Teacher_Name, 0, 1)) }}
                                            </div>
                                            <span>{{ $teacher->Teacher_Name }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge-dark badge-green">{{ $teacher->teacher_id ?? 'N/A' }}</span></td>
                                    <td style="font-size:0.8rem;color:var(--text-secondary);">
                                        {{ $teacher->subject ? $teacher->subject->subject_name : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state" style="padding:24px;">
                                            <i class="fa-solid fa-chalkboard-user"></i>
                                            <p>No teachers yet.</p>
                                        </div>
                                    </td>
                                </tr>
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
    const ctx = document.getElementById('registrationsChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(59,130,246,0.35)');
    gradient.addColorStop(1, 'rgba(59,130,246,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'New Students',
                data: @json($monthlyData),
                borderColor: '#3b82f6',
                backgroundColor: gradient,
                borderWidth: 2.5,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#1e293b',
                pointBorderWidth: 2,
                pointRadius: 5,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
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
                    ticks: { color: '#64748b', font: { family: 'Inter', size: 12 } }
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: {
                        color: '#64748b',
                        font: { family: 'Inter', size: 12 },
                        stepSize: 1
                    },
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
