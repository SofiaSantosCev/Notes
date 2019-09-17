<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['password'];
    public function rol()
    {
        return $this->belongsTo('App\Rol');
    }
}
