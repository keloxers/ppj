<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignedrole extends Model
{

  protected $table = 'assigned_roles';

  public $timestamps = false;


  public function roles()
  {
      return $this->belongsTo('App\Role','role_id');
  }



}
