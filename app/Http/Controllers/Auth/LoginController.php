<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => [
                'required',
                Rule::exists('users', 'email')->where(function($query) {
                    return $query->where('state', 1);
                })
            ],
            'password' => 'required'
        ],[
            'email.required' => 'El correo electronico es necesario',
            'email.exists' => 'El correo electronico ingresado no existe',
            'password.required' => 'Por favor ingrese la contraseña'
        ]);

        if($validate->fails())
        {
            return back()->withErrors($validate->errors());
        }

        $credenciales = $request->only(['email', 'password']);
        if(Auth::attempt($credenciales))
        {
            return redirect()->route('home');
        }else{
            Session::flash('message_error', 'Credenciales incorrectas');
            return back();
        }
    }
}
