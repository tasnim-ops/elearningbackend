<?php

namespace App\Http\Controllers;

use App\Models\NewClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class NewClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    $request->validate([
        'name' => 'required',
        'file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $file = $request->file('file');
    $fileName = time() . '_' . $file->getClientOriginalName();
    $file->storeAs('public/images', $fileName);

    $newClass = NewClass::create([
        'name' => $request->input('name'),
        'path' => 'images/' . $fileName,
    ]);

    return response()->json($newClass, 201);
}

    /**
     * Display the specified resource.
     */
    public function show(NewClass $newClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewClass $newClass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    var_dump($id);
    var_dump($request->name);

    try {
        $inputData = $request->all();
        $validator = Validator::make($inputData, [
            'name' => 'sometimes|required|string',
            'file' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            $newClass = NewClass::findOrFail($id);

            if (!$newClass) {
                return response()->json(['error' => 'Class not found'], 404);
            }

            if ($request->has('name')) {
                $newClass->name = $request->input('name');
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                var_dump('File Uploaded: ' . $file->getClientOriginalName()); // Ligne de débogage

                if ($file->getError() !== UPLOAD_ERR_OK) {
                    return response()->json(['error' => 'File upload error'], 400);
                }
                $fileName = time() . '_' . $file->getClientOriginalName();

                if ($newClass->path) {
                    Storage::delete('public/' . $newClass->path);
                }

                $file->storeAs('public/images', $fileName);

                var_dump('File Stored: ' . $fileName); // Ligne de débogage

                $newClass->path = 'images/' . $fileName;
            }

            $newClass->save();

            return response()->json(['message' => 'Class updated successfully']);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred'], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewClass $newClass)
    {
        //
    }
}
