<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('status', 'Contraseña cambiada con éxito');
    }

    public function showEmailForm()
    {
        return view('profile');
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email',
        ]);

        $user = Auth::user();
        $user->email = $request->new_email;
        $user->email_verified_at = null; // Elimino la marca de verificación porque el correo electrónico ha cambiado
        $user->save();

        return back()->with('status', 'Correo electrónico cambiado con éxito');
    }

    public function showUsernameForm()
    {
        return view('profile');
    }

    public function changeUsername(Request $request)
    {
        $request->validate([
            'new_username' => 'required|unique:users,name',
        ]);

        $user = Auth::user();
        $user->name = $request->new_username;
        $user->save();

        return back()->with('status', 'Nombre de usuario cambiado con éxito');
    }

    public function deleteUser() {
        
        $user = Auth::user();
       if ($user->id === 1) {
            return redirect()->back()->with('error', 'No puedes eliminar al superadmin');
        }
        else {      
            $user->delete();
            return redirect()->route('home')->with('status', 'Usuario eliminado correctamente');
        }
    }

    public function changeRole(Request $request)
    {
        $user = Auth::user();
        
        // Validar que solo admin y superadmin puedan cambiar roles
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            return back()->with('error', 'No tienes permisos para cambiar roles');
        }

        $request->validate([
            'new_role' => ['required', 'in:admin,user'],
        ]);

        // Si es admin, solo puede asignar role user
        if ($user->role === 'admin' && $request->new_role !== 'user') {
            return back()->with('error', 'Solo puedes asignar el rol de usuario');
        }

        $user->role = $request->new_role;
        $user->save();

        return back()->with('status', 'Rol actualizado correctamente');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($user->role === 'admin' || $user->role === 'superadmin') {
            $rules['role'] = 'required|in:user,admin';
        }

        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $request->validate($rules);

        $userData = $request->only(['name', 'email', 'role']);

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('profile.show')->with('status', 'Perfil actualizado correctamente');
    }
}