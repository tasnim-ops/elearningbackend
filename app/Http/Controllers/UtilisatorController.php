<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UtilisatorRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Utilisator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
class UtilisatorController extends Controller
{
   // UtilisateurController.php

public function editerPhoto(Request $request, $id)
{
    // Validez la requête (vérifiez si une nouvelle photo a été envoyée, etc.)
    $request->validate([
        'nouvelle_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Exemple de validation pour une image
    ]);

    // Obtenez l'utilisateur à partir de l'ID
    $utilisateur = Utilisator::findOrFail($id);

    // Gérez la nouvelle photo
    if ($request->hasFile('nouvelle_photo')) {
        // Supprimez l'ancienne photo si elle existe
        Storage::delete($utilisateur->photo);

        // Enregistrez la nouvelle photo dans le stockage
        $nouveauChemin = $request->file('nouvelle_photo')->store('chemin/vers/le/stockage');

        // Mettez à jour le chemin de la photo dans la base de données
        $utilisateur->update(['photo' => $nouveauChemin]);
    }

    // Retournez une réponse appropriée
    return response()->json(['message' => 'Photo mise à jour avec succès'], 200);
}

}
