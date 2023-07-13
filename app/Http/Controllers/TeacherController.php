<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UtilisatorRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Teacher;
use Illuminate\Validation\Rule;
class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //importer les donnéés de la BD
        $teachers= Teacher::all();
        //afficher les données sous format JSON
        return response()->json($teachers);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(UtilisatorRequest $request)
    {
        // Création d'un validateur pour vérifier l'unicité de l'email et du téléphone
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', Rule::unique('teachers', 'email')],
            'telephone' => ['required', Rule::unique('teachers', 'telephone')],
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
            $photo->move(public_path('images'), $photoName);
            // Ajouter le nom du fichier aux données validées
            $photoUrl = URL::to('images/' . $photoName);
        }else {
            $photoUrl = $teacher->photo;
        }

        // Création de l'instance Teacher en utilisant les attributs validés
        //(l'utilisation de validated pour rendre request sous format de tableau)
        $teacher = Teacher::create($request->validated());
        return response()->json($teacher, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //chercher l Teacher dans la BD avec id
        $teacher= Teacher::findOrFail($id);

        //retouner la resultat sous format JSON
        return response()->json($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UtilisatorRequest $request, $id)
    {
            // Récupérer l'Teacher existant
            $teacher = Teacher::findOrFail($id);

            // Valider les données de la requête
            $validatedData = $request->validated();

            // Mettre à jour les données de l'Teacher
            $teacher->update($validatedData);

            // Retourner la réponse avec l'Teacher mis à jour
            return response()->json($teacher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $teacher= Teacher::findOrFail($id);
        $teacher->delete();
        return response()->json(null,204);
    }
}
