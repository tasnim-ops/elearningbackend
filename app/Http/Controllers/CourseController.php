<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Validator;

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


     public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'title' => 'required|string',
             'course_description' => 'string|max:300',
             'category_id' => 'required|exists:categories,id',
             'teacher_id' => 'required|exists:teachers,id',
             'price' => 'required|numeric',
             'documents' => 'array',
             'documents.*' => 'file|max:20480', // Set the maximum file size (in KB) to 20MB (20480 KB)
         ]);

         if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['pdf', 'mp4', 'jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'ppt', 'pptx', 'txt'];

                if (in_array($extension, $allowedExtensions)) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('public/documents', $filename);
                    $documentUrl = url('/storage/documents/' . $filename); // Generate the full URL
                    $documents[] = [
                        'filename' => $file->getClientOriginalName(),
                        'type' => $extension,
                        'path' => $documentUrl,
                    ];
                }
            }
        }



         $courseData = $request->except('documents');
         $course = Course::create($courseData);

         if (!empty($documents)) {
             $course->documents = $documents;
             $course->save();
         }

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
    public function update(Request $request, $id)
    {

        // Debug 1: Print the ID
        print("ID: " . $id . "\n");

        // Debug 2: Print the request data
        print("Request Data:\n");
        print_r($request->all());
        // Afficher les données validées
        $validatedData = $request->validate([
            'title' => 'required|string',
            'course_description' => 'string|max:300',
            'category_id' => 'required|exists:categories,id',
            'teacher_id' => 'required|exists:teachers,id',
            'price' => 'required|numeric',
            'documents' => 'array',
            'documents.*' => 'file|max:20480', // Set the maximum file size (in KB) to 20MB (20480 KB)
        ]);


        dd($validatedData); // Affiche les données validées et arrête l'exécution ici

        $course = Course::findOrFail($id);

        // Mise à jour des propriétés du cours avec les données validées
        $course->update($validatedData);

        // Traitement des documents (même logique que dans la fonction store)
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['pdf', 'mp4', 'jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'ppt', 'pptx', 'txt'];

                if (in_array($extension, $allowedExtensions)) {
                    $path = $file->store('public/documents');
                    $documents[] = [
                        'filename' => $file->getClientOriginalName(),
                        'type' => $extension,
                        'path' => $path,
                    ];
                }
            }
        }

        dd($documents); // Affiche les documents traités et arrête l'exécution ici

        // Mettre à jour les informations des documents dans la base de données, si nécessaire
        if (!empty($documents)) {
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            print("from not empty doc");
            $course->documents = $documents;
            $course->save();
        }

        return response()->json($course, 200);
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
