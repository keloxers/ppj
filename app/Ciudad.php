<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{

    protected $table = 'ciudads';

    public function provincias()
    {
        return $this->belongsTo('App\Provincia');
    }    

}
