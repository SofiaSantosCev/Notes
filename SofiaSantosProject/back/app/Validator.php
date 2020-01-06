<?php

namespace App;

use App\User;

class Validator 
{
    public static function isValidEmail($email)
	{
		$matches = null;
		return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $email, $matches));
	}

	public static function hasOnlyOneWord($string)
	{
		return ctype_graph($string);
	}

	public static function isStringEmpty($string)
	{
		return $string == "";
	}

	public static function exceedsMaxLength($string, $max)
	{
		return strlen($string) > $max;
	}

	public static function reachesMinLength($string, $min)
	{
		return strlen($string) > $min;
	}

	public static function isEmailInUse($email){
		$existingUser = User::where('email', $email)->first();
		if(!is_null($existingUser)){
			return true;
		} else {
			return flase;
		}
	}
}
