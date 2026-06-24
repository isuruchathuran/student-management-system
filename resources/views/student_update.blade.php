@extends('app')

@push('title')
    Student Update
@endpush

@push('colour')
    bg-danger
@endpush

@push('nav-brand')
    LMS
@endpush

@push('css')

    <style>
        body{
            background: #f4f7fc;
        }

        h1{
            text-align: center;
            font-weight: 700;
            color: red;
            margin-bottom: 25px;
        }

        form{
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .form-group{
            margin-bottom: 15px;
        }

        .form-group label{
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .form-control{
            border-radius: 10px;
            padding: 10px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }

        .form-control:focus{
            border-color: #198754;
            box-shadow: 0 0 10px rgba(25,135,84,0.2);
        }

        .btn-success{
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            font-size: 16px;
        }

        .btn-primary{
            width: 100%;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            margin-top: 10px;
        }

        .col-4{
            padding-top: 10px;
        }

        .side-card{
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .side-card h4{
            color: #198754;
            margin-bottom: 15px;
            font-weight: 700;
        }
    </style>

@endpush

@section('content')

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mt-3">✏ Student Details Update</h1>
            </div>
        </div>


        <div class="row justify-content-center">

            <div class="col-lg-8">

                <form action="{{route('student.update')}}" method="post">
                    @csrf

                    <input type="hidden" name="id" value="{{ $student->id }}">

                    <div class="form-group">
                        <label>Register No :</label>
                        <input type="text"
                               name="reg_No"
                               class="form-control"
                               value="{{$student->reg_No}}"
                               readonly
                               style="background-color: #e9ecef; cursor: not-allowed; font-weight: 600; color: #495057;">
                        <small class="text-muted">⚠ Registration number is auto-generated and cannot be changed.</small>
                    </div>

                    <div class="form-group">
                        <label>Full Name :</label>
                        <input type="text"
                               name="name"
                               value="{{$student->Name}}"
                               class="form-control"
                               placeholder="Enter Your Full Name"
                               required>
                    </div>

                    <div class="form-group">
                        <label>E-mail :</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{$student->email}}"
                               placeholder="Enter Your Email Address"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Phone No :</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{$student->phone}}"
                               placeholder="Enter Your Phone Number"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth :</label>
                        <input type="date"
                               name="bod"
                               class="form-control"
                               value="{{$student->date_of_birth}}"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Password :</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               value="{{$student->password}}"
                               placeholder="Enter Your Password"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Address :</label>
                        <input type="text"
                               name="address"
                               class="form-control"
                               value="{{$student->address}}"
                               placeholder="Enter Your Address"
                               required>
                    </div>

                    <button type="submit" class="btn btn-danger mt-3 w-100">
                        Save Updated Details
                    </button>

                </form>

            </div>

            <div class="col-lg-4">

                    <a href="{{route('student.list')}}"
                       class="btn btn-primary">
                        View Student List
                    </a>

            </div>

        </div>


    </div>

@endsection

@push('script')

    <script>

    </script>

@endpush
