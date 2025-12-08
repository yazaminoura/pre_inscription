<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\User;
use App\Http\Requests\StoreFormationRequest;
use App\Http\Requests\UpdateFormationRequest;
use Brian2694\Toastr\Facades\Toastr;

class FormationController extends Controller
{
    public function index()
    {
        $formations = Formation::all();
        return view('utilisateur.formations.index', compact('formations'));
    }

    public function create()
    {
        $users = User::all();
        return view('utilisateur.formations.create', compact('users'));
    }
    
    public function store(StoreFormationRequest $request)
    {
        Formation::create($request->validated());
    
        return redirect()->route('formations.index')
        ->with('toastr', [
            'type' => 'success',
            'message' => 'Formation ajoutée avec succès'
        ]); 
    }

   
    public function edit($id)
{
    $formation = Formation::findOrFail($id);
    $users = User::all();
    return view('utilisateur.formations.edit', compact('formation', 'users'));
}

    public function update(UpdateFormationRequest $request, $id)
    {
        $formation = Formation::findOrFail($id);
        $formation->update($request->validated());

        return redirect()->route('formations.index')
        ->with('toastr', [
            'type' => 'success',
            'message' => 'Formation mise à jour avec succès'
        ]);
    }

    public function destroy($id)
    {
        $formation = Formation::findOrFail($id);
        $formation->delete();

       return redirect()->route('formations.index')
        ->with('toastr', [
            'type' => 'success',
            'message' => 'Formation supprimée avec succès'
        ]);
    }
}