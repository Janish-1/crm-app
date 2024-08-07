@extends('layouts.quizlayout')

@section('content')
<section id="quiz">
    <div id="quiz-container" class="quiz-container">
        <h1>Quiz</h1>
        <p>Category: {{ $category }}</p>
        <p>Test ID: <span id="test-id">{{ $testid }}</span></p>
        <div id="timer" class="timer"></div><a href="#" id="show-rules">View Rules</a>

        <form action="{{ route('submit-quiz', ['category' => $category, 'testid' => $testid]) }}" method="post">
            @csrf

            @foreach ($questions as $index => $question)
            <div class="question">
                <h3>{{ $index + 1 }}. {{ $question->question }}</h3>

                @foreach (json_decode($question->options, true) ?? [] as $option)
                <label class="option">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}">
                    {{ $option }}
                </label>
                @endforeach
            </div>
            @endforeach

            <button type="button" id="submit-button">Submit Quiz</button>
        </form>
    </div>
    <div id="rules-popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeRulesPopup()">&times;</span>
            <h2>Rules</h2>
            <p>1. Answer all questions within the given time.</p>
            <p>2. Click the submit button to end the quiz.</p>
            <p>3. Choose the most appropriate answer for each question.</p>
            <p>4. Do not use external resources or assistance.</p>
            <p>5. If you move away from the screen, you may fail the test.</p>
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script>
    var isTestStarted = false;

    // Attach load event to the window
    window.addEventListener('load', function () {
        // Show a confirmation dialog before starting the test
        var startTestConfirmation = window.confirm('Are you sure you want to start the test?');

        if (startTestConfirmation) {
            isTestStarted = true;

            // You can continue with your existing logic to start the test
            // For example, you can remove the warning listener if the test has started
            window.removeEventListener('beforeunload', beforeUnloadWarning);

            // Start the timer or any other logic you have
            startTimer();

        } else {
            // If the user chose not to start the test, keep the warning on leaving the page
            window.location.href = 'http://crm.ramo.co.in/careers/teststartpage.php'
        }
    });

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

    // Warn the user before leaving or reloading the page
    window.addEventListener('beforeunload', function (e) {
        var confirmationMessage = 'Are you sure you want to leave? Your test progress will be lost.';

        (e || window.event).returnValue = confirmationMessage; // Standard
        return confirmationMessage; // For some older browsers
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
    // Add this to your existing JavaScript
    document.getElementById('show-rules').addEventListener('click', function () {
        document.getElementById('rules-popup').style.display = 'block';
    });

    function closeRulesPopup() {
        document.getElementById('rules-popup').style.display = 'none';
    }
</script>
@endsection