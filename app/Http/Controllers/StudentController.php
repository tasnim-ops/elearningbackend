<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\URL;
class StudentController extends Controller


{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //importer les donnéés de la BD
        $students= Student::all();
        //afficher les données sous format JSON
        return response()->json($students);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Création d'un validateur pour vérifier l'unicité de l'email et du téléphone
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'password' => 'required|string|min:8',
            'photo' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'email' => ['required', 'email', Rule::unique('students', 'email')],
            'phone' => ['required', Rule::unique('students', 'phone')],
            'fb' => 'url',
            'linkedin'=>'url',
            'github'=>'url',
            'desc'=> 'string|max:255'

        ]);

        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
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
            $photoUrl = $request->photo;
        }

        // Création de l'instance Student en utilisant les attributs validés
        //(l'utilisation de validated pour rendre request sous format de tableau)
        $student = Student::create(array_merge($request->all(),[
            'photo'=> $photoUrl,
        ]));

        return response()->json($student, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //chercher l Student dans la BD avec id
        $student= Student::findOrFail($id);

        //retouner la resultat sous format JSON
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
            // Récupérer l'Student existant
            $student = Student::findOrFail($id);

            // Valider les données de la requête
            $validatedData = $request->validated();

            // Mettre à jour les données de l'Student
            $student->update($validatedData);

            // Retourner la réponse avec l'Student mis à jour
            return response()->json($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student= Student::findOrFail($id);
        $student->delete();
        return response()->json(null,204);
    }
}
