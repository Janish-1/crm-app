<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class Quiz extends Controller
{
    public function openQuiz(Request $request)
    {
        $category = $request->category;
        $testid = $request->testid; // Get testid from the URL
        $questions = Question::where('category', $category)->get();

        // Redirect to fail page if testid is not available
        if (!$testid) {
            return redirect()->route('fail', ['category' => $category]);
        }

        return view('quiz', compact('questions', 'category', 'testid'));
    }
        
    public function submitQuiz(Request $request, $category)
    {
        $answers = $request->input('answers');
        $totalQuestions = count($answers);
        $correctCount = 0;
    
        // Loop through submitted answers and check correctness
        foreach ($answers as $questionId => $userAnswer) {
            $question = Question::find($questionId);
    
            if ($userAnswer === $question->correct_answer) {
                $correctCount++;
            }
        }
    
        // Calculate the percentage of correct answers
        $percentageCorrect = ($correctCount / $totalQuestions) * 100;
    
        // Redirect based on the percentage
        if ($percentageCorrect >= 75) {
            return redirect()->route('pass', ['category' => $category]);
        } else {
            return redirect()->route('fail', ['category' => $category]);
        }
    }
    
    public function passPage()
    {
        return view('pass');
    }

    public function failPage()
    {
        return view('fail');
    }
}
