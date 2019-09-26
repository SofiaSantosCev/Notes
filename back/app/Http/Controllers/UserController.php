<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use \Firebase\JWT\JWT;
use App\Validator;

class UserController extends Controller
{
    const ID_ROL = 2;
    const TOKEN_KEY = 'bHH2JilqwA3Yx0qwn';

    public function store(Request $request)
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(self::isEmailInUse($email))
        {
            return parent::response('This email belongs to another account', 400);
        }

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        $user->rol_id = self::ID_ROL;
        $user->save();

        return parent::response("User created", 200);
    }

    // User login
    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (Validator::isStringEmpty($email) or Validator::isStringEmpty($password)) {
            return parent::response("All fields have to be filled",400);
        }


        $user = User::where('email', $email)->first();
        $id = $user->id;
        $rol_id = $user->rol_id;

        if ($user->email == $email and password_verify($password, $user->password))
        {
            $token = self::generateToken($email, $password);
            
            return response()->json([
                'token' => $token,
                'user_id'=> $id, 
                'role_id' => $role_id
            ]);
        } else {
            return parent::response("You don't have access",400); 
        }

    }

    protected function generateToken($email, $password)
    {
        $dataToken = [
            'email' => $email,
            'password' => $password,
            'random' => time()
        ];

        $token = JWT::encode($dataToken, self::TOKEN_KEY);         
        return $token;
    }

    private function isEmailInUse($email)
    {
        $users = User::where('email', $email)->get();
        foreach ($users as $user) 
        {
            if($user->email == $email)
            {
                return true;
            }
        } 
    }

    public function destroy()
    {
        if (parent::checkLogin())
        {
            $user = parent::getUserFromToken();
            $user->delete();
            return parent::response('Account deleted successfully.', 200);
        } else {
            return parent::response('An error ocurred. Please, try later.', 301);
        }
    }
}
