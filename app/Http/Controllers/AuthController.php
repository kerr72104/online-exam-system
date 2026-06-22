<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'student',
        ]);

        Auth::login($user);

        Log::info('New user registered successfully.', [
            'user_id' => $user->id,
            'email'   => $user->email
        ]);

        return redirect()->route('student.dashboard');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            if(!User::where('email', $credentials['email'])->exists()) {
                Log::info('Login attempt with non-existent email.', [
                    'email' => $credentials['email']
                ]);
            }
            else {
                Log::info('Invalid password inputted for email.', [
                    'email' => $credentials['email']
                ]);
            }
            return back()
                ->onlyInput('email')
                ->with('error', 'Login failed. Please check your credentials and try again.');
        }

        $request->session()->regenerate();
    
        Log::info('User logged in successfully.', [
            'user_id' => Auth::id(),
            'email'   => Auth::user()->email
        ]);

        return $this->redirectByRole(Auth::user()->role);
    }

    public function logout(Request $request)
    {
        Log::info('User logged out.', [
            'user_id' => Auth::id(),
            'email' => Auth::user()?->email,
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirectByRole(string $role)
    {
        return match($role) {
            'admin'   => redirect()->route('admin.users.index'),
            'teacher' => redirect()->route('teacher.questions.index'),
            'student' => redirect()->route('student.dashboard'),
            default   => redirect('/'),
        };
    }
}