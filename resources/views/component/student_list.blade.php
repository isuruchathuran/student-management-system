@extends('app')
@push('title')
    Studenet List
@endpush

@push('colour')
    bg-primary
@endpush

@push('nav-brand')
    LMS
@endpush

@push('css')
    <style>

    </style>
@endpush

@section('content')

    <div class="container ">
        <div class="row align-items-center">

            <div class="col-6">
                <h1 class="mt-5"> 📚 Student List</h1>
            </div>

            <div class="col-6 text-end mt-5">
                <a href="{{route('student.dashboard')}}" class="btn btn-success">
                    + Register Student
                </a>
            </div>

        </div>

        <form action="{{ route('student.list') }}" method="GET">
            <div class="input-group mb-3">
                <input type="text"
                       id="searchInput"
                       name="search"
                       class="form-control"
                       placeholder="Search by Reg No, Name, Email or Phone...">
            </div>
        </form>


        <div class="col-12">
            <table class="table">
                <thead class="table-dark">
                <tr style="text-align: center;">
                    <th scope="col">Reg No</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">BOD</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone No</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($students as $student)
                    <tr style="text-align: center;">
                        <td>{{$student->reg_No}}</td>
                        <td>{{$student->Name}}</td>
                        <td>{{$student->date_of_birth}}</td>
                        <td>{{$student->email}}</td>
                        <td>{{$student->phone}}</td>

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
                @endforeach

                </tbody>
            </table>
        </div>

    </div>
    </div>

    <!-- Update Modal -->
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
                            <label>Register No :</label>
                            <input type="text" name="reg_No" id="update_reg_No" class="form-control" required>
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

        {{-- ✅ Register / Delete success alert --}}
        @if(session('success'))
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: '{{ session("title") ?? "Success!" }}',
                text: '{{ session("success") }}',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });
        @endif

        {{-- Live Search --}}
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

        function openEditModal(btn) {
            document.getElementById('update_id').value = btn.getAttribute('data-id');
            document.getElementById('update_reg_No').value = btn.getAttribute('data-reg');
            document.getElementById('update_name').value = btn.getAttribute('data-name');
            document.getElementById('update_email').value = btn.getAttribute('data-email');
            document.getElementById('update_phone').value = btn.getAttribute('data-phone');
            document.getElementById('update_bod').value = btn.getAttribute('data-bod');
            document.getElementById('update_password').value = btn.getAttribute('data-password');
            document.getElementById('update_address').value = btn.getAttribute('data-address');

            var updateModal = new bootstrap.Modal(document.getElementById('updateStudentModal'));
            updateModal.show();
        }
    </script>
@endpush
