<?php

namespace App\Http\Controllers;


use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\Mutual;
use App\Institucion;
use Carbon\Carbon;


class MutualController extends Controller
{

  public function index($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $institucion = Institucion::find($id);


    $mutuals = Mutual::where('institucions_id', $id)->paginate(50);
    $title = "Mutuales";
    return view('mutuals.index', ['mutuals' => $mutuals,
                                       'institucion' => $institucion,
                                       'title' => $title ]);

  }



    public function create($id)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $institucion = Institucion::find($id);

      $title = "Crear nueva Mutual en " . $institucion->institucion;
      return view('mutuals.create', ['institucion' => $institucion, 'title' => $title]);
    }



    public function store(Request $request)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
        'institucions_id' => 'required|exists:institucions,id',
        'matricula' => 'required',
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

      $date = $request->date;

      $dia = substr($date,0,2);
      $mes = substr($date,3,2);
      $anio = substr($date,6,4);

      $date = Carbon::createFromDate($anio, $mes, $dia)->setTime(0, 0, 0);


      $mutual = new Mutual;
      $mutual->institucions_id = $request->institucions_id;
      $mutual->mutual = $request->mutual;
      $mutual->matricula = $request->matricula;
      $mutual->fecha_constitucion = $date;
      $mutual->activo = $activo;
      $mutual->save();
      return redirect('/institucions/' . $request->institucions_id . '/mutuals');



    }





  public function edit($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $mutual = Mutual::find($id);


    $title = "Mutual Editar";
    return view('mutuals.edit', ['mutual' => $mutual, 'title' => $title ]);
  }



  public function update(Request $request, $id)
  {
    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $validator = Validator::make($request->all(), [
      'matricula' => 'required',
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

    $date = $request->date;

    $dia = substr($date,0,2);
    $mes = substr($date,3,2);
    $anio = substr($date,6,4);

    $date = Carbon::createFromDate($anio, $mes, $dia)->setTime(0, 0, 0);

    $mutual = Mutual::find($id);
    $institucions_id = $mutual->institucions_id;
    $mutual->mutual = $request->mutual;
    $mutual->matricula = $request->matricula;
    $mutual->fecha_constitucion = $date;
    $mutual->activo = $activo;
    $mutual->save();
    return redirect('/institucions/' . $institucions_id . '/mutuals');


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
      'id' => 'required|exists:mutuals,id',
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

    $mutual = Mutual::find($id);
    $mutual->delete();
    return redirect('/mutuals/');


  }




    public function finder(Request $request){

      $mutuals = Mutual::where('mutual', 'like', '%'. $request->buscar . '%')->paginate(25);


      $title = "Mutual buscando: " . $request->buscar;
      return view('mutuals.index', ['mutuals' => $mutuals, 'title' => $title ]);

    }



  public function search(Request $request){
    $term = $request->term;

    //  echo $term;
    //  die;

    $datos = Mutual::where('name', 'like', '%'. $term . '%')->get();
    $adevol = array();
    if (count($datos) > 0) {
      foreach ($datos as $dato)
      {
        $adevol[] = array(
          'id' => $dato->id,
          'value' => $dato->mutual,
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
      'id' => 'required|exists:mutuals,id',
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

    $mutual = Mutual::find($id);
    $title='Mutual ver';
    return view('mutuals.show', ['mutual' => $mutual, 'title' => $title]);

  }

}
