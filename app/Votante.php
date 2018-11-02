<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Votante extends Model
{

    protected $table = 'votantes';

    public function users()
    {
        return $this->belongsTo('App\User');
    }

    public function alumnos()
    {
        return $this->belongsTo('App\Alumno');
    }


}
