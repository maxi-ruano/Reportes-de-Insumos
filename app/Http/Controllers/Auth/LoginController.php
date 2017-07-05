<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\SysUsers;
use App\SysRoles;
use App\SysUserRole;
use Auth;
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
    protected $username = 'username';
    //protected $redirectAfterLogout = 'public/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function logout(Request $request)
    {
      $this->guard()->logout();

      $request->session()->flush();

      $request->session()->regenerate();

      return redirect('/login');
    }

    public function login(Request $request)
    {
      $this->validateLogin($request);

      $user = SysUsers::where('username', $request->username)
                   ->where('password',md5($request->password))
                   ->first();
      $idsUsuariosDisposiciones = array("2722","2790","2721","717","2639");
      if(isset($user) ||  in_array($user->id, $idsUsuariosDisposiciones)){
        $userRole = SysUserRole::where('user_id', $user->id)
                           ->whereIn('role_id', [7, 9, 40, 76])->first(); //Usuarios Bedel y Admin
        if(isset($userRole) || in_array($user->id, $idsUsuariosDisposiciones)){
          Auth::login($user, true);
          $request->session()->put('usuario_nombre', $user->first_name);
          $request->session()->put('usuario_id', $user->id);
          $role = SysRoles::where('role_id', $userRole->role_id)->first();
          if(in_array($user->id, $idsUsuariosDisposiciones)){
            $request->session()->put('usuario_rol_id', 76);
            $request->session()->put('usuario_rol', 'ROL_DISPOSICIONES');
          }else{
            $request->session()->put('usuario_rol_id', $userRole->role_id);
            $request->session()->put('usuario_rol', $role->cte_php);
          }
          $request->session()->put('usuario_sucursal_id', $user->sucursal);
          if(($role->cte_php == 'ROL_DISPOSICIONES') || in_array($user->id, $idsUsuariosDisposiciones))
            return redirect('/admin/disposiciones');
        }

        return redirect('/admin/bedel');
      }else{
        return $this->sendFailedLoginResponse($request);
      }
/*
      if (Auth::attempt(['username' => $user->username, 'password' => $user->password], true)) {
        dd('si');// The user is being remembered...
      }else{
        dd('no');
      }
*/

      //return redirect('/admin/bedel');
    }

    public function setPasswordAttribute($password){
      $this->attributes['password'] = md5($password);
    }

    public function username()
    {
        return 'username';
    }


}
