<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{

  protected $table = 'torneos';

  public function deportes()
  {
      return $this->belongsTo('App\Deporte');
  }


}
