<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\User;
use App\Actividad;
use App\Cooperativasactividad;
use App\Cooperativa;

class CooperativasactividadController extends Controller

{

    public function index($id)
    {

      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $cooperativa = Cooperativa::find($id);

      $cooperativasactividads = Cooperativasactividad::where('cooperativas_id',$id)->paginate(25);

      $title = "Cooperativa: " . $cooperativa->cooperativa;

      return view('Cooperativasactividads.index',
                                                  ['cooperativasactividads' => $cooperativasactividads,
                                                   'cooperativa' => $cooperativa,
                                                   'title' => $title ]);
    }

    public function store(Request $request)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
                  'cooperativas_id' => 'required|exists:cooperativas,id',
                  'actividads_id' => 'required|exists:actividads,id',
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


      $cooperativasactividads = new Cooperativasactividad;
      $cooperativasactividads->actividads_id = $request->actividads_id;
      $cooperativasactividads->cooperativas_id = $request->cooperativas_id;
      $cooperativasactividads->save();
      return redirect('/cooperativas/' . $request->cooperativas_id . '/cooperativasactividads');


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
                  'id' => 'required|exists:Cooperativasactividads,id',
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

      $cooperativasactividads = Cooperativasactividad::find($id);
      $cooperativas_id = $cooperativasactividads->cooperativas_id;
      $cooperativasactividads->delete();
      return redirect('/cooperativas/' . $cooperativas_id . '/cooperativasactividads');


    }
}
