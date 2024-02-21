<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CareerController extends Controller
{
    private function random_strings($length_of_string)
    {

        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shuffle the $str_result and returns substring
        // of specified length
        return substr(
            str_shuffle($str_result),
            0,
            $length_of_string
        );
    }

    public function createCareer(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required',
            'mail' => 'required|email',
            'contact' => 'required',
            'category' => 'required',
            'experience' => 'required',
            'cv' => 'required|mimes:pdf,doc,docx',
        ]);

        // Check if any data is provided
        $requestData = $request->only(['name', 'mail', 'contact', 'category', 'experience']);
        if (empty(array_filter($requestData))) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided'
            ], 400);
        }

        // Upload the CV file
        $cvPath = $request->file('cv')->store('public/cv_files');

        $testid = $this->random_strings(200);

        // Create a new Career model instance
        $career = new Career([
            'name' => $requestData['name'],
            'mail' => $requestData['mail'],
            'contactnumber' => $requestData['contact'],
            'category' => $requestData['category'],
            'experience' => $requestData['experience'],
            'cv' => $cvPath,
            'testid' => $testid,
        ]);

        // Save the Career model instance to the database
        if (!$career->save()) {
            // Handle the case where saving fails
            return response()->json([
                'success' => false,
                'message' => 'Failed to create career'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Career created successfully',
            'career' => $career
        ], 200);
    }

    public function readCareer($id)
    {
        $career = Career::find($id);

        if ($career) {
            return response()->json([
                'success' => true,
                'career' => $career
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Career not found'
            ], 404);
        }
    }

    public function updateCareer(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required',
            'mail' => 'required|email',
            'contact' => 'required',
            'category' => 'required',
            'experience' => 'required',
            'cv' => 'mimes:pdf,doc,docx',
        ]);

        // Find the Career model instance by ID
        $career = Career::find($id);

        if (!$career) {
            // Handle the case where the career is not found
            return response()->json([
                'success' => false,
                'message' => 'Career not found'
            ], 404);
        }

        // Update the Career model instance with the new data
        $career->update([
            'name' => $request->input('name'),
            'mail' => $request->input('mail'),
            'contactnumber' => $request->input('contact'),
            'category' => $request->input('category'),
            'experience' => $request->input('experience'),
        ]);

        // Update the CV file if a new file is provided
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cv_files');
            $career->update(['cv' => $cvPath]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Career updated successfully',
            'career' => $career
        ], 200);
    }

    public function deleteCareer($id)
    {
        // Find the Career model instance by ID
        $career = Career::find($id);

        if (!$career) {
            // Handle the case where the career is not found
            return response()->json([
                'success' => false,
                'message' => 'Career not found'
            ], 404);
        }

        // Delete the CV file
        if ($career->cv) {
            Storage::delete($career->cv);
        }

        // Delete the Career model instance
        if (!$career->delete()) {
            // Handle the case where deletion fails
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete career'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Career deleted successfully'
        ], 200);
    }

    public function readAllCareers()
    {
        // Retrieve all career entries
        $careers = Career::all();

        // Return the response
        return response()->json([
            'success' => true,
            'careers' => $careers
        ], 200);
    }

    public function redirectToTest(Request $request)
    {
        // Get the provided test ID from the request
        $testid = $request->testid;

        // Check if the test ID is provided
        if (!$testid) {
            return response()->json([
                'success' => false,
                'message' => 'Test ID is required'
            ], 400);
        }

        // Check if the test ID exists and the status is pending in the careers database
        $career = Career::where('testid', $testid)->where('teststatus', 'pending')->first();
        $category = $career->category;

        if ($career) {
            // Return a 200 response with a success message and the test ID
            return response()->json([
                'success' => true,
                'message' => 'Successfully redirected to test page',
                'testid' => $testid,
                'redirectUrl' => "http://127.0.0.1:8000/quiz/{$category}/{$testid}"
            ], 200);
        }

        // Handle the case where the test ID is invalid or the status is not pending
        return response()->json([
            'success' => false,
            'message' => 'Invalid test ID or test status is not pending'
        ], 400);
    }
}
