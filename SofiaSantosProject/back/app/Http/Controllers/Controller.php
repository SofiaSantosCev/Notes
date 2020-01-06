<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use \Firebase\JWT\JWT;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const TOKEN_KEY = 'bHH2JilqwA3Yx0qwn';

    protected function findUser($email)
    {
        $user = User::where('email',$email)->first();       
        return $user; 
    }

    //Check if the token is valid
    protected function checkLogin()
    {
        $headers = getallheaders();

        if(!isset($headers['Authorization']))
        { 
            return false;
        }

        $tokenDecoded = self::decodeToken();
        $user = self::getUserFromToken();

        if ($tokenDecoded->password == $user->password and $tokenDecoded->email == $user->email) 
        {
            return true;
        } else {
            return self::response('You dont have permission',301);
        }
    }

    protected function getUserfromToken()
    {
        $tokenDecoded = self::decodeToken();
        $user = self::findUser($tokenDecoded->email);
        return $user;
    }

    protected function response($text, $code){

        return response()->json([
            'message' => $text
        ],$code);
    }

    private static function decodeToken() {
        $headers = getallheaders();

        if(isset($headers['Authorization']))
        {
            $token = $headers['Authorization'];
            $tokenDecoded = JWT::decode($token, self::TOKEN_KEY, array('HS256'));
            return $tokenDecoded;
        }
    }
}
