@extends('app')

@push('page_title', 'Teachers')

@section('content')
<div class="fade-in-up">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="page-header">
        <div class="breadcrumb-custom mb-1">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            Teachers
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1><i class="fa-solid fa-chalkboard-user me-2" style="color:var(--success);font-size:1.3rem;"></i>Teachers</h1>
                <p>Manage all teachers and their assignments</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn-accent" id="openAddTeacherBtn"
                        data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                    <i class="fa-solid fa-plus"></i> Add Teacher
                </button>
            </div>
        </div>
    </div>

    {{-- ── Main Card ────────────────────────────────────────────────────── --}}
    <div class="card-dark">
        <div class="card-dark-header">
            <h5><i class="fa-solid fa-list me-2" style="color:var(--text-muted);"></i>Teacher List
                <span class="badge-dark badge-green ms-2">{{ $teachers->count() }} records</span>
            </h5>
            <div class="search-input-wrapper">
                <i class="fa-solid fa-magnifying-glass si-icon"></i>
                <input type="text"
                       id="searchInput"
                       class="search-input-dark"
                       value="{{ request('search') }}"
                       placeholder="Search teachers...">
            </div>
        </div>

        <div class="table-wrapper-dark">
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th>Teacher ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Qualification</th>
                        <th>Address</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td><span class="badge-dark badge-green">{{ $teacher->teacher_id ?? 'N/A' }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle" style="background:linear-gradient(135deg,#10b981,#3b82f6);width:34px;height:34px;font-size:0.78rem;flex-shrink:0;">
                                    {{ strtoupper(substr($teacher->Teacher_Name, 0, 1)) }}
                                </div>
                                <span style="font-weight:500;">{{ $teacher->Teacher_Name }}</span>
                            </div>
                        </td>
                        <td style="color:var(--text-secondary);font-size:0.85rem;">{{ $teacher->email }}</td>
                        <td>{{ $teacher->mobile_no }}</td>
                        <td>
                            @if($teacher->subject)
                                <span class="badge-dark badge-purple mb-1 d-inline-block">{{ $teacher->subject->subject_name }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $teacher->qualification ?? '—' }}</td>
                        <td class="cell-truncate" title="{{ $teacher->address }}">{{ $teacher->address }}</td>
                        <td style="text-align:center;">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button"
                                        class="btn-icon edit"
                                        title="Edit Teacher"
                                        onclick="openEditTeacherModal(this)"
                                        data-id="{{ $teacher->id }}"
                                        data-teacher-id="{{ $teacher->teacher_id }}"
                                        data-name="{{ $teacher->Teacher_Name }}"
                                        data-email="{{ $teacher->email }}"
                                        data-phone="{{ $teacher->mobile_no }}"
                                        data-subject-id="{{ $teacher->subject_id }}"
                                        data-qualification="{{ $teacher->qualification }}"
                                        data-password="{{ $teacher->password }}"
                                        data-address="{{ $teacher->address }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ route('admin.teachers.delete', $teacher->id) }}"
                                   class="btn-icon del"
                                   title="Delete Teacher"
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
                                <i class="fa-solid fa-chalkboard-user"></i>
                                <p>No teacher records found. Add your first teacher!</p>
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
     ADD TEACHER MODAL
═══════════════════════════════════════════════════ --}}
<div class="modal fade modal-dark" id="addTeacherModal" tabindex="-1"
     aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">
                    <i class="fa-solid fa-user-plus me-2" style="color:var(--success);"></i>Add New Teacher
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.teachers.store') }}" method="post" id="addTeacherForm">
                @csrf
                <div class="modal-body form-dark">

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;border-radius:10px;">
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
                            <label class="form-label">Teacher ID</label>
                            <input type="text" id="add_teacher_id_display" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="add_name"
                                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name') }}" placeholder="Enter full name" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="add_email"
                                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   value="{{ old('email') }}" placeholder="Enter email" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="add_password"
                                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Min. 6 characters" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_no" id="add_phone"
                                   class="form-control {{ $errors->has('mobile_no') ? 'is-invalid' : '' }}"
                                   value="{{ old('mobile_no') }}" placeholder="Enter phone number" required>
                            @error('mobile_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="add_subject_id" class="form-select {{ $errors->has('subject_id') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Select Subject --</option>
                                @foreach($availableSubjects as $subject)
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->subject_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Qualification <span class="text-danger">*</span></label>
                            <input type="text" name="qualification" id="add_qualification"
                                   class="form-control {{ $errors->has('qualification') ? 'is-invalid' : '' }}"
                                   value="{{ old('qualification') }}" placeholder="e.g. MSc Computer Science" required>
                            @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" id="add_address"
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
                    <button type="submit" class="btn-accent" style="background:var(--success);">
                        <i class="fa-solid fa-user-plus me-1"></i> Add Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     EDIT TEACHER MODAL
═══════════════════════════════════════════════════ --}}
<div class="modal fade modal-dark" id="editTeacherModal" tabindex="-1"
     aria-labelledby="editTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTeacherModalLabel">
                    <i class="fa-solid fa-pen me-2" style="color:var(--accent);"></i>Edit Teacher Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.teachers.update') }}" method="post">
                @csrf
                <div class="modal-body form-dark">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Teacher ID</label>
                            <input type="text" id="edit_teacher_id" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="edit_password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_no" id="edit_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="edit_subject_id" class="form-select" required>
                                <option value="">-- Select Subject --</option>
                                @foreach($allSubjects as $subject)
                                    <option value="{{ $subject->id }}" data-assigned="{{ $subject->teacher ? $subject->teacher->id : '' }}">
                                        {{ $subject->subject_name }} {{ $subject->teacher ? '(Assigned)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Qualification <span class="text-danger">*</span></label>
                            <input type="text" name="qualification" id="edit_qualification" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" id="edit_address" class="form-control" required>
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

@endsection

@push('script')
<script>
    function confirmDelete(element) {
        event.preventDefault();
        const deleteUrl = element.getAttribute('href');
        Swal.fire({
            title: 'Delete Teacher?',
            text: 'This teacher record will be permanently deleted!',
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
            title: 'Error!',
            html: '{!! session("error") !!}',
            icon: 'error',
            background: '#1e293b',
            color: '#f1f5f9',
        });
    });
    @endif

    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('addTeacherModal')).show();
    });
    @endif

    // Load next Teacher ID on modal open
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('addTeacherModal').addEventListener('show.bs.modal', function () {
            fetch('{{ route("admin.teachers.next-teacher-id") }}')
                .then(r => r.json())
                .then(d => { document.getElementById('add_teacher_id_display').value = d.teacher_id; })
                .catch(() => { document.getElementById('add_teacher_id_display').value = 'TCH???'; });
        });
    });

    // Live search - Replaced by DataTables
    // document.addEventListener('DOMContentLoaded', function () {
    //     let delay;
    //     document.getElementById('searchInput').addEventListener('keyup', function () {
    //         clearTimeout(delay);
    //         const val = this.value;
    //         delay = setTimeout(() => {
    //             window.location.href = '{{ route("admin.teachers.index") }}?search=' + encodeURIComponent(val);
    //         }, 500);
    //     });
    // });

    // DataTables Initialization
    document.addEventListener('DOMContentLoaded', function () {
        const table = $('.table-dark-custom').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "language": {
                "search": "",
                "searchPlaceholder": "Search teachers...",
                "lengthMenu": "Show _MENU_ records",
                "info": "Showing _START_ to _END_ of _TOTAL_ teachers",
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
        
        $('.card-dark-header').addClass('dt-active');
    });

    function openEditTeacherModal(btn) {
        document.getElementById('edit_id').value            = btn.getAttribute('data-id');
        document.getElementById('edit_teacher_id').value   = btn.getAttribute('data-teacher-id');
        document.getElementById('edit_name').value         = btn.getAttribute('data-name');
        document.getElementById('edit_email').value        = btn.getAttribute('data-email');
        document.getElementById('edit_phone').value        = btn.getAttribute('data-phone');
        
        const teacherId = btn.getAttribute('data-id');
        const subjectId = btn.getAttribute('data-subject-id');
        const options = document.getElementById('edit_subject_id').options;
        for (let i = 0; i < options.length; i++) {
            const opt = options[i];
            if (opt.value) {
                const assigned = opt.getAttribute('data-assigned');
                if (assigned && assigned != teacherId) {
                    opt.disabled = true;
                } else {
                    opt.disabled = false;
                }
            }
        }
        document.getElementById('edit_subject_id').value = subjectId;

        document.getElementById('edit_qualification').value = btn.getAttribute('data-qualification');
        document.getElementById('edit_password').value     = btn.getAttribute('data-password');
        document.getElementById('edit_address').value      = btn.getAttribute('data-address');
        new bootstrap.Modal(document.getElementById('editTeacherModal')).show();
    }
</script>
@endpush
