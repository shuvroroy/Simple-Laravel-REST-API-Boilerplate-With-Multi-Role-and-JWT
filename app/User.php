<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Response;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username',"phone", 'password','bio','role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at', 'deleted_at', 'created_at'
    ];

    public static function roles()
    {
        return ["user", "admin", "superadmin"];
    }

    //Roles at the moment "user","admin","superadmin"
    public function hasRole($role){

        if($this->role == "superadmin"){
            return true;
        } else if ($role == $this->role) {
            return true;
        }

        return false;
    }

    public static function roleErrorResponse(){

        $error["error"] = "You do not have permission to access this";
        return Response::json($error, 200);

    }

    //Relations

    public static function index(Request $request)
    {

        $users = User::where('role', '!=', 'superadmin');

        if ($request->has("name")) {
            $user = $users->where("name", "LIKE", "%" . $request->name . "%");
        }

        $users = $users->paginate(25);

        return JsonReturn::successData($users);
    }


}
