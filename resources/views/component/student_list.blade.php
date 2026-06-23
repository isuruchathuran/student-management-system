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
                            <a href="{{ route('student.edit', $student->id) }}"
                               class="btn btn-primary btn-sm"
                               onclick="openEditModal(event, this)">
                                ✏
                            </a>

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
    </script>
@endpush
