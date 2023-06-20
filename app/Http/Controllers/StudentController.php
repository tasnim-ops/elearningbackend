<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Utilisator;
use App\Http\Requests\UtilisatorRequest;

//import de classe mere
use App\Http\Controllers\UtilisatorController;

class StudentController extends UtilisatorController
{
    public function store(UtilisatorRequest $request)
    {
          // Appeler la méthode store() de la classe mère UtilisatorController
    $utilisatorController = new UtilisatorController();
    $response = $utilisatorController->store($request);

    // Vérifier si la réponse JSON contient des erreurs
    if ($response->getStatusCode() !== 201) {
        return $response; // Retourner la réponse d'erreur telle quelle
    }

    // Obtenir l'objet Utilisator à partir du corps de la réponse JSON
    $utilisator = $response->getOriginalContent();

    // Créer un nouvel objet Student et l'associer à l'Utilisator créé
    $student = new Student();
    $student->utilisator_id = $utilisator->id;
    $student->save();

    return response()->json([
        'utilisator' => $utilisator,
        'student' => $student
    ], 201);

    }

    public function index()
    {
        // Filtrer les utilisateurs par type "student"
        $students = Student::all();

        // Retourner les enseignants sous format JSON
        return response()->json($students);
    }
    public function update(UtilisatorRequest $request, $id)
    {
        // Appeler la méthode parente 'update' de UtilisatorController
        parent::update($request, $id);

        // Mettre à jour les données spécifiques aux enseignants dans la table 'students'
        $student = Student::findOrFail($id);
        // Mettre à jour les champs spécifiques aux enseignants dans la table 'students' en utilisant les données de la requête
        $student->update([
            // Ajoutez ici les champs spécifiques aux enseignants que vous souhaitez mettre à jour
            // Exemple : 'specialty' => $request->input('specialty'),
        ]);

        // Retourner la réponse avec l'enseignant mis à jour
        return response()->json($student);
    }

    public function destroy($id)
    {
         // Supprimer l'enseignant de la table 'students'
         Student::where('utilisator_id', $id)->delete();

         // Appeler la méthode parente 'destroy' de UtilisatorController
         parent::destroy($id);

         return response()->json(null, 204);
    }
}
