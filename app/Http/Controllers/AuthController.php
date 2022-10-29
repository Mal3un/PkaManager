<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(request $request)
    {
        $message = $request->get('errors');

        return view('auth.login', [
            'message' => $message ?? null
        ]);
    }

    public function authCheck(Request $request)
    {
        session()->start();
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt($request->only(['username', 'password']))) {
            Auth::login(auth()->user());
            if(auth()->user()->role_id === 3) {
                return redirect()->route('manager.welcome');
            }else if(auth()->user()->role_id === 2) {
                $user = Teacher::where('user_id', auth()->user()->id)->first(['id','first_name','last_name','gender'])->toArray();
                session()->put('user', $user);
                return redirect()->route('manager.welcome');
            }else if(auth()->user()->role_id === 1) {
                $user = Student::where('user_id', auth()->user()->id)->first(['id','first_name','last_name','gender'])->toArray();
                session()->put('user', $user);
                return redirect()->route('manager.welcome');
            }
        }

        return redirect()->route('login', ['errors' => 'Sai tài khoản hoặt mật khẩu']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        return redirect()->route('login');
    }
}
