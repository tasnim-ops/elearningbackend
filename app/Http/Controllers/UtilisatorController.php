<?php

namespace App\Http\Controllers;

use App\Models\Utilisator;
use Illuminate\Http\Request;

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
         // Validation des données
         $validatedData = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:utilisators',
            'telephone' => 'required|string|unique:utilisators',
            'password' => 'required|string|min:8',
            'photo' => ['image','mimes:jpeg,png,jpg,webp', 'max:2048']
        ]);

        //verificationpresence fichier dans la requette
        if($request->hasFile('photo')){
            $photo=$request->file('photo');
            //renommer lefichier
            $photoName=time() . '_' . $photo->getClientOriginalName();
            //deplacer le fichier dans public
            $photo->move(public_path('photos'),$photoName);
            //ajouter le nom de ficher aux données
            $validatedData['photo']=$photoName;
        }
        // Créer un nouvel utilisateur
        $utilisator = Utilisator::create($validatedData);

        // Retourner la réponse avec le nouvel utilisateur créé
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
     * Show the form for editing the specified resource.
     */
    public function edit(Utilisator $utilisator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $validatedData = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'telephone' => 'required|string',
            'password' => 'required|string|min:8',
            'photo' => ['image','mimes:jpeg,png,jpg,webp', 'max:2048']
        ]);
        //chercher l utilisateur
        $utilisator= Utilisator::findOrFail($id);

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
