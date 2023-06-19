<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //importer les donnéés de la BD
        $conferences= Conference::all();
        //afficher les données sous format JSON
        return response()->json($conferences);
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
            'title' => 'required|string',
            'description' => 'required|string',
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        $validatedData = $request->validate([
            'status' => ['required', Rule::in(['to do', 'done'])],
        ]);
        $conference = Utilisator::create($validatedData);
        return response()->json($conference, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         $conference= Utilisator::findOrFail($id);
         return response()->json($confernec);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conference $conference)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        $validatedData = $request->validate([
            'status' => ['required', Rule::in(['to do', 'done'])],
        ]);
                $conference= Confernce::findOrFail($id);
                $confernece->update($validatedData);
                return response()->json($confernece);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $conference= Utilisator::findOrFail($id);
        $conference->delete();
        return response()->json(null,204);
    }

    public function getToDoConferences()
    {
        $conferences = Conference::where('status', 'to do')->get();
        return $conferences;
    }
}
