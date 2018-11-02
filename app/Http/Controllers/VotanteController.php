<?php

namespace App\Http\Controllers;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use App\Alumno;
use App\Votante;

use App\User;
use Bouncer;

class VotanteController extends Controller
{


  public function index()
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }


    $votantes = Votante::paginate(40);
    $title = "votantes";
    return view('votantes.index', ['votantes' => $votantes, 'title' => $title ]);


  }


  public function create()
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $users = User::where('esmesa',true)->get();

    $title = "Votante";
    return view('votantes.create', ['title' => $title, 'users' => $users]);
  }



  public function store(Request $request)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $activo=1;
    if($request->activo=="") {
      $activo=0;
    };


    $validator = Validator::make($request->all(), [
      'users_id' => 'required|exists:users,id',
      'alumnos_id' => 'required|exists:alumnos,id',
    ]);


    if ($validator->fails()) {
      foreach($validator->messages()->getMessages() as $field_name => $messages) {
        foreach($messages AS $message) {
          $errors[] = $message;
        }
      }
      return redirect()->back()->with('errors', $errors)->withInput();
      die;
    }

    $votante = new Votante;
    $votante->users_id = $request->users_id;
    $votante->alumnos_id = $request->alumnos_id;
    $votante->activo = true;
    $votante->save();

    $user = User::find($request->users_id);
    $user->activo = false;
    $user->save();

    // $alumno = Alumno::find($request->alumnos_id);
    // $alumno->activo = false;
    // $alumno->save();

    return redirect('/votantes/create');

  }


  public function show($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }


    $alumno = Alumno::find($id);
    $title = "Alumno";
    return view('alumnos.show', ['alumno' => $alumno,'title' => $title]);

  }



  public function edit($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }


    $alumno = Alumno::find($id);
    $title = "Alumno";
    return view('alumnos.edit', ['alumno' => $alumno,'title' => $title]);

  }



  public function update(Request $request, $id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }


    $activo=1;
    if($request->activo=="") {
      $activo=0;
    };


    $alumno = Alumno::find($id);
    $alumno->apellido = $request->apellido;
    $alumno->nombre = $request->nombre;
    $alumno->dni = $request->dni;
    $alumno->activo = $activo;
    $alumno->save();
    return redirect('/alumnos');
  }



  public function destroy($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }



    $alumno = Alumno::find($id);
    $alumno->delete();

    return redirect('/alumnos');
  }



  public function finder(Request $request)
  {
    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }


    $alumnos = Alumno::where('apellido', 'like', '%'. $request->buscar . '%')->paginate(15);
    $title = "Alumno: buscando " . $request->buscar;
    return view('alumnos.index', ['alumnos' => $alumnos, 'title' => $title ]);

  }


  public function search(Request $request){
    $term = $request->term;
    $datos = alumno::where('apellido', 'like', '%'. $request->term . '%')->where('activo', true)->get();
    $adevol = array();
    if (count($datos) > 0) {
      foreach ($datos as $dato)
      {
        $adevol[] = array(
          'id' => $dato->id,
          'value' => $dato->apellido . ', ' . $dato->nombre,
        );
      }
    } else {
      $adevol[] = array(
        'id' => 0,
        'value' => 'no hay coincidencias para ' .  $term
      );
    }
    return json_encode($adevol);
  }





}
