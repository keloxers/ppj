<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\Comision;
use App\Institucion;

class ComisionController extends Controller
{

  public function index($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $institucion = Institucion::find($id);

    $comisions = Comision::where('institucions_id', $institucion->id)->paginate(25);
    $title = "Comision del " . $institucion->institucion;
    return view('comisions.index', ['comisions' => $comisions, 'institucion' => $institucion,'title' => $title ]);

  }



    public function create($id)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $institucion = Institucion::find($id);

      $title = "Agregar autoridad a " . $institucion->institucion;
      return view('comisions.create', ['institucion' => $institucion, 'title' => $title]);
    }



    public function store(Request $request)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
        'institucions_id' => 'required|exists:institucions,id',
        'cargoscomisions_id' => 'required|exists:cargoscomisions,id',
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

      $comision = new Comision;
      $comision->institucions_id = $request->institucions_id;
      $comision->nombre = $request->nombre;
      $comision->cargoscomisions_id = $request->cargoscomisions_id;
      $comision->save();
      return redirect('/institucions/' . $comision->institucions_id . '/comisions');



    }





  public function edit($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $comision = Comision::find($id);
    $title = "comision Editar";
    return view('comisions.edit', ['comision' => $comision, 'title' => $title ]);
  }



  public function update(Request $request, $id)
  {
    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $validator = Validator::make($request->all(), [
      'comision' => 'required|unique:comisions,comision,'.$id . '|max:125',

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

    $comision = Comision::find($id);
    $comision->comision = $request->comision;
    $comision->activo = $activo;
    $comision->save();
    return redirect('/comisions');

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
      'id' => 'required|exists:comisions,id',
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

    $comision = Comision::find($id);
    $comision->delete();
    return redirect('/comisions/');


  }




    public function finder(Request $request){

      $comisions = Comision::where('comision', 'like', '%'. $request->buscar . '%')->paginate(25);


      $title = "comision buscando: " . $request->buscar;
      return view('comisions.index', ['comisions' => $comisions, 'title' => $title ]);

    }



  public function search(Request $request){
    $term = $request->term;

    //  echo $term;
    //  die;

    $datos = Comision::where('name', 'like', '%'. $term . '%')->get();
    $adevol = array();
    if (count($datos) > 0) {
      foreach ($datos as $dato)
      {
        $adevol[] = array(
          'id' => $dato->id,
          'value' => $dato->comision,
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
      'id' => 'required|exists:comisions,id',
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

    $comision = Comision::find($id);
    $title='comision ver';
    return view('comisions.show', ['comision' => $comision, 'title' => $title]);

  }




}
