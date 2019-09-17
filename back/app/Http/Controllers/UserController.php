<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use \Firebase\JWT\JWT;

class UserController extends Controller
{

    const ID_ROL = 2;

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
        $user->rol_id = 1;
        $user->save();

        parent::response("User created", 200);
    }

    // User login
    public function signIn()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = User::where('email', $email)->first();
        $id = $user->id;
        $rol_id = $user->rol_id;
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

    }

    public function deleteUser()
    {

    }
}
