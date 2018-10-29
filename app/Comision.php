<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{

  protected $table = 'comisions';

  public function cargoscomisions()
  {
      return $this->belongsTo('App\Cargoscomision');
  }


}
