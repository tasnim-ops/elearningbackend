<?php

namespace App\Http\Controllers;

use App\Models\Essai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Response;
class EssaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $essais = Essai::all();
        return response()->json($essais);

    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'essai_name' => 'required|unique:essais',
            'essai_desc'=> 'required',
            'essai_result'=> 'required',
            'photo'=>'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('images'), $photoName);
            $photoUrl = URL::to('images/' . $photoName);
        } else {
            $photoUrl = null;
        }
        $request->photo = $photoUrl;
        $essai = Essai::create($request->all());
        return response([
            'message' => 'Mise à jour réussie',
            'data' => $essai,
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Essai $essai)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    // ...
    public function update(Request $request, $id)
    {
        $request->validate([
            'essai_name' => 'required',
            'essai_desc' => 'required',
            'essai_result' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $essai = Essai::findOrFail($id);
        } catch (\Exception $e) {
            return response(['error' => 'Échec de la mise à jour. ID non trouvé.'], 404);
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('images'), $photoName);
            $photoUrl = URL::to('images/' . $photoName);
        } else {
            $photoUrl = null;
        }

        $data = $request->except('photo');
        $data['photo'] = $photoUrl;

        // Ajouter les informations de débogage dans le tableau
        $debugInfo = [
            'data' => $data,
            'old_essai' => $essai,
        ];

        // Mettre à jour l'objet Essai
        $essai->update($data);

        // Retourner la réponse avec les informations de débogage
        return response([
            'message' => 'Mise à jour réussie',
            'data' => $essai,
            'debug_info' => $debugInfo, // Ajouter les informations de débogage à la réponse
        ], 200);
    }






    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Essai $essai)
    {
        //
    }
}
