<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Teacher;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
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
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'password' => 'required|string|min:8',
            'photo' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], 
            'email' => ['required', 'email', Rule::unique('teachers', 'email')],
            'phone' => ['required', Rule::unique('teachers', 'phone')],
            'fb' => 'url',
            'linkedin' => 'url',
            'github' => 'url',
            'desc' => 'string|max:255'
        ]);

        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Gestion du fichier photo (si présent)
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            if ($photo->isValid()) {
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('images'), $photoName);
                $photoUrl = URL::to('images/' . $photoName);
            }
        }

        // Création de l'instance Teacher
        $teacher = Teacher::create([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'password' => bcrypt($request->input('password')),
            'photo' => $photoUrl,
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'fb' => $request->input('fb'),
            'linkedin' => $request->input('linkedin'),
            'github' => $request->input('github'),
            'desc' => $request->input('desc')
        ]);

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
    public function update(Request $request, $id)
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
