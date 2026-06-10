@extends('app')
@push('title')
      Studenet Register
@endpush

@push('nav-brand')
    LMS
@endpush

@push('css')
    <style>

    </style>
@endpush

@section('content')

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mt-5">Studenet Registration Form</h1>
            </div>
            <div class="col-6">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Register No :</label>
                        <input type="text" class="form-control" placeholder="Enter Your Register No:" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Full Name :</label>
                        <input type="text" class="form-control" placeholder="Enter Your Full Name:" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">E-mail :</label>
                        <input type="email" class="form-control" placeholder="Enter Your Full Email:" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Phone no :</label>
                        <input type="text" class="form-control" placeholder="Enter Your Phone number:" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">BOD :</label>
                        <input type="date" class="form-control" placeholder="Enter Your Birthday:" required>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">password :</label>
                        <input type="password" class="form-control" placeholder="Enter Your Password:" required>
                    </div>
                    <button type="submit" class="btn btn-success mt-5 w-100">Register</button>
                </form>
            </div>
            <div class="col-6">
                <table class="table">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Reg No</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">BOD</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone No</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>REG 001</td>
                        <td>Isuru</td>
                        <td>2002.02.20</td>
                        <td>isuruchathuran24@gmail.com</td>
                        <td>077 216 5057</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            </div>
        </div>
    </div>

@endsection


@push('script')
    <script>

    </script>
@endpush
