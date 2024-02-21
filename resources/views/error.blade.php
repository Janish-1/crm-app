<!-- resources/views/fail.blade.php -->
@extends('layouts.quizlayout')

@section('content')
    <h1>Oops! Something Went Wrong</h1>
    <p>Website is not working! Please wait for our fixers!</p>
    <p>{{ $message }}</p>
@endsection
