<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use App\Models\Administrator;
use Illuminate\Http\Request;
use App\Http\Requests\UtilisatorRequest;

use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         //importer les donnéés de la BD
         $administrators= Administrator::all();
         //afficher les données sous format JSON
         return response()->json($administrators);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UtilisatorRequest $request)
    {
         // Création d'un validateur pour vérifier l'unicité de l'email et du téléphone
         $validator = Validator::make($request->all(), [
             'email' => ['required', 'email', Rule::unique('administrators', 'email')],
             'telephone' => ['required', Rule::unique('administrators', 'telephone')],
         ]);
        // Validation des données
        $validatedData = $request->validated();

        // Vérification de la présence du fichier photo dans la requête
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            // Renommer le fichier
            $photoName = time() . '_' . $photo->getClientOriginalName();
            // Déplacer le fichier dans le dossier public
            $photo->move(public_path('photos'), $photoName);
            // Ajouter le nom du fichier aux données validées
            $request['photo'] = $photoName;
        }

        // Créer un nouvel administrateur en utilisant le tableau $request
        $administrator = Administrator::create($request->validated());

        // Retourner la réponse avec le nouvel administrateur créé
        return response()->json($administrator, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         //chercher l utilisateur dans la BD avec id
         $administrator= Administrator::findOrFail($id);

         //retouner la resultat sous format JSON
         return response()->json($administrator);

    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UtilisatorRequest $request, $id)
    {
       // Récupérer l'adminà mettre à jour
    $administrator = Administrator::findOrFail($id);

    // Valider les données en utilisant la classe UtilisatorRequest
    $validatedData = $request->validated();

    // Mettre à jour les données de l'admin
    $administrator->update($validatedData);

    // Retourner la réponse avec l'admin mis à jour
    return response()->json($administrator);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $administrator= Administrator::findOrFail($id);
        $administrator->delete();
        return response()->json(null,204);
    }
}
