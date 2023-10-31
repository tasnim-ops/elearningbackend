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
         $administrators= Administrator::all();
         return response()->json($administrators);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', Rule::unique('administrators', 'email')],
            'phone' => ['required', Rule::unique('administrators', 'phone')],
            'photo' => ['image', 'nullable'],
            'firstname' => ['required'],
            'lastname' => ['required'],
            'password' => ['required', 'string', 'min:8'], // Add validation for the password field

        ]);
        $messages=[
            'required' => 'The field is required',
            'unique' => 'e-mail already exist'
        ];
        $validator->setCustomMessages($messages);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // If validation passes, process the request
        $validatedData = $validator->validated();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            // Renommer le fichier
            $photoName = time() . '_' . $photo->getClientOriginalName();
            // Déplacer le fichier dans le dossier public
            $photo->move(public_path('photos'), $photoName);
            // Ajouter le nom du fichier aux données validées
            $validatedData['photo'] = $photoName;
        }else{
            $validatedData['photo']=null;
        }

        // Créer un nouvel administrateur en utilisant les données validées
        $administrator = Administrator::create([
            'firstname'=> $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'password' => bcrypt($request->input('password')),
            'photo' => $validatedData['photo'],
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

        // Retourner la réponse avec le nouvel administrateur créé
        return response()->json($administrator, 201);
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
