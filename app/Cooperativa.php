<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cooperativa extends Model
{

  protected $table = 'cooperativas';

  public function institucions()
  {
      return $this->belongsTo('App\Institucion');
  }

}
