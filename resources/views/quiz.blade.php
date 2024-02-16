@extends('layouts.app')

@section('content')
    <h1>Quiz</h1>
    <p>{{ $category }}</p>
    <p>{{ $testid }}</p>
    <p id="timer"></p>

    <form id="quiz-form" action="{{ route('submit-quiz', ['category' => $category, 'testid' => $testid]) }}" method="post">
        @csrf

        @foreach ($questions as $question)
            <div>
                <p>{{ $question->question }}</p>

                @foreach (json_decode($question->options, true) ?? [] as $option)
                    <label>
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}">
                        {{ $option }}
                    </label><br>
                @endforeach
            </div>
        @endforeach

        <button type="button" id="submit-button">Submit Quiz</button>
    </form>

    <script>
        var countdown = 600; // 10 minutes in seconds

        var timer = setInterval(function () {
            countdown--;

            if (countdown <= 0) {
                clearInterval(timer);
                submitQuiz('fail'); // Redirect to fail path when the timer reaches zero
            }

            // Update the timer display
            updateTimerDisplay();
        }, 1000);

        // Listen for page visibility change events
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                submitQuiz('fail'); // Redirect to fail path when the page is hidden
            }
        });

        // Attach click event to the submit button
        document.getElementById('submit-button').addEventListener('click', function () {
            submitQuiz('submit-quiz'); // Updated to use 'submit-quiz' as the default path
        });

        function submitQuiz(path) {
            clearInterval(timer);

            // Clear all selected options
            var radioButtons = document.querySelectorAll('input[type="radio"]');
            radioButtons.forEach(function (radioButton) {
                radioButton.checked = false;
            });

            // Construct the path URL with category and testid parameters
            var constructedPath = '/' + path + '/{{ $category }}/{{ $testid }}';

            // Redirect to the constructed path
            window.location.href = constructedPath;
        }

        function updateTimerDisplay() {
            var minutes = Math.floor(countdown / 60);
            var seconds = countdown % 60;

            // Format the time and update the display
            document.getElementById('timer').textContent =
                'Time Remaining: ' + minutes + ' minutes ' + seconds + ' seconds';
        }
    </script>
@endsection
