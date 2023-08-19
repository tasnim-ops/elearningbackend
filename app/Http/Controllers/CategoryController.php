<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_categ' => 'required|string|unique:categories,name_categ',
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('images'), $photoName);
            $photoUrl = URL::to('images/' . $photoName);
        } else {
            $photoUrl = null;
        }

        $category = Category::create([
            'name_categ' => $request->input('name_categ'),
            'photo' => $photoUrl,
        ]);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $inputData = $request->all();
            var_dump($inputData); // Affiche les données reçues

            $validator = Validator::make($inputData, [
                'name_categ' => 'required|string|unique:categories,name_categ,' . $id,
                'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                // ... (votre code de mise à jour)
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage()); // Affiche le message d'erreur
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(null, 204);
    }
}
