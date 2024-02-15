<!-- resources/views/quiz.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Quiz</h1>
    <p>Category: {{ $category }}</p>

    <form action="/submit-quiz/{{$category}}" method="post">
        @csrf

        @foreach ($questions as $question)
            <div>
                <p>{{ $question->question }}</p>

                @foreach (json_decode($question->options) as $option)
                    <label>
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}">
                        {{ $option }}
                    </label><br>
                @endforeach
            </div>
        @endforeach

        <button type="submit">Submit Quiz</button>
    </form>
@endsection
