@extends('app')
@push('title')
    Student List
@endpush

@push('colour')
    bg-primary
@endpush

@push('nav-brand')
    LMS
@endpush

@push('css')
    <style>
        .table-wrapper {
            overflow-x: auto;
        }
        .address-cell {
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .import-errors-list {
            max-height: 200px;
            overflow-y: auto;
            font-size: 0.85rem;
        }
    </style>
@endpush

@section('content')

    <div class="container">
        <div class="row align-items-center">

            <div class="col-6">
                <h1 class="mt-5"> 📚 Student List</h1>
            </div>

            <div class="col-6 text-end mt-5 d-flex gap-2 justify-content-end flex-wrap">
                {{-- Export PDF — passes current search filter --}}
                <a href="{{ route('student.export.pdf') }}{{ request('search') ? '?search='.urlencode(request('search')) : '' }}"
                   class="btn btn-danger">
                    📄 Export PDF
                </a>

                {{-- Export Excel — passes current search filter --}}
                <a href="{{ route('student.export.excel') }}{{ request('search') ? '?search='.urlencode(request('search')) : '' }}"
                   class="btn btn-success">
                    📊 Export Excel
                </a>

                {{-- Import Excel button --}}
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                    📥 Import Excel
                </button>

                {{-- Register Student — now opens a modal --}}
                <button type="button" class="btn btn-primary" id="openRegisterModalBtn"
                        data-bs-toggle="modal" data-bs-target="#registerStudentModal">
                    + Register Student
                </button>
            </div>

        </div>



        <form action="{{ route('student.list') }}" method="GET">
            <div class="input-group mb-3 mt-3">
                <input type="text"
                       id="searchInput"
                       name="search"
                       class="form-control"
                       value="{{ request('search') }}"
                       placeholder="Search by Reg No, Name, Email or Phone...">
            </div>
        </form>


        <div class="col-12 table-wrapper">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr style="text-align: center;">
                    <th scope="col">Reg No</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Birthday</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone No</th>
                    <th scope="col">Address</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>

                @forelse($students as $student)
                    <tr style="text-align: center;">
                        <td>{{$student->reg_No}}</td>
                        <td>{{$student->Name}}</td>
                        <td>{{$student->date_of_birth}}</td>
                        <td>{{$student->email}}</td>
                        <td>{{$student->phone}}</td>
                        <td class="address-cell" title="{{$student->address}}">{{$student->address}}</td>

                        <td>
                            <button type="button"
                               class="btn btn-primary btn-sm"
                               onclick="openEditModal(this)"
                               data-id="{{$student->id}}"
                               data-reg="{{$student->reg_No}}"
                               data-name="{{$student->Name}}"
                               data-email="{{$student->email}}"
                               data-phone="{{$student->phone}}"
                               data-bod="{{$student->date_of_birth}}"
                               data-password="{{$student->password}}"
                               data-address="{{$student->address}}">
                                ✏
                            </button>

                            <a href="{{ route('student.delete', $student->id) }}"
                               class="btn btn-danger btn-sm"
                               onclick="return confirmDelete(this)">
                                🗑
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No student records found.</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

    </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Register Student Modal                                        --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="registerStudentModal" tabindex="-1"
         aria-labelledby="registerStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="registerStudentModalLabel">🎓 Register New Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('student.store')}}" method="post" id="registerStudentForm">
                    @csrf
                    <div class="modal-body">

                        {{-- Validation errors (shown when redirected back with errors) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>⚠ Please fix the following errors:</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Registration No:</label>
                                    <input type="text"
                                           id="register_reg_no_display"
                                           class="form-control"
                                           readonly
                                           style="background-color:#e9ecef;cursor:not-allowed;font-weight:600;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Full Name: <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="name"
                                           id="register_name"
                                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                           value="{{ old('name') }}"
                                           placeholder="Enter full name"
                                           required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">E-mail: <span class="text-danger">*</span></label>
                                    <input type="email"
                                           name="email"
                                           id="register_email"
                                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                           value="{{ old('email') }}"
                                           placeholder="Enter email address"
                                           required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Phone No: <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="phone"
                                           id="register_phone"
                                           class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                           value="{{ old('phone') }}"
                                           placeholder="Enter phone number"
                                           required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Date of Birth: <span class="text-danger">*</span></label>
                                    <input type="date"
                                           name="bod"
                                           id="register_bod"
                                           class="form-control {{ $errors->has('bod') ? 'is-invalid' : '' }}"
                                           value="{{ old('bod') }}"
                                           required>
                                    @error('bod')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Password: <span class="text-danger">*</span></label>
                                    <input type="password"
                                           name="password"
                                           id="register_password"
                                           class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                           placeholder="Enter Password"
                                           required>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-semibold">Address: <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="address"
                                   id="register_address"
                                   class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                   value="{{ old('address') }}"
                                   placeholder="Enter address"
                                   required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">🎓 Register Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Import Excel Modal                                            --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="importExcelModal" tabindex="-1"
         aria-labelledby="importExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold" id="importExcelModalLabel">📥 Import Students from Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('student.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">
                            Upload an <strong>.xlsx</strong> or <strong>.xls</strong> file.
                        </p>

                        <div class="form-group mb-3">
                            <input type="file"
                                   name="excel_file"
                                   id="excel_file"
                                   class="form-control"
                                   accept=".xlsx,.xls"
                                   required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning fw-bold">📥 Import Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Update Student Modal (unchanged)                              --}}
    {{-- ============================================================ --}}
    <div class="modal fade" id="updateStudentModal" tabindex="-1" aria-labelledby="updateStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="updateStudentModalLabel">✏ Student Details Update</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('student.update')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="update_id">

                        <div class="form-group mb-3">
                            <label> Register No :</label>
                            <input type="text" name="reg_No" id="update_reg_No" class="form-control"
                                   readonly
                                   style="background-color: #e9ecef; cursor: not-allowed; font-weight: 600; color: #495057;">

                        </div>

                        <div class="form-group mb-3">
                            <label>Full Name :</label>
                            <input type="text" name="name" id="update_name" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>E-mail :</label>
                            <input type="email" name="email" id="update_email" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Phone No :</label>
                            <input type="text" name="phone" id="update_phone" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Date of Birth :</label>
                            <input type="date" name="bod" id="update_bod" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Password :</label>
                            <input type="password" name="password" id="update_password" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Address :</label>
                            <input type="text" name="address" id="update_address" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Updated Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ---- Delete confirmation ----
        function confirmDelete(element) {
            event.preventDefault();
            const deleteUrl = element.getAttribute('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "This student will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
            return false;
        }

        // ---- Success toast (register / update / delete) ----
        @if(session('success'))
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: '{{ session("title") ?? "Success!" }}',
                text: '{{ session("success") }}',
                icon: 'success',
                timer: 2500,
                showConfirmButton: false
            });
        });
        @endif

        // ---- Error toast ----
        @if(session('error'))
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: '{{ session("error_title") ?? "Error!" }}',
                html: '{!! session("error") !!}',
                icon: 'error',
                timer: 4500,
                showConfirmButton: true
            });
        });
        @endif

        // ---- Warning toast ----
        @if(session('warning'))
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: '{{ session("title") ?? "Warning!" }}',
                html: '{!! session("warning") !!}',
                icon: 'warning',
                showConfirmButton: true
            });
        });
        @endif

        // ---- If validation errors exist, re-open the register modal ----
        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            var registerModal = new bootstrap.Modal(document.getElementById('registerStudentModal'));
            registerModal.show();
        });
        @endif

        // ---- Load next Reg No when the register modal is about to open ----
        document.addEventListener('DOMContentLoaded', function () {
            var registerModalEl = document.getElementById('registerStudentModal');

            registerModalEl.addEventListener('show.bs.modal', function () {
                fetch('{{ route("student.next-reg-no") }}')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('register_reg_no_display').value = data.reg_no;
                    })
                    .catch(() => {
                        document.getElementById('register_reg_no_display').value = 'STU???';
                    });
            });
        });

        // ---- Live search ----
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');

            let delay;
            searchInput.addEventListener('keyup', function () {
                clearTimeout(delay);
                delay = setTimeout(function () {
                    const searchValue = searchInput.value;
                    window.location.href = '{{ route("student.list") }}?search=' + encodeURIComponent(searchValue);
                }, 500);
            });
        });

        // ---- Populate the update modal ----
        function openEditModal(btn) {
            document.getElementById('update_id').value      = btn.getAttribute('data-id');
            document.getElementById('update_reg_No').value  = btn.getAttribute('data-reg');
            document.getElementById('update_name').value    = btn.getAttribute('data-name');
            document.getElementById('update_email').value   = btn.getAttribute('data-email');
            document.getElementById('update_phone').value   = btn.getAttribute('data-phone');
            document.getElementById('update_bod').value     = btn.getAttribute('data-bod');
            document.getElementById('update_password').value = btn.getAttribute('data-password');
            document.getElementById('update_address').value = btn.getAttribute('data-address');

            var updateModal = new bootstrap.Modal(document.getElementById('updateStudentModal'));
            updateModal.show();
        }
    </script>
@endpush
