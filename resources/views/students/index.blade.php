@extends('app')

@push('page_title', 'Students')

@push('css')
<style>
    .import-errors-list { max-height: 200px; overflow-y: auto; font-size: 0.85rem; }
</style>
@endpush

@section('content')
<div class="fade-in-up">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="page-header">
        <div class="breadcrumb-custom mb-1">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            Students
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1><i class="fa-solid fa-user-graduate me-2" style="color:var(--accent);font-size:1.3rem;"></i>Students</h1>
                <p>Manage all registered students</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.students.export.pdf') }}{{ request('search') ? '?search='.urlencode(request('search')) : '' }}"
                   class="btn-accent" style="background:#ef4444;">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('admin.students.export.excel') }}{{ request('search') ? '?search='.urlencode(request('search')) : '' }}"
                   class="btn-accent" style="background:#10b981;">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
                <button type="button" class="btn-accent" style="background:#f59e0b;"
                        data-bs-toggle="modal" data-bs-target="#importExcelModal">
                    <i class="fa-solid fa-file-import"></i> Import
                </button>
                <button type="button" class="btn-accent" id="openRegisterModalBtn"
                        data-bs-toggle="modal" data-bs-target="#registerStudentModal">
                    <i class="fa-solid fa-plus"></i> Add Student
                </button>
            </div>
        </div>
    </div>

    {{-- ── Main Card ────────────────────────────────────────────────────── --}}
    <div class="card-dark">
        <div class="card-dark-header">
            <h5><i class="fa-solid fa-list me-2" style="color:var(--text-muted);"></i>Student List
                <span class="badge-dark badge-blue ms-2">{{ $students->count() }} records</span>
            </h5>
            <div class="search-input-wrapper">
                <i class="fa-solid fa-magnifying-glass si-icon"></i>
                <input type="text"
                       id="searchInput"
                       class="search-input-dark"
                       value="{{ request('search') }}"
                       placeholder="Search students...">
            </div>
        </div>

        <div class="table-wrapper-dark">
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th>Reg No</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date of Birth</th>
                        <th>Address</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($students as $student)
                    <tr>
                        <td><span class="badge-dark badge-blue">{{ $student->reg_No }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle" style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);width:34px;height:34px;font-size:0.78rem;flex-shrink:0;">
                                    {{ strtoupper(substr($student->Name, 0, 1)) }}
                                </div>
                                <span style="font-weight:500;">{{ $student->Name }}</span>
                            </div>
                        </td>
                        <td style="color:var(--text-secondary);font-size:0.85rem;">{{ $student->username }}</td>
                        <td style="color:var(--text-secondary);font-size:0.85rem;">{{ $student->email }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') }}</td>
                        <td class="cell-truncate" title="{{ $student->address }}">{{ $student->address }}</td>
                        <td style="text-align:center;">
                                <button type="button"
                                        class="btn-icon view"
                                        title="View Details"
                                        onclick="openViewModal({{ $student->id }})"
                                        style="color: var(--info); background: rgba(14, 165, 233, 0.1);">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button type="button"
                                        class="btn-icon edit"
                                        title="Edit Student"
                                        onclick="openEditModal(this)"
                                        data-id="{{ $student->id }}"
                                        data-reg="{{ $student->reg_No }}"
                                        data-name="{{ $student->Name }}"
                                        data-username="{{ $student->username }}"
                                        data-email="{{ $student->email }}"
                                        data-phone="{{ $student->phone }}"
                                        data-bod="{{ $student->date_of_birth }}"
                                        data-password="{{ $student->password }}"
                                        data-address="{{ $student->address }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ route('admin.students.delete', $student->id) }}"
                                   class="btn-icon del"
                                   title="Delete Student"
                                   onclick="return confirmDelete(this)">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fa-solid fa-user-graduate"></i>
                                <p>No student records found. Add your first student!</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════════
     REGISTER STUDENT MODAL
═══════════════════════════════════════════════════ --}}
<div class="modal fade modal-dark" id="registerStudentModal" tabindex="-1"
     aria-labelledby="registerStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerStudentModalLabel">
                    <i class="fa-solid fa-user-plus me-2" style="color:var(--accent);"></i>Register New Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.students.store') }}" method="post" id="registerStudentForm">
                @csrf
                <div class="modal-body form-dark">

                    @if ($errors->any())
                        <div class="alert alert-danger" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;border-radius:10px;">
                            <strong><i class="fa-solid fa-circle-exclamation me-2"></i>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Registration No</label>
                            <input type="text" id="register_reg_no_display" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="register_name"
                                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name') }}" placeholder="Enter full name" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="register_username"
                                   class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                   value="{{ old('username') }}" placeholder="Enter unique username" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="register_email"
                                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   value="{{ old('email') }}" placeholder="Enter email" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="register_phone"
                                   class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                   value="{{ old('phone') }}" placeholder="Enter phone number" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="bod" id="register_bod"
                                   class="form-control {{ $errors->has('bod') ? 'is-invalid' : '' }}"
                                   value="{{ old('bod') }}" required>
                            @error('bod')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="register_password"
                                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Min. 6 characters" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" id="register_address"
                                   class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                   value="{{ old('address') }}" placeholder="Enter address" required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-accent" style="background:var(--bg-tertiary);color:var(--text-secondary);" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn-accent">
                        <i class="fa-solid fa-user-plus me-1"></i> Register Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     EDIT STUDENT MODAL
═══════════════════════════════════════════════════ --}}
<div class="modal fade modal-dark" id="updateStudentModal" tabindex="-1"
     aria-labelledby="updateStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStudentModalLabel">
                    <i class="fa-solid fa-pen me-2" style="color:var(--accent);"></i>Edit Student Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.students.update') }}" method="post">
                @csrf
                <div class="modal-body form-dark">
                    <input type="hidden" name="id" id="update_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Registration No</label>
                            <input type="text" name="reg_No" id="update_reg_No" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="update_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="update_username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="update_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="update_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="bod" id="update_bod" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="update_password" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" id="update_address" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-accent" style="background:var(--bg-tertiary);color:var(--text-secondary);" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn-accent">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     IMPORT EXCEL MODAL
═══════════════════════════════════════════════════ --}}
<div class="modal fade modal-dark" id="importExcelModal" tabindex="-1"
     aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importExcelModalLabel">
                    <i class="fa-solid fa-file-import me-2" style="color:var(--warning);"></i>Import Students from Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.students.import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body form-dark">
                    <p style="font-size:0.875rem;color:var(--text-secondary);">
                        Upload an <strong style="color:var(--text-primary);">.xlsx</strong> or <strong style="color:var(--text-primary);">.xls</strong> file to import students.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Excel File</label>
                        <input type="file" name="excel_file" id="excel_file"
                               class="form-control" accept=".xlsx,.xls" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-accent" style="background:var(--bg-tertiary);color:var(--text-secondary);" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn-accent" style="background:var(--warning);">
                        <i class="fa-solid fa-file-import me-1"></i> Import Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     VIEW STUDENT PROFILE MODAL
═══════════════════════════════════════════════════ --}}
<div class="modal fade modal-dark" id="viewStudentModal" tabindex="-1" aria-labelledby="viewStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewStudentModalLabel">
                    <i class="fa-solid fa-user-graduate me-2" style="color:var(--info);"></i>Student Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form-dark">
                <div class="row g-4">
                    {{-- Left Column: Personal Info --}}
                    <div class="col-md-5">
                        <div class="card-dark" style="border: 1px solid rgba(255,255,255,0.05); background: var(--bg-tertiary);">
                            <div class="card-dark-header p-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <h6 class="mb-0" style="color: var(--text-primary);">Personal Details</h6>
                            </div>
                            <div class="p-3">
                                <div class="text-center mb-3">
                                    <div class="avatar-circle mx-auto mb-2" id="view_avatar" style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);width:80px;height:80px;font-size:2rem;">
                                        S
                                    </div>
                                    <h5 class="mb-1" id="view_name" style="color: var(--text-primary);">Student Name</h5>
                                    <span class="badge-dark badge-blue" id="view_reg_no">REG001</span>
                                </div>
                                <ul class="list-group list-group-flush form-dark" style="background: transparent; border-radius: 0;">
                                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background: transparent; border-color: rgba(255,255,255,0.05); color: var(--text-secondary); padding: 0.75rem 0;">
                                        <span><i class="fa-solid fa-envelope me-2 w-15px"></i>Email</span>
                                        <span id="view_email" style="color: var(--text-primary); text-align: right; word-break: break-all; max-width: 60%;"></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background: transparent; border-color: rgba(255,255,255,0.05); color: var(--text-secondary); padding: 0.75rem 0;">
                                        <span><i class="fa-solid fa-phone me-2 w-15px"></i>Phone</span>
                                        <span id="view_phone" style="color: var(--text-primary);"></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background: transparent; border-color: rgba(255,255,255,0.05); color: var(--text-secondary); padding: 0.75rem 0;">
                                        <span><i class="fa-solid fa-cake-candles me-2 w-15px"></i>DOB</span>
                                        <span id="view_bod" style="color: var(--text-primary);"></span>
                                    </li>
                                    <li class="list-group-item d-flex flex-column" style="background: transparent; border-color: rgba(255,255,255,0.05); color: var(--text-secondary); padding: 0.75rem 0;">
                                        <span class="mb-1"><i class="fa-solid fa-location-dot me-2 w-15px"></i>Address</span>
                                        <span id="view_address" style="color: var(--text-primary); padding-left: 25px; line-height: 1.4; font-size: 0.9rem;"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Right Column: Academic Info & Stats --}}
                    <div class="col-md-7">
                        {{-- Stats Row --}}
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-sm-4">
                                <div class="card-dark text-center p-3 h-100" style="border: 1px solid rgba(255,255,255,0.05); background: var(--bg-tertiary);">
                                    <i class="fa-solid fa-pen-to-square mb-2" style="font-size: 1.5rem; color: var(--warning);"></i>
                                    <h4 class="mb-0" id="view_total_attempts" style="color: var(--text-primary);">0</h4>
                                    <span style="font-size: 0.75rem; color: var(--text-secondary);">Attempts</span>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4">
                                <div class="card-dark text-center p-3 h-100" style="border: 1px solid rgba(255,255,255,0.05); background: var(--bg-tertiary);">
                                    <i class="fa-solid fa-check-circle mb-2" style="font-size: 1.5rem; color: var(--success);"></i>
                                    <h4 class="mb-0" id="view_completed_quizzes" style="color: var(--text-primary);">0</h4>
                                    <span style="font-size: 0.75rem; color: var(--text-secondary);">Completed</span>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="card-dark text-center p-3 h-100" style="border: 1px solid rgba(255,255,255,0.05); background: var(--bg-tertiary);">
                                    <i class="fa-solid fa-chart-line mb-2" style="font-size: 1.5rem; color: var(--info);"></i>
                                    <h4 class="mb-0" id="view_avg_marks" style="color: var(--text-primary);">0%</h4>
                                    <span style="font-size: 0.75rem; color: var(--text-secondary);">Avg Marks</span>
                                </div>
                            </div>
                        </div>

                        {{-- Enrolled Subjects --}}
                        <h6 style="color: var(--text-primary); margin-bottom: 0.75rem;"><i class="fa-solid fa-book me-2" style="color: var(--accent);"></i>Enrolled Subjects</h6>
                        <div id="view_subjects" class="d-flex flex-wrap gap-2 mb-4">
                            <!-- Subjects will be injected here -->
                        </div>

                        {{-- Latest Results --}}
                        <h6 style="color: var(--text-primary); margin-bottom: 0.75rem;"><i class="fa-solid fa-clock-rotate-left me-2" style="color: var(--success);"></i>Latest Results</h6>
                        <div id="view_latest_results" class="d-flex flex-column gap-2">
                            <!-- Results will be injected here -->
                            <div class="text-center p-3" style="color: var(--text-muted); font-size: 0.85rem; border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px;">
                                Loading results...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-accent" style="background:var(--bg-tertiary);color:var(--text-secondary);" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    // ── Delete confirmation ────────────────────────────────────────────────
    function confirmDelete(element) {
        event.preventDefault();
        const deleteUrl = element.getAttribute('href');
        Swal.fire({
            title: 'Delete Student?',
            text: 'This student record will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#3b82f6',
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'Cancel',
            background: '#1e293b',
            color: '#f1f5f9',
        }).then((result) => {
            if (result.isConfirmed) window.location.href = deleteUrl;
        });
        return false;
    }

    // ── Success / Error toasts ─────────────────────────────────────────────
    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            title: '{{ session("title") ?? "Success!" }}',
            text: '{{ session("success") }}',
            icon: 'success',
            timer: 2500,
            showConfirmButton: false,
            background: '#1e293b',
            color: '#f1f5f9',
        });
    });
    @endif

    @if(session('error'))
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            title: '{{ session("error_title") ?? "Error!" }}',
            html: '{!! session("error") !!}',
            icon: 'error',
            timer: 4500,
            showConfirmButton: true,
            background: '#1e293b',
            color: '#f1f5f9',
        });
    });
    @endif

    // ── Re-open register modal on validation errors ────────────────────────
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('registerStudentModal')).show();
    });
    @endif

    // ── Load next Reg No on Add Student modal open ──────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('registerStudentModal').addEventListener('show.bs.modal', function () {
            fetch('{{ route("admin.students.next-reg-no") }}')
                .then(r => r.json())
                .then(d => { document.getElementById('register_reg_no_display').value = d.reg_no; })
                .catch(() => { document.getElementById('register_reg_no_display').value = 'STU???'; });
        });
        

    });

    // ── Live search ───────────────────────────────────────────────────────
    // Replaced by DataTables, keeping for fallback if needed
    // document.addEventListener('DOMContentLoaded', function () {
    //     document.getElementById('searchInput').addEventListener('keyup', function (e) {
    //         if (e.key === 'Enter') {
    //             const val = e.target.value.trim();
    //             if(val) {
    //                 window.location.href = '{{ route("admin.students.index") }}?search=' + encodeURIComponent(val);
    //             } else {
    //                 window.location.href = '{{ route("admin.students.index") }}';
    //             }
    //         }
    //     });
    // });

    // ── DataTables Initialization ─────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const table = $('.table-dark-custom').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "language": {
                "search": "",
                "searchPlaceholder": "Search students...",
                "lengthMenu": "Show _MENU_ records",
                "info": "Showing _START_ to _END_ of _TOTAL_ students",
                "infoEmpty": "No records available"
            },
            "order": [], // disable initial sort
            "columnDefs": [
                { "orderable": false, "targets": -1 } // Disable sorting on Actions column
            ],
            "dom": "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        });
        
        // Hide custom search wrapper since DataTable provides one
        $('.card-dark-header').addClass('dt-active');
    });

    // ── Populate edit modal ───────────────────────────────────────────────
    function openEditModal(btn) {
        document.getElementById('update_id').value       = btn.getAttribute('data-id');
        document.getElementById('update_reg_No').value   = btn.getAttribute('data-reg');
        document.getElementById('update_name').value     = btn.getAttribute('data-name');
        document.getElementById('update_username').value = btn.getAttribute('data-username');
        document.getElementById('update_email').value    = btn.getAttribute('data-email');
        document.getElementById('update_phone').value    = btn.getAttribute('data-phone');
        document.getElementById('update_bod').value      = btn.getAttribute('data-bod');
        document.getElementById('update_password').value = btn.getAttribute('data-password');
        document.getElementById('update_address').value  = btn.getAttribute('data-address');
        


        new bootstrap.Modal(document.getElementById('updateStudentModal')).show();
    }

    // ── Fetch and Populate view modal ──────────────────────────────────────
    function openViewModal(studentId) {
        // Show loading state or clear previous data if needed
        document.getElementById('view_subjects').innerHTML = '<span style="color: var(--text-muted); font-size: 0.85rem;">Loading subjects...</span>';
        document.getElementById('view_latest_results').innerHTML = '<div class="text-center p-3" style="color: var(--text-muted); font-size: 0.85rem; border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px;">Loading results...</div>';
        
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('viewStudentModal'));
        modal.show();

        // Fetch student details
        fetch(`{{ url('admin/students') }}/${studentId}/details`)
            .then(res => res.json())
            .then(data => {
                const s = data.student;
                const stats = data.stats;
                const results = data.latest_results;

                // Populate Personal Details
                document.getElementById('view_avatar').innerText = s.Name.charAt(0).toUpperCase();
                document.getElementById('view_name').innerText = s.Name;
                document.getElementById('view_reg_no').innerText = s.reg_No;
                document.getElementById('view_email').innerText = s.email;
                document.getElementById('view_phone').innerText = s.phone;
                
                // Format DOB
                const dob = new Date(s.date_of_birth);
                document.getElementById('view_bod').innerText = dob.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                
                document.getElementById('view_address').innerText = s.address;

                // Populate Stats
                document.getElementById('view_total_attempts').innerText = stats.total_attempts;
                document.getElementById('view_completed_quizzes').innerText = stats.completed_quizzes;
                document.getElementById('view_avg_marks').innerText = stats.average_marks + '%';

                // Populate Subjects
                const subjectsContainer = document.getElementById('view_subjects');
                if (s.subjects && s.subjects.length > 0) {
                    subjectsContainer.innerHTML = s.subjects.map(sub => 
                        `<span class="badge-dark badge-purple" style="font-size: 0.8rem; padding: 0.4rem 0.6rem;">${sub.subject_code} - ${sub.subject_name}</span>`
                    ).join('');
                } else {
                    subjectsContainer.innerHTML = '<span style="color: var(--text-muted); font-size: 0.85rem;">No subjects assigned</span>';
                }

                // Populate Latest Results
                const resultsContainer = document.getElementById('view_latest_results');
                if (results && results.length > 0) {
                    resultsContainer.innerHTML = results.map(r => `
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="color: var(--text-primary); font-weight: 500; font-size: 0.9rem;">${r.quiz_title}</div>
                                <div style="color: var(--text-secondary); font-size: 0.75rem;">${r.subject} • ${r.date}</div>
                            </div>
                            <div style="font-weight: 600; color: ${r.percentage >= 50 ? 'var(--success)' : 'var(--danger)'}; font-size: 1.1rem;">
                                ${r.percentage}%
                            </div>
                        </div>
                    `).join('');
                } else {
                    resultsContainer.innerHTML = '<div class="text-center p-3" style="color: var(--text-muted); font-size: 0.85rem; border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px;">No completed quizzes yet</div>';
                }
            })
            .catch(err => {
                console.error("Failed to fetch student details:", err);
                document.getElementById('view_subjects').innerHTML = '<span style="color: var(--danger); font-size: 0.85rem;">Failed to load data.</span>';
                document.getElementById('view_latest_results').innerHTML = '<div class="text-center p-3" style="color: var(--danger); font-size: 0.85rem; border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px;">Failed to load results.</div>';
            });
    }
</script>
@endpush
