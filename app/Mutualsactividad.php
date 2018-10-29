<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mutualsactividad extends Model
{

  protected $table = 'mutualsactividads';

  public function actividads()
  {
      return $this->belongsTo('App\Actividad');
  }

  public function mutuals()
  {
      return $this->belongsTo('App\Mutual');
  }


}
