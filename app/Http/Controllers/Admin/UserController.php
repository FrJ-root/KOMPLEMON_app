<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur');
    }
    
    public function index()
    {
        // Exclude administrators from the query for better performance
        $users = User::where('role', '!=', 'administrateur')->paginate(10);
        return view('admin.users.index', compact('users'));
    }
    
    public function create()
    {
        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['administrateur', 'gestionnaire_produits', 'gestionnaire_commandes', 'editeur_contenu'])],
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);
        
        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }
    
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['administrateur', 'gestionnaire_produits', 'gestionnaire_commandes', 'editeur_contenu'])],
        ]);
        
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];
        
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        
        $user->update($userData);
        
        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }
    
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
    
    /**
     * Block specific permissions for a user
     */
    public function blockPermissions(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'blocked_permissions' => 'nullable|array',
            'blocked_permissions.*' => 'string',
            'block_reason' => 'nullable|string|max:500',
        ]);
        
        $user = User::findOrFail($validated['user_id']);
        
        // Store blocked permissions in the user's record
        $user->blocked_permissions = $validated['blocked_permissions'] ?? [];
        $user->blocked_permissions_reason = $validated['block_reason'];
        $user->save();
        
        return redirect()->route('users.index')
            ->with('success', 'Les permissions ont été mises à jour avec succès.');
    }
}
