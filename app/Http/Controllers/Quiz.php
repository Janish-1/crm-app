<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Question;
use Illuminate\Http\Request;

class Quiz extends Controller
{
    public function openQuiz(Request $request)
    {
        $category = (string) $request->category;
        $testid = (string) $request->testid; // Get testid from the URL

        // Redirect to fail page if testid is not available
        if (empty($testid)) {
            return redirect()->route('error', ['message' => "No TestID"]);
        }

        $checkifdone = Career::where('testid', $testid)->where('teststatus', 'pending')->first();

        if (!$checkifdone) {
            return redirect()->route('error', ['message' => "Test Already Done"]);
        }

        // Fetch questions for the given category
        $questions = Question::where('category', $category)->inRandomOrder()->take(15)->get();

        // Check if questions are empty
        if ($questions->isEmpty()) {
            return redirect()->route('error')->with('message', "No Questions Available for {$category}");
        }

        return view('quiz', compact('questions', 'category', 'testid'));
    }

    public function submitQuiz(Request $request, $category)
    {
        try {
            $answers = $request->input('answers');
            $testid = (string) $request->testid; // Get testid from the URL

            // Check if $answers is an array before using count
            $totalQuestions = is_array($answers) ? count($answers) : 0;
            $correctCount = 0;

            // Loop through submitted answers and check correctness
            foreach ($answers as $questionId => $userAnswer) {
                $question = Question::find($questionId);

                if ($userAnswer === $question->correct_answer) {
                    $correctCount++;
                }
            }

            // Calculate the percentage of correct answers
            $percentageCorrect = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;

            // Assuming you have a Test model with 'status' field
            $test = Career::where('category', $category)
                ->where('testid', $testid)
                ->first();

            // Redirect based on the percentage
            if ($percentageCorrect >= 75) {
                $test->teststatus = 'pass';
                $test->save();
                return redirect()->route('passpage', ['category' => $category]);
            } else {
                $test->teststatus = 'fail';
                $test->save();
                return redirect()->route('failpage', ['category' => $category]);
            }
        } catch (\Exception $e) {
            return redirect()->route('error')->with('message', 'An error occurred during the quiz submission.');
        }
    }

    public function passfunction(Request $request)
    {
        $category = $request->category;
        $testid = $request->testid;

        // Assuming you have a Test model with 'status' field
        $test = Career::where('category', $category)
            ->where('testid', $testid)
            ->first();

        $teststatus = $test->teststatus;

        if ($test && $teststatus == 'pending') {
            $test->teststatus = 'pass';
            $test->save();
        }

        return redirect()->route('passpage');
    }

    public function failfunction(Request $request)
    {
        $category = $request->category;
        $testid = $request->testid;

        // Assuming you have a Test model with 'status' field
        $test = Career::where('category', $category)
            ->where('testid', $testid)
            ->first();

        $teststatus = $test->teststatus;

        if ($test && $teststatus == 'pending') {
            $test->teststatus = 'fail';
            $test->save();
        }

        return redirect()->route('failpage');
    }

    public function passpage(){
        return view('passpage');
    }

    public function failpage(){
        return view('failpage');
    }

    public function errorPage()
    {
        $errorMessage = session('message', 'An error occurred.'); // Default message if 'message' is not set in session

        return view('error', ['message' => $errorMessage]);
    }
}
