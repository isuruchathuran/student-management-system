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
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            Students
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1><i class="fa-solid fa-user-graduate me-2" style="color:var(--accent);font-size:1.3rem;"></i>Students</h1>
                <p>Manage all registered students</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('students.export.pdf') }}{{ request('search') ? '?search='.urlencode(request('search')) : '' }}"
                   class="btn-accent" style="background:#ef4444;">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('students.export.excel') }}{{ request('search') ? '?search='.urlencode(request('search')) : '' }}"
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
                        <td style="color:var(--text-secondary);font-size:0.85rem;">{{ $student->email }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') }}</td>
                        <td class="cell-truncate" title="{{ $student->address }}">{{ $student->address }}</td>
                        <td style="text-align:center;">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button"
                                        class="btn-icon edit"
                                        title="Edit Student"
                                        onclick="openEditModal(this)"
                                        data-id="{{ $student->id }}"
                                        data-reg="{{ $student->reg_No }}"
                                        data-name="{{ $student->Name }}"
                                        data-email="{{ $student->email }}"
                                        data-phone="{{ $student->phone }}"
                                        data-bod="{{ $student->date_of_birth }}"
                                        data-password="{{ $student->password }}"
                                        data-address="{{ $student->address }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ route('students.delete', $student->id) }}"
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
                        <td colspan="7">
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
            <form action="{{ route('students.store') }}" method="post" id="registerStudentForm">
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
            <form action="{{ route('students.update') }}" method="post">
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
            <form action="{{ route('students.import') }}" method="post" enctype="multipart/form-data">
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

    // ── Load next Reg No on modal open ────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('registerStudentModal').addEventListener('show.bs.modal', function () {
            fetch('{{ route("students.next-reg-no") }}')
                .then(r => r.json())
                .then(d => { document.getElementById('register_reg_no_display').value = d.reg_no; })
                .catch(() => { document.getElementById('register_reg_no_display').value = 'STU???'; });
        });
    });

    // ── Live search ───────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        let delay;
        document.getElementById('searchInput').addEventListener('keyup', function () {
            clearTimeout(delay);
            const val = this.value;
            delay = setTimeout(() => {
                window.location.href = '{{ route("students.index") }}?search=' + encodeURIComponent(val);
            }, 500);
        });
    });

    // ── Populate edit modal ───────────────────────────────────────────────
    function openEditModal(btn) {
        document.getElementById('update_id').value       = btn.getAttribute('data-id');
        document.getElementById('update_reg_No').value   = btn.getAttribute('data-reg');
        document.getElementById('update_name').value     = btn.getAttribute('data-name');
        document.getElementById('update_email').value    = btn.getAttribute('data-email');
        document.getElementById('update_phone').value    = btn.getAttribute('data-phone');
        document.getElementById('update_bod').value      = btn.getAttribute('data-bod');
        document.getElementById('update_password').value = btn.getAttribute('data-password');
        document.getElementById('update_address').value  = btn.getAttribute('data-address');
        new bootstrap.Modal(document.getElementById('updateStudentModal')).show();
    }
</script>
@endpush
