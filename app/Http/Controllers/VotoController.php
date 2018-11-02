<?php

namespace App\Http\Controllers;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use App\Alumno;
use App\Categoria;
use App\Votante;
use App\Proyecto;
use App\Voto;
use App\User;
use Bouncer;

class VotoController extends Controller
{


  public function store($categorias_id, $votantes_id, $proyectos_id)
  {


    // verifico si la categoria existe

    $categoria = Categoria::find($categorias_id);
    $votante = Votante::find($votantes_id);
    $proyecto = Proyecto::find($proyectos_id);

    if (!$categoria or !$votante or !$proyecto) {
      return redirect('/home');
    }


    $voto = Voto::where('votantes_id', $votantes_id)
    ->where('categorias_id', $categorias_id)
    ->first();

    if(!$voto) {

      $voto = new Voto;
      $voto->votantes_id = $votantes_id;
      $voto->categorias_id = $categorias_id;
      $voto->proyectos_id = $proyectos_id;
      $voto->save();


    }

    $categoria = Categoria::orderby('id','desc')->first();


    $voto = Voto::where('votantes_id', $votantes_id)
    ->where('categorias_id', $categoria->id)
    ->first();

    if($voto) {

      $votante = Votante::find($votantes_id);
      $votante->activo= false;
      $votante->save();
      $user = User::find($votante->users_id);
      $user->activo= true;
      $user->save();
      $alumno = Alumno::find($votante->alumnos_id);
      $alumno->activo= false;
      $alumno->save();


    }

    return redirect('/home');

  }




}
