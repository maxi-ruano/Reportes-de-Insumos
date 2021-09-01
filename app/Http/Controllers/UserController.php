<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Permission;
use App\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\SysMultivalue;

class UserController extends Controller
{
    use Authorizable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy('id','desc');
        
        if(isset($request->search))
            $users = $users->where(function($query) use ($request) {
                        $query->where('name', 'iLIKE', '%'. $request->search .'%')
                            ->orWhere('email', 'iLIKE', '%'. $request->search .'%');
                    });
        
        if(isset($request->sucursal))
            $users = $users->where('sucursal',$request->sucursal);
                    
        $result = $users->paginate(10);

        if(count($result)){
            foreach ($result as $key => $value) {
                $value->sucursal = User::find($value->id)->sucursalTexto();
            }
        }

        $SysMultivalue = new SysMultivalue();        
        $sucursales = $SysMultivalue->sucursales();
        
        return view('user.index', compact('result','sucursales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Mostrar el rol Admin solo a los usuarios Admin
        if(Auth::user()->hasRole('Admin'))
            $roles = Role::whereNull('deleted_at')->pluck('name', 'id');
        else
            $roles = Role::where('name','<>','Admin')->whereNull('deleted_at')->pluck('name', 'id');
        
        $SysMultivalue = new SysMultivalue();        
        $sucursales = $SysMultivalue->sucursales();

        $userModel = new User();       
        $sys_users = $userModel->usersLicta();

        return view('user.new', compact('roles','sucursales','sys_users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'sucursal' => 'required|min:1',
            'roles' => 'required|min:1'
        ]);
        
        // hash password
        $request->merge(['password' => bcrypt($request->get('password'))]);

        // Create the user
        if ( $user = User::create($request->except('roles', 'permissions')) ) {

            $this->syncPermissions($request, $user);

            flash('User has been created.');

        } else {
            flash()->error('Unable to create user.');
        }

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::whereNull('deleted_at')->pluck('name', 'id');
        $permissions = Permission::all('name', 'id');
        
        $SysMultivalue = new SysMultivalue();        
        $sucursales = $SysMultivalue->sucursales();

        $userModel = new User();       
        $sys_users = $userModel->usersLicta();

        return view('user.edit', compact('user', 'roles', 'permissions','sucursales','sys_users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'email' => 'required|email|unique:users,email,' . $id,
            'sucursal' => 'required|min:1',
            'roles' => 'required|min:1'
        ]);
        
        // Get the user
        $user = User::findOrFail($id);

        // Update user
        $user->fill($request->except('roles', 'permissions', 'password'));
        $user->sucursal = $request->get('sucursal');
        
        if($request->get('sys_user_id'))
            $user->sys_user_id = $request->get('sys_user_id');

        // check for password change
        if($request->get('password')) {
            $user->password = bcrypt($request->get('password'));
        }

        // Handle the user roles
        $this->syncPermissions($request, $user);

        $user->save();

        flash()->success('User has been updated.');

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function destroy($id)
    {
        if ( Auth::user()->id == $id ) {
            flash()->warning('Deletion of currently logged in user is not allowed :(')->important();
            return redirect()->back();
        }

        if( User::findOrFail($id)->delete() ) {
            flash()->success('User has been deleted');
        } else {
            flash()->success('User not deleted');
        }

        return redirect()->back();
    }

    /**
     * Sync roles and permissions
     *
     * @param Request $request
     * @param $user
     * @return string
     */
    private function syncPermissions(Request $request, $user)
    {
        // Get the submitted roles
        $roles = $request->get('roles', []);
        $permissions = $request->get('permissions', []);

        // Get the roles
        $roles = Role::find($roles);

        // check for current role changes
        if( ! $user->hasAllRoles( $roles ) ) {
            // reset all direct permissions for user
            $user->permissions()->sync([]);
        } else {
            // handle permissions
            $user->syncPermissions($permissions);
        }

        $user->syncRoles($roles);

        return $user;
    }

    public function activar(Request $request)
    {
        $sql = User::where("id",$request->id)->update(array('activo' => $request->activo));
        return $sql;
    }

    public function validateCredentialsLICTA($username, $password){
        $datos_user = \DB::table('sys_users')
            ->select('id', 'first_name', 'last_name', 'username', 'password', 'sector')
            ->where('username', $username)
            ->take(1)
            ->get();

        if($datos_user->first()) {
            $pass = hash('sha256', $datos_user[0]->password);

            if($password == $pass) {
                    return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

}
