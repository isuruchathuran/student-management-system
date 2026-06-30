@extends('app')

@push('page_title', 'Edit Student')

@section('content')
<div class="fade-in-up">

    <div class="page-header">
        <div class="breadcrumb-custom mb-1">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            <a href="{{ route('students.index') }}">Students</a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            Edit Student
        </div>
        <h1><i class="fa-solid fa-pen me-2" style="color:var(--accent);font-size:1.3rem;"></i>Edit Student</h1>
        <p>Update student record for <strong>{{ $student->reg_No }}</strong></p>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-user me-2" style="color:var(--accent);"></i>Student Details</h5>
                    <a href="{{ route('students.index') }}" class="btn-accent" style="background:var(--bg-tertiary);color:var(--text-secondary);padding:7px 16px;font-size:0.8rem;">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="card-dark-body">
                    <form action="{{ route('students.update') }}" method="post" class="form-dark">
                        @csrf
                        <input type="hidden" name="id" value="{{ $student->id }}">

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;border-radius:10px;">
                                <strong><i class="fa-solid fa-circle-exclamation me-2"></i>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Registration No</label>
                                <input type="text" name="reg_No" class="form-control" value="{{ $student->reg_No }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       value="{{ old('name', $student->Name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       value="{{ old('email', $student->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                       value="{{ old('phone', $student->phone) }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="bod" class="form-control {{ $errors->has('bod') ? 'is-invalid' : '' }}"
                                       value="{{ old('bod', $student->date_of_birth) }}" required>
                                @error('bod')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                       value="{{ $student->password }}" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                       value="{{ old('address', $student->address) }}" required>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn-accent">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                                </button>
                                <a href="{{ route('students.index') }}" class="btn-accent" style="background:var(--bg-tertiary);color:var(--text-secondary);">
                                    <i class="fa-solid fa-xmark me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
