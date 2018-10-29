<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\Torneo;

class TorneoController extends Controller
{

  public function index()
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }


    $torneos = Torneo::paginate(25);
    $title = "Torneos";
    return view('torneos.index', ['torneos' => $torneos, 'title' => $title ]);

  }



    public function create()
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $title = "Torneo Crear";
      return view('torneos.create', ['title' => $title]);
    }



    public function store(Request $request)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
        'torneo' => 'required|unique:torneos,torneo|max:125',
        'deportes_id' => 'required|exists:deportes,id',
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

      $activo = 0;
      if ($request->activo=='on') { $activo = 1; }

      $torneo = new Torneo;
      $torneo->torneo = $request->torneo;
      $torneo->deportes_id = $request->deportes_id;
      $torneo->activo = $activo;
      $torneo->save();
      return redirect('/torneos');



    }





  public function edit($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $torneo = Torneo::find($id);
    $title = "Torneo Editar";
    return view('torneos.edit', ['torneo' => $torneo, 'title' => $title ]);
  }



  public function update(Request $request, $id)
  {
    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $validator = Validator::make($request->all(), [
      'torneo' => 'required|unique:torneos,torneo,'.$id . '|max:125',
      'deportes_id' => 'required|exists:deportes,id',

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

    $activo = 0;
    if ($request->activo=='on') { $activo = 1; }

    $torneo = Torneo::find($id);
    $torneo->torneo = $request->torneo;
    $torneo->deportes_id = $request->deportes_id;
    $torneo->activo = $activo;
    $torneo->save();
    return redirect('/torneos');

  }




  public function destroy($id)
  {
    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $request = new Request([
      'id' => $id,
    ]);

    $validator = Validator::make($request->all(), [
      'id' => 'required|exists:torneos,id',
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

    $torneo = Torneo::find($id);
    $torneo->delete();
    return redirect('/torneos/');


  }




    public function finder(Request $request){

      $torneos = Torneo::where('torneo', 'like', '%'. $request->buscar . '%')->paginate(25);


      $title = "Torneos Buscando: " . $request->buscar;
      return view('torneos.index', ['torneos' => $torneos, 'title' => $title ]);

    }



  public function search(Request $request){
    $term = $request->term;

    //  echo $term;
    //  die;

    $datos = Torneo::where('name', 'like', '%'. $term . '%')->get();
    $adevol = array();
    if (count($datos) > 0) {
      foreach ($datos as $dato)
      {
        $adevol[] = array(
          'id' => $dato->id,
          'value' => $dato->torneo,
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




  public function show($id)
  {

    $request = new Request([
      'id' => $id,
    ]);

    $validator = Validator::make($request->all(), [
      'id' => 'required|exists:torneos,id',
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

    $torneo = Torneo::find($id);
    $title='Torneo Ver';
    return view('torneos.show', ['torneo' => $torneo, 'title' => $title]);

  }




}
