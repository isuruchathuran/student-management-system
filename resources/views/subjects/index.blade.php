@extends('app')

@push('page_title', 'Subjects')

@section('content')
<div class="fade-in-up">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="page-header">
        <div class="breadcrumb-custom mb-1">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            Subjects
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1><i class="fa-solid fa-book-open me-2" style="color:var(--purple);font-size:1.3rem;"></i>Subjects</h1>
                <p>Manage all academic subjects and course assignments</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn-accent" style="background:var(--purple);"
                        data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    <i class="fa-solid fa-plus"></i> Add Subject
                </button>
            </div>
        </div>
    </div>

    {{-- ── Main Card ────────────────────────────────────────────────────── --}}
    <div class="card-dark">
        <div class="card-dark-header">
            <h5><i class="fa-solid fa-list me-2" style="color:var(--text-muted);"></i>Subject List
                <span class="badge-dark badge-purple ms-2">{{ $subjects->count() }} records</span>
            </h5>
            <div class="search-input-wrapper">
                <i class="fa-solid fa-magnifying-glass si-icon"></i>
                <input type="text"
                       id="searchInput"
                       class="search-input-dark"
                       value="{{ request('search') }}"
                       placeholder="Search subjects...">
            </div>
        </div>

        <div class="table-wrapper-dark">
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Assigned Teacher</th>
                        <th>Credits</th>
                        <th>Semester</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($subjects as $subject)
                    <tr>
                        <td><span class="badge-dark badge-purple">{{ $subject->subject_code }}</span></td>
                        <td style="font-weight:500;">{{ $subject->subject_name }}</td>
                        <td>
                            @if($subject->teacher)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle" style="background:linear-gradient(135deg,#10b981,#3b82f6);width:28px;height:28px;font-size:0.7rem;flex-shrink:0;">
                                        {{ strtoupper(substr($subject->teacher->Teacher_Name, 0, 1)) }}
                                    </div>
                                    <span style="font-size:0.875rem;">{{ $subject->teacher->Teacher_Name }}</span>
                                </div>
                            @else
                                <span style="color:var(--text-muted);font-size:0.85rem;">— Unassigned</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-dark badge-amber">
                                <i class="fa-solid fa-star me-1" style="font-size:0.65rem;"></i>
                                {{ $subject->credits ?? $subject->credit_hours }} Credits
                            </span>
                        </td>
                        <td>{{ $subject->semester ?? '—' }}</td>
                        <td style="text-align:center;">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button"
                                        class="btn-icon edit"
                                        title="Edit Subject"
                                        onclick="openEditSubjectModal(this)"
                                        data-id="{{ $subject->id }}"
                                        data-code="{{ $subject->subject_code }}"
                                        data-name="{{ $subject->subject_name }}"
                                        data-teacher="{{ $subject->teacher_id }}"
                                        data-credits="{{ $subject->credits ?? $subject->credit_hours }}"
                                        data-semester="{{ $subject->semester }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="{{ route('subjects.delete', $subject->id) }}"
                                   class="btn-icon del"
                                   title="Delete Subject"
                                   onclick="return confirmDelete(this)">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fa-solid fa-book-open"></i>
                                <p>No subject records found. Add your first subject!</p>
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
     ADD SUBJECT MODAL
═══════════════════════════════════════════════════ --}}
<div class="modal fade modal-dark" id="addSubjectModal" tabindex="-1"
     aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">
                    <i class="fa-solid fa-plus me-2" style="color:var(--purple);"></i>Add New Subject
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('subjects.store') }}" method="post" id="addSubjectForm">
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
                            <label class="form-label">Subject Code <span class="text-danger">*</span></label>
                            <input type="text" name="subject_code" id="add_code"
                                   class="form-control {{ $errors->has('subject_code') ? 'is-invalid' : '' }}"
                                   value="{{ old('subject_code') }}" placeholder="e.g. CS101" required>
                            @error('subject_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" name="subject_name" id="add_name"
                                   class="form-control {{ $errors->has('subject_name') ? 'is-invalid' : '' }}"
                                   value="{{ old('subject_name') }}" placeholder="e.g. Introduction to Programming" required>
                            @error('subject_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assigned Teacher</label>
                            <select name="teacher_id" id="add_teacher" class="form-select">
                                <option value="">— Select Teacher —</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->Teacher_Name }} ({{ $teacher->subject }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Credits <span class="text-danger">*</span></label>
                            <input type="number" name="credits" id="add_credits"
                                   class="form-control {{ $errors->has('credits') ? 'is-invalid' : '' }}"
                                   value="{{ old('credits', 3) }}" min="1" max="10" required>
                            @error('credits')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" id="add_semester"
                                    class="form-select {{ $errors->has('semester') ? 'is-invalid' : '' }}" required>
                                <option value="">Select</option>
                                @foreach(['Semester 1','Semester 2','Semester 3','Semester 4','Semester 5','Semester 6','Semester 7','Semester 8'] as $sem)
                                    <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                                @endforeach
                            </select>
                            @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-accent" style="background:var(--bg-tertiary);color:var(--text-secondary);" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn-accent" style="background:var(--purple);">
                        <i class="fa-solid fa-plus me-1"></i> Add Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     EDIT SUBJECT MODAL
═══════════════════════════════════════════════════ --}}
<div class="modal fade modal-dark" id="editSubjectModal" tabindex="-1"
     aria-labelledby="editSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubjectModalLabel">
                    <i class="fa-solid fa-pen me-2" style="color:var(--accent);"></i>Edit Subject
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('subjects.update') }}" method="post">
                @csrf
                <div class="modal-body form-dark">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Subject Code <span class="text-danger">*</span></label>
                            <input type="text" name="subject_code" id="edit_code" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject Name <span class="text-danger">*</span></label>
                            <input type="text" name="subject_name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assigned Teacher</label>
                            <select name="teacher_id" id="edit_teacher" class="form-select">
                                <option value="">— Select Teacher —</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">
                                        {{ $teacher->Teacher_Name }} ({{ $teacher->subject }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Credits <span class="text-danger">*</span></label>
                            <input type="number" name="credits" id="edit_credits" class="form-control" min="1" max="10" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" id="edit_semester" class="form-select" required>
                                <option value="">Select</option>
                                @foreach(['Semester 1','Semester 2','Semester 3','Semester 4','Semester 5','Semester 6','Semester 7','Semester 8'] as $sem)
                                    <option value="{{ $sem }}">{{ $sem }}</option>
                                @endforeach
                            </select>
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
            title: 'Delete Subject?',
            text: 'This subject record will be permanently deleted!',
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
        new bootstrap.Modal(document.getElementById('addSubjectModal')).show();
    });
    @endif

    // Live search
    document.addEventListener('DOMContentLoaded', function () {
        let delay;
        document.getElementById('searchInput').addEventListener('keyup', function () {
            clearTimeout(delay);
            const val = this.value;
            delay = setTimeout(() => {
                window.location.href = '{{ route("subjects.index") }}?search=' + encodeURIComponent(val);
            }, 500);
        });
    });

    function openEditSubjectModal(btn) {
        document.getElementById('edit_id').value      = btn.getAttribute('data-id');
        document.getElementById('edit_code').value    = btn.getAttribute('data-code');
        document.getElementById('edit_name').value    = btn.getAttribute('data-name');
        document.getElementById('edit_credits').value = btn.getAttribute('data-credits');

        // Set semester dropdown
        const semSel = document.getElementById('edit_semester');
        const semester = btn.getAttribute('data-semester');
        for (let opt of semSel.options) {
            opt.selected = (opt.value === semester);
        }

        // Set teacher dropdown
        const teacherSel = document.getElementById('edit_teacher');
        const teacherId  = btn.getAttribute('data-teacher');
        for (let opt of teacherSel.options) {
            opt.selected = (opt.value === teacherId);
        }

        new bootstrap.Modal(document.getElementById('editSubjectModal')).show();
    }
</script>
@endpush
