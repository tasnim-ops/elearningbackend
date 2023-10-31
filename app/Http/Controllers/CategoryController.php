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
    {var_dump($request->name_categ);
        try {
            // Récupérer toutes les données de la requête
            $inputData = $request->all();

            // Valider les données de la requête
            $validator = Validator::make($inputData, [
                'name_categ' => 'sometimes|required|string|unique:categories,name_categ,' . $id,
                'photo' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            ]);

            // Vérifier si la validation a échoué
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                // Trouver la catégorie à mettre à jour
                $category = Category::find($id);

                // Vérifier si la catégorie existe
                if (!$category) {
                    return response()->json(['error' => 'Catégorie non trouvée'], 404);
                }

                // Mettre à jour le champ "name_categ" si présent dans la requête
                if ($request->has('name_categ')) {
                    $category->name_categ = $request->input('name_categ');
                }

                // Mettre à jour le champ "photo" si un nouveau fichier est téléchargé
                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    if ($photo->getError() !== UPLOAD_ERR_OK) {
                        return response()->json(['error' => 'Erreur de téléchargement de fichier'], 400);
                    }
                    $photoName = time() . '_' . $photo->getClientOriginalName();
                    $photo->move(public_path('images'), $photoName);
                    $photoUrl = URL::to('images/' . $photoName);
                    var_dump('url',$photoUrl);
                    $category->photo = $photoUrl;
                }

                // Utiliser la fonction update pour mettre à jour la catégorie
                $category->update([
                    'name_categ' => $category->name_categ,
                    'photo' => $category->photo,
                ]);

                // Retourner la catégorie mise à jour en réponse
                return response()->json($category, 200);
            }
        } catch (\Exception $e) {
            // Afficher le message d'erreur
            var_dump($e->getMessage());

            // Gérer les erreurs et retourner une réponse d'erreur
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
