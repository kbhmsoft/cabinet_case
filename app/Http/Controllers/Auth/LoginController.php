<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /*public function index()
    {
        $user = Auth::user();
        dd($user);

        dd(Auth::user()->role_id);
        if(Auth::user()->role_id == 1){
            // Superadmi dashboard        
            return view('dashboard.superadmin');

        }elseif(Auth::user()->role_id == 5){
            // DC office assistant dashboard            
            return view('dashboard.do_asst');
        }
    }
    */
}
