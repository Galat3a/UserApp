<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Hash;  // Añadida esta línea

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(AdminMiddleware::class);
    }

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        // No permitir auto-eliminación
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes eliminarte a ti mismo');
        }

        // No permitir eliminar al superadmin id=1 para impedir que se haga desde el navegador hacker 
        if ($user->id === 1) {
            return redirect()->back()->with('error', 'No puedes eliminar al superadmin');
        }

        $user->delete();
        return redirect()->route('users.index')->with('status', 'Usuario eliminado correctamente');
    }
        
    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Acceso no autorizado');
        }
        return view('users.create'); // Cambiado de 'users.create' a 'users.create'
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,admin'],
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
    
        return redirect()->route('users.index')
            ->with('status', 'Usuario creado correctamente');
    }
    
    public function verify(Request $request, User $user)
    {
        // Permitir que el usuario verifique su propio correo
        if ($user->email_verified_at !== null) {
            return redirect()->back()->with('error', 'El correo ya está verificado');
        }

        $user->email_verified_at = now();
        $user->save();
        return redirect()->back()->with('status', 'Correo verificado correctamente');
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === 1 && auth()->user()->id !== 1) {
            return redirect()->back()->with('error', 'No puedes editar al superadmin');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin'
        ];

        // Validar contraseña solo si se proporciona
        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $request->validate($rules);

        $userData = $request->only(['name', 'email', 'role']);
        
        // Actualizar contraseña si se proporciona
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        return redirect()->route('users.index')->with('status', 'Usuario actualizado correctamente');
    }
}