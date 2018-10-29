<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\User;
use App\Actividad;
use App\Mutualsactividad;
use App\Mutual;

class MutualsactividadController extends Controller

{

    public function index($id)
    {

      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $mutual = Mutual::find($id);

      $mutualsactividads = Mutualsactividad::where('mutuals_id',$id)->paginate(25);

      $title = "mutual: " . $mutual->mutual;

      return view('mutualsactividads.index',
                                                  ['mutualsactividads' => $mutualsactividads,
                                                   'mutual' => $mutual,
                                                   'title' => $title ]);
    }

    public function store(Request $request)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
                  'mutuals_id' => 'required|exists:mutuals,id',
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


      $mutualsactividads = new Mutualsactividad;
      $mutualsactividads->actividads_id = $request->actividads_id;
      $mutualsactividads->mutuals_id = $request->mutuals_id;
      $mutualsactividads->save();
      return redirect('/mutuals/' . $request->mutuals_id . '/mutualsactividads');


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
                  'id' => 'required|exists:mutualsactividads,id',
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

      $mutualsactividads = Mutualsactividad::find($id);
      $mutuals_id = $mutualsactividads->mutuals_id;
      $mutualsactividads->delete();
      return redirect('/mutuals/' . $mutuals_id . '/mutualsactividads');


    }
}
