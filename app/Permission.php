<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

  protected $table = 'permissions';

  public $timestamps = false;


  public function abilities()
  {
      return $this->belongsTo('App\Abilitie','ability_id');
  }



}
