@extends('layouts.quizlayout')

@section('content')
<section id="login">
    <div class="login-dark">
        <form method="post" action="{{route('login')}}">
            @csrf
            <h2 class="sr-only">Login Form</h2>
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <div class="form-group"><input class="form-control" type="text" name="useroremail" placeholder="Email or Username"></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password">
            </div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Log In</button></div><a
                href="#" class="forgot">Forgot your email or password?</a>
            <!-- Add the "Register" button -->
            <div class="form-group">
                <a href="{{ route('registerpage') }}" class="btn btn-secondary btn-block">Register</a>
            </div>
        </form>
    </div>
</section>
@endsection