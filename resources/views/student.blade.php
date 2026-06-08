<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap-5.0.2-dist/css/bootstrap.min.css')}}">
</head>
<body>
@include('component.navbar')
<div class="text-center mb-4">
    <h1 class="fw-bold text-primary">Student Registration Form</h1>
    <p class="text-muted">Please fill all required fields</p>
</div>
@include('component.form')
</body>
</html>
