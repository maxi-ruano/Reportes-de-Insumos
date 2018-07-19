<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
}

/*

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


    use AuthenticatesUsers;


    protected $username = 'username';
    //protected $redirectAfterLogout = 'public/login';

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
	           ->whereNull('end_date')
                   ->first();
      $idsUsuariosDisposiciones = array("2722","2790","2721","2832","2639","2828", "2432");
      $idsUsuariosControlInsumos = array("2430");

      if(isset($user)){ //Si usuario exintente
        $userRole = SysUserRole::where('user_id', $user->id)->whereIn('role_id', [7, 9, 40, 76])->first(); //Usuarios Bedel y Admin

        if(in_array($user->id, $idsUsuariosDisposiciones)){ //Si Usuarios Disposiciones
          $this->guardarDatosUsuariosSession($request, $user);
          $this->guardarDatosRol($request, 76, 'ROL_DISPOSICIONES');
          return redirect('/admin/disposiciones');
        }

        if(isset($userRole)){ //Si Usuarios Bedel y Admin
          $this->guardarDatosUsuariosSession($request, $user);
          $role = SysRoles::where('role_id', $userRole->role_id)->first();
          $this->guardarDatosRol($request, $userRole->role_id, $role->cte_php);
          return redirect('/admin/bedel');
        }

        if(in_array($user->id, $idsUsuariosControlInsumos)){ //Si Usuarios Control Insumos
          $this->guardarDatosUsuariosSession($request, $user);
          $this->guardarDatosRol($request, 77, 'ROL_REPORTES_CONTROL_INSUMOS');
          return redirect('/admin/reporteSecuenciaInsumos');
        }
        return redirect('/login');
      }else{
        return $this->sendFailedLoginResponse($request);
      }


      //return redirect('/admin/bedel');
    }
    public function guardarDatosUsuariosSession($request, $user){
      Auth::login($user, true);
      $request->session()->put('usuario_nombre', $user->first_name);
      $request->session()->put('usuario_id', $user->id);
      $request->session()->put('usuario_sucursal_id', $user->sucursal);
      $user->last_log = date("Y-m-d H:i:s");
      $user->save();
    }

    public function guardarDatosRol($request, $idRol, $textoRol){
      $request->session()->put('usuario_rol_id', $idRol);
      $request->session()->put('usuario_rol', $textoRol);
    }

    public function setPasswordAttribute($password){
      $this->attributes['password'] = md5($password);
    }

    public function username()
    {
        return 'username';
    }


}
*/