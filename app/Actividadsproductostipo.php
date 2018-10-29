<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actividadsproductostipo extends Model
{

  protected $table = 'actividadsproductostipos';

  public function actividads()
  {
      return $this->belongsTo('App\Actividad');
  }

  public function productostipos()
  {
      return $this->belongsTo('App\Productostipo');
  }



}
