<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\Comision;
use App\Institucion;
use App\Cargoscomision;

class CargoscomisionController extends Controller
{

  public function index()
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $cargoscomisions = Cargoscomision::paginate(25);
    $title = "Cargos comision ";
    return view('cargoscomisions.index', ['cargoscomisions' => $cargoscomisions,'title' => $title ]);

  }



    public function create()
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $title = "Agregar Cargos comision";
      return view('cargoscomisions.create', ['title' => $title]);
    }



    public function store(Request $request)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
        'cargoscomision' => 'required|max:125',
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

      $posicion = 1;
      if ($request->posicion<>'') { $posicion = $request->posicion; }

      $cargoscomision = new Cargoscomision;
      $cargoscomision->cargoscomision = $request->cargoscomision;
      $cargoscomision->posicion = $posicion;
      $cargoscomision->activo = $activo;
      $cargoscomision->save();
      return redirect('/cargoscomisions');



    }





  public function edit($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $cargoscomision = Cargoscomision::find($id);
    $title = "Cargos Comision Editar";
    return view('cargoscomisions.edit', ['cargoscomision' => $cargoscomision, 'title' => $title ]);
  }



  public function update(Request $request, $id)
  {
    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $validator = Validator::make($request->all(), [
      'cargoscomision' => 'required|unique:cargoscomisions,cargoscomision,'.$id . '|max:125',

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

    $posicion = 1;
    if ($request->posicion<>'') { $posicion = $request->posicion; }

    $cargoscomision = Cargoscomision::find($id);
    $cargoscomision->cargoscomision = $request->cargoscomision;
    $cargoscomision->posicion = $posicion;
    $cargoscomision->activo = $activo;
    $cargoscomision->save();
    return redirect('/cargoscomisions');




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

    $datos = Cargoscomision::where('cargoscomision', 'like', '%'. $term . '%')->get();
    $adevol = array();
    if (count($datos) > 0) {
      foreach ($datos as $dato)
      {
        $adevol[] = array(
          'id' => $dato->id,
          'value' => $dato->cargoscomision,
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
