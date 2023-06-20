<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        return response()->json($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        $validatedData = $request->validated();
        if ($request->hasFile('documents')) {
            $documents = [];

            foreach ($request->file('documents') as $file) {
                $path = $file->store('documents');
                $documents[] = [
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                ];
            }

            $validatedData['documents'] = $documents;
        }

        $course = Course::create($validatedData);

        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Search for the course in the database with the given ID
        $course = Course::findOrFail($id);

        // Return the result as JSON
        return response()->json($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, $id)
    {
        $validatedData = $request->validated();
        $course = Course::findOrFail($id);
        $course->update($validatedData);
        return response()->json($course, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(null, 204);
    }
}
