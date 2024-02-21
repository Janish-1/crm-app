@extends('layouts.quizlayout')

@section('content')
<section id="register">
    <div class="login-dark">
        <form method="post" action="{{ route('register') }}">
            @csrf
            <h2 class="sr-only">Registration Form</h2>
            <div class="illustration"><i class="icon ion-ios-person-add"></i></div>
            
            <!-- Name Field -->
            <div class="form-group">
                <input class="form-control" type="text" name="name" placeholder="Name" value="{{ old('name') }}">
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Password">
            </div>

            <!-- Confirm Password Field -->
            <div class="form-group">
                <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password">
            </div>

            <!-- Role Field -->
            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" name="role" id="role">
                    <option value="guest" selected>Guest</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="director">Director</option>
                    <!-- Add other role options as needed -->
                </select>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">Register</button>
            </div>

            <!-- Already have an account? Link -->
            <a href="{{ url('/') }}" class="forgot">Already have an account? Log In</a>
        </form>
    </div>
</section>
@endsection
