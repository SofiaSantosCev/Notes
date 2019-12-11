<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\Validator;
use App\User;
use Hash;

class UserController extends Controller
{
    const ID_ROLE = 2;
    const TOKEN_KEY = 'bHH2JilqwA3Yx0qwn';
    

    public function index() {
        
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        if(!isset($name) && !isset($email) && !isset($password)) {
            return parent::response('Input information wrong', 400);
        }

        if(self::isEmailInUse($email))
        {
            return parent::response('This email belongs to another account', 400);
        }

        $user = new User;
        
        $user->name = $name;
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->role_id = self::ID_ROLE;
        
        $user->save();
        
        $user = User::where('email', $email)->first();  
        $id = $user->id;
        $role_id = $user->role_id;
        $token = self::generateToken($email, $password);
        
        return response()->json([
            'token' => $token,
            'user_id'=> $id, 
            'role_id' => $role_id,
            'email' => $email
        ]);
    }

    // User login
    public function login(Request $request)
    {   
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (Validator::isStringEmpty($email) || Validator::isStringEmpty($password)) {
            return parent::response("All fields have to be filled", 400);
        }
        
        $user = User::where('email', $email)->first();

        if ($user == null ){
            return parent::response("This email doesn't belong to any account", 400);
        }

        $id = $user->id;
        $role_id = $user->role_id;
        
        if ($user->email == $email && password_verify($password, $user->password))
        {
            $token = self::generateToken($email, $password);

            return response()->json([
                'token' => $token,
                'user_id'=> $id, 
                'role_id' => $role_id,
                'email' => $email
            ]);
        } else {
            return parent::response("Wrong password or email", 400);
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
            return parent::response('You need to login to delete the account.', 301);
        }
    }
}
