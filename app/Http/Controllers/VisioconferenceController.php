<?php

namespace App\Http\Controllers;

use App\Models\Visioconference;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\VisioconferenceRequest;
class VisioconferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                $visioconferences= Visioconference::all();
                return response()->json($visioconferences);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(VisioconferenceRequest $request)
    {
        $visioconference = Visioconference::create($request->validated());
        return response()->json($visioconference, 201);
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $visioconference=Visioconference::findOrFail($id);
        return response()->json($visioconference);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(VisioconferenceRequest $request, $id)
    {
         // Chercher la categorie
        $visioconference = Visioconference::findOrFail($id);

        // Mettez à jour la ressource
        $visioconference->update($request->all());

        return response()->json($visioconference, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $visioconference=Visioconference::findOrFail($id);
        $visioconference->delete();
        return response()->json(null,204);
    }
}
