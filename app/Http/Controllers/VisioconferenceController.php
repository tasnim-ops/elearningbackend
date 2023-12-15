<?php

namespace App\Http\Controllers;

use App\Models\Visioconference;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
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
    public function store(Request $request)
    {
        var_dump("title", $request->title);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'teacher_id' => 'required|string',
            'conftime' => 'required|string',
            'confdate' => 'required|string',
            'participants' => 'required|array',
            'participants.*' => 'email',
            'duration' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $visioconference = Visioconference::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'teacher_id' => $request['teacher_id'],
            'conftime' => $request['conftime'],
            'confdate' => $request['confdate'],
            'duration' => $request['duration'],
            'participants' => $request['participants'],
        ]);

        return response()->json([$visioconference, 201]);
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
    public function update(Request  $request, $id)
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
