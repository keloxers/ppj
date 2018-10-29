<?php

namespace App\Http\Controllers;


use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\Cooperativa;
use App\Institucion;
use Carbon\Carbon;


class CooperativaController extends Controller
{

  public function index($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $institucion = Institucion::find($id);


    $cooperativas = Cooperativa::where('institucions_id', $id)->paginate(50);
    $title = "Cooperativas";
    return view('cooperativas.index', ['cooperativas' => $cooperativas,
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

      $title = "Crear nueva cooperativa en " . $institucion->institucion;
      return view('cooperativas.create', ['institucion' => $institucion, 'title' => $title]);
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


      $cooperativa = new Cooperativa;
      $cooperativa->institucions_id = $request->institucions_id;
      $cooperativa->cooperativa = $request->cooperativa;
      $cooperativa->matricula = $request->matricula;
      $cooperativa->fecha_constitucion = $date;
      $cooperativa->activo = $activo;
      $cooperativa->save();
      return redirect('/institucions/' . $request->institucions_id . '/cooperativas');



    }





  public function edit($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $cooperativa = Cooperativa::find($id);


    $title = "Cooperativa Editar";
    return view('cooperativas.edit', ['cooperativa' => $cooperativa, 'title' => $title ]);
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

    $cooperativa = Cooperativa::find($id);
    $institucions_id = $cooperativa->institucions_id;
    $cooperativa->cooperativa = $request->cooperativa;
    $cooperativa->matricula = $request->matricula;
    $cooperativa->fecha_constitucion = $date;
    $cooperativa->activo = $activo;
    $cooperativa->save();
    return redirect('/institucions/' . $institucions_id . '/cooperativas');


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
      'id' => 'required|exists:cooperativas,id',
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

    $cooperativa = Cooperativa::find($id);
    $cooperativa->delete();
    return redirect('/cooperativas/');


  }




    public function finder(Request $request){

      $cooperativas = Cooperativa::where('cooperativa', 'like', '%'. $request->buscar . '%')->paginate(25);


      $title = "cooperativas buscando: " . $request->buscar;
      return view('cooperativas.index', ['cooperativas' => $cooperativas, 'title' => $title ]);

    }



  public function search(Request $request){
    $term = $request->term;

    //  echo $term;
    //  die;

    $datos = Cooperativa::where('name', 'like', '%'. $term . '%')->get();
    $adevol = array();
    if (count($datos) > 0) {
      foreach ($datos as $dato)
      {
        $adevol[] = array(
          'id' => $dato->id,
          'value' => $dato->cooperativa,
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
      'id' => 'required|exists:cooperativas,id',
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

    $cooperativa = Cooperativa::find($id);
    $title='Cooperativa ver';
    return view('cooperativas.show', ['cooperativa' => $cooperativa, 'title' => $title]);

  }

}
