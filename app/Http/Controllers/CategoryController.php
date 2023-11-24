<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
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

        // Récupérer l'Category existant

        $category = Category::findOrFail($id);


        // Validate the data in the request

        $validator = Validator::make($request->all(), [

            'name_categ' => 'sometimes|string',

            'photo' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

        ]);


        if ($validator->fails()) {

            //return response()->json(['errors' => $validator->errors()], 400);
            //return ($id);
            return($request->photo);
        }


        // Update the data of the Category

        if ($request->has('name_categ')) {

            $category->name_categ = $request->input('name_categ');

        }


        // Handle the photo if it's present in the request

        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');


            // Delete the old photo file (if it exists)

            if ($category->photo) {

                $oldPhotoPath = public_path('images') . '/' . basename($category->photo);

                if (File::exists($oldPhotoPath)) {

                    File::delete($oldPhotoPath);

                }

            }


            if ($photo->isValid()) {

                $photoName = time() . '_' . $photo->getClientOriginalName();

                $photo->move(public_path('images'), $photoName);

                $category->photo = URL::to('images/' . $photoName);

            }

        }


        $category->save();


        // Retourner la réponse avec l'Category mis à jour

        return response()->json($category);

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
