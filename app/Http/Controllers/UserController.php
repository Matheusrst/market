<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Hash;
use Illuminate\Support\Facades\Hash as FacadesHash;

class UserController extends Controller
{
    /**
     * construct de verificação de usuarios
     */
    public function __construct()
    {
        $this->middleware('auth.user')->except(['index', 'showLoginForm', 'login', 'showRegistrationForm', 'register']);
    }

    /**
     * vereficação de usuarios e visualização do menu
     *
     * @return void
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('users.index', compact('user'));
    }
    
    /**
     * visualização de registro
     *
     * @return void
     */
    public function showRegistrationForm()
    {
        return view('register');
    }

    /**
     * registro de novos usuario no banco de dados
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.showRegistrationForm')
                             ->withErrors($validator)
                             ->withInput();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        Auth::login($user);

        return redirect()->route('products.index')->with('success', 'User registered and logged in successfully!');
    }

    /**
     * visualização de login
     *
     * @return void
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * login de usuarios cadastrados no banco de dados
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Autenticação bem-sucedida
            return redirect()->route('products.index')->with('success', 'Logged in successfully!');
        } else {
            // Falha na autenticação
            return redirect()->route('users.showLoginForm')->with('error', 'Invalid credentials, please try again.');
        }
    }

    /**
     * logout do usuario logado
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('users.showLoginForm');
    }

    /**
     * adição de fundos na carteira do usuário
     *
     * @param Request $request
     * @param User $user
     * @return void
     */
    public function addFunds(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user->wallet += $request->input('amount');
        $user->save();

        return redirect()->route('users.index')->with('success', 'Funds added successfully!');
    }

    /**
     * remoção de fundos na carteira do usário
     *
     * @param Request $request
     * @param User $user
     * @return void
     */
    public function withdrawFunds(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $user->wallet,
        ]);

        $user->wallet -= $request->input('amount');
        $user->save();

        return redirect()->route('users.index')->with('success', 'Funds withdrawn successfully!');
    }

    /**
     * visualização de edição de usuario
     *
     * @param [type] $id
     * @return void
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * ediçao de ususario cadastrado 
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = FacadesHash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.edit', $user->id)->with('success', 'User updated successfully.');
    }

    /**
     * visualizaçõa e confirmação de delete
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

//        if ($user->id === Auth::user()->id) {
//          return redirect()->back()->with('error', 'You cannot delete yourself.');
//        }

        return view('users.confirm-delete', compact('user'));
    }

    /**
     * eexecluuir usuarios
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('products.index')->with('success', 'User deleted successfully.');
    }
}

