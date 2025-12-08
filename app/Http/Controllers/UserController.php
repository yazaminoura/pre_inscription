<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('utilisateur.administrateurs.index', compact('users'));
    }

    public function create()
    {
        return view('utilisateur.administrateurs.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('administrateurs.index')
        ->with('toastr', [
            'type' => 'success',
            'message' => 'Utilisateur créé avec succès'
        ]);
    }

    public function edit(User $user)
    {
        return view('utilisateur.administrateurs.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Update password only if provided
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('administrateurs.index')
        ->with('toastr', [
            'type' => 'success',
            'message' => 'Profil mis à jour avec succès'
        ]);
    }

   public function destroy(User $user)
{
    // Prevent deletion of the main admin user
    if ($user->id === 1) {
        return redirect()->back()->with('toastr', [
            'type' => 'error',
            'message' => 'Impossible de supprimer cet utilisateur administrateur principal.'
        ]);
    }
    try {
        $user->delete();
        return redirect()->route('administrateurs.index')->with('toastr', [
            'type' => 'success',
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->with('toastr', [
            'type' => 'error',
            'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
        ]);
    }
}
}