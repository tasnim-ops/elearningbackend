<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;


use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $administrators= Administrator::all();
         return response()->json($administrators);
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
            'email' => ['required', 'email', Rule::unique('administrators', 'email')],
            'phone' => ['required', Rule::unique('administrators', 'phone')],

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

        // Création de l'instance Administrator
        $admin = Administrator::create([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'password' => bcrypt($request->input('password')),
            'photo' => $photoUrl,
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

        return response()->json($admin, 201);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         $administrator= Administrator::findOrFail($id);

         return response()->json($administrator);

    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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
