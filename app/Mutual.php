<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mutual extends Model
{

  protected $table = 'mutuals';

  public function institucions()
  {
      return $this->belongsTo('App\Institucion');
  }

}
