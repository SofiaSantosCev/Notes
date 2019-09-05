<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public function Categories(){
        return $this->belongsTo('App\Category');
    }
}
