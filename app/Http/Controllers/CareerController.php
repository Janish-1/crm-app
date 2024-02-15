<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CareerController extends Controller
{
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
                'message' => 'No data provided'], 400);
        }
    
        // Upload the CV file
        $cvPath = $request->file('cv')->store('cv_files');
    
        // Create a new Career model instance
        $career = new Career([
            'name' => $requestData['name'],
            'mail' => $requestData['mail'],
            'contactnumber' => $requestData['contact'],
            'category' => $requestData['category'],
            'experience' => $requestData['experience'],
            'cv' => $cvPath,
        ]);
    
        // Save the Career model instance to the database
        if (!$career->save()) {
            // Handle the case where saving fails
            return response()->json([
                'success' => false,
                'message' => 'Failed to create career'], 500);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Career created successfully', 'career' => $career], 200);
    }
    
    public function readCareer($id)
    {
        $career = Career::find($id);

        if ($career) {
            return response()->json([
                'success' => true,
                'career' => $career], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Career not found'], 404);
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
                'message' => 'Career not found'], 404);
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
            'message' => 'Career updated successfully', 'career' => $career], 200);
    }

    public function deleteCareer($id)
    {
        // Find the Career model instance by ID
        $career = Career::find($id);

        if (!$career) {
            // Handle the case where the career is not found
            return response()->json([
                'success' => false,
                'message' => 'Career not found'], 404);
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
                'message' => 'Failed to delete career'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Career deleted successfully'], 200);
    }

    public function readAllCareers()
    {
        // Retrieve all career entries
        $careers = Career::all();

        // Return the response
        return response()->json([
            'success' => true,
            'careers' => $careers], 200);
    }
}
