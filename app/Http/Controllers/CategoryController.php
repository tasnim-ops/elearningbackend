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
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
            // Valider les données
    $validatedData = $request->validated();

    // Chercher l'utilisateur
    $administrator = Administrator::findOrFail($id);

    // Mettre à jour les données de l'utilisateur
    $administrator->update($validatedData);

    // Retourner la réponse avec l'utilisateur mis à jour
    return response()->json($administrator);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categorie=Category::findOrFail($id);
        $categorie->delete();
        return responde()->json(null,204);
    }
}
