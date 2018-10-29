<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cooperativasactividad extends Model
{

  protected $table = 'cooperativasactividads';

  public function actividads()
  {
      return $this->belongsTo('App\Actividad');
  }

  public function cooperativas()
  {
      return $this->belongsTo('App\Cooperativa');
  }


}
