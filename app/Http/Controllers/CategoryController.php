<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories=Category::all();
        return response()->json($categories);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData=$request->validate([
            'name_categ'=>'required|string|unique:categories,name_categ',
        ]);
        $categorie=Category::create($validatedData);
        return response()->json($categorie,201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $categorie=Category::findOrFail($id);
        return response()->json($categorie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
            // Valider les données
    $validatedData = $request->validate([
        'name_categ'=>'required|string',

    ]);

    // Chercher la categorie
    $categorie = Category::findOrFail($id);

    // Mettre à jour les données du categorie
    $categorie->update($validatedData);

    // Retourner la réponse avec la categorie mis à jour
    return response()->json($categorie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categorie=Category::findOrFail($id);
        $categorie->delete();
        return response()->json(null,204);
    }
}
