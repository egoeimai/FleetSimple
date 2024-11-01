<?php

namespace App\Http\Controllers\authentications;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Http\Request;

class LoginBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }


  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }
  public function login(Request $request)
  {
    $inputVal = $request->all();

    $this->validate($request, [
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if (auth()->attempt(array('email' => $inputVal['email'], 'password' => $inputVal['password']))) {
      if (auth()->user()->is_admin == 1) {
        return redirect()->route('admin.route');
      } else {
        return redirect()->route('dashboard');
      }
    } else {
      return redirect()->route('auth-login-basic')
        ->with('error', 'Email & Password are incorrect.');
    }
  }

  /**
   * Write code on Method
   *
   * @return response()
   */
  public function logout(): RedirectResponse
  {
    Session::flush();
    Auth::logout();

    return redirect()->route('auth-login-basic');
  }
}
