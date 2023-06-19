<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses=Course::all();
        return response()->json($courses);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'course_description' => 'string|max:300',
            'category_id' => 'required|exists:categories,id',
            'teacher_id' => 'required|exists:teachers,id',
            'price' => 'required|numeric',
            'documents' => 'array',
            'documents.*' => 'file|mimes:pdf,mp4|max:2048',
        ]);
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
                //chercher course dans la BD avec id
                $course= Corse::findOrFail($id);

                //retouner la resultat sous format JSON
                return response()->json($course);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'course_description'=>'string:300',
            'category_id' => 'required|exists:categories,id',
            'teacher_id' => 'required|exists:categories,id',
            'price'=>'required|double',
            'documents'=>'mimes:pdf,mp4|max:2048',
        ]);
        $course=Course::findOrFail($id);
        $course->update($validatedData);
        return response()->json($course,201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course=Course::findOrFail($id);
        $course->delete();
        return response()->json(null,204);
    }
}
