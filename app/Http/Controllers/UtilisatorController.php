<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UtilisatorRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Utilisator;
use Illuminate\Validation\Rule;
class UtilisatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //importer les donnéés de la BD
        $utilisators= Utilisator::all();
        //afficher les données sous format JSON
        return response()->json($utilisators);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(UtilisatorRequest $request)
    {
        // Validation des données (à l'exception de l'unicité)
        $validatedData = $request->validate($request->rules());

        // Création d'un validateur pour vérifier l'unicité de l'email et du téléphone
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', Rule::unique('utilisators', 'email')],
            'telephone' => ['required', Rule::unique('utilisators', 'telephone')],
        ]);

        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Vérification de la présence du fichier photo dans la requête
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            // Renommer le fichier
            $photoName = time() . '_' . $photo->getClientOriginalName();
            // Déplacer le fichier dans le dossier public
            $photo->move(public_path('photos'), $photoName);
            // Ajouter le nom du fichier aux données validées
            $validatedData['photo'] = $photoName;
        }

        $utilisator = Utilisator::create($validatedData);
        return response()->json($utilisator, 201);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //chercher l utilisateur dans la BD avec id
        $utilisator= Utilisator::findOrFail($id);

        //retouner la resultat sous format JSON
        return response()->json($utilisator);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UtilisatorRequest $request, $id)
    {
            // Récupérer l'utilisateur existant
            $utilisator = Utilisator::findOrFail($id);

            // Valider les données de la requête
            $validatedData = $request->validated();

            // Mettre à jour les données de l'utilisateur
            $utilisator->update($validatedData);

            // Retourner la réponse avec l'utilisateur mis à jour
            return response()->json($utilisator);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $utilisator= Utilisator::findOrFail($id);
        $utilisator->delete();
        return response()->json(null,204);
    }
}
