<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createUser(Request $request)
    {
        // validacao dos dados
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|string',
        ]);

        // criacao do usuario
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'user_type' => $validatedData['user_type'],
        ]);

        return redirect()->route('users')->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Remove o usuário do sistema.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Busca o usuário pelo ID
        $user = User::findOrFail($id);

        // Deleta o usuário
        $user->delete();

        // Redireciona com mensagem de sucesso
        return redirect()->route('users')->with('success', 'Usuário excluído com sucesso.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'user_type' => 'required|string',
        ]);

        $user = User::findOrFail($id);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }
        
        $user->user_type = $validatedData['user_type'];
        $user->save();

        return redirect()->route('users')->with('success', 'Usuário atualizado com sucesso.');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::all();

        return view('users', compact('users'));
    }
}
