<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('users.index', compact('user'));
    }
    
    public function showRegistrationForm()
    {
        return view('register');
    }

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

    public function showLoginForm()
    {
        return view('login');
    }

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

    public function addFunds(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user->wallet += $request->input('amount');
        $user->save();

        return redirect()->route('users.index')->with('success', 'Funds added successfully!');
    }

    // Método para retirar fundos da carteira do usuário
    public function withdrawFunds(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $user->wallet,
        ]);

        $user->wallet -= $request->input('amount');
        $user->save();

        return redirect()->route('users.index')->with('success', 'Funds withdrawn successfully!');
    }
}

