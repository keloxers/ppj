<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\User;
use App\Actividad;
use App\Actividadsproductostipo;

class ActividadsproductostipoController extends Controller
{

    public function index($id)
    {

      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $actividad = Actividad::find($id);

      $actividadsproductostipos = Actividadsproductostipo::where('actividads_id',$id)->paginate(25);

      $title = "Actividad: " . $actividad->actividad;

      return view('actividadsproductostipos.index',
                                                  ['actividadsproductostipos' => $actividadsproductostipos,
                                                   'actividad' => $actividad,
                                                   'title' => $title ]);

    }



    public function store(Request $request)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
                  'actividads_id' => 'required|exists:actividads,id',
                  'productostipos_id' => 'required|exists:productostipos,id',
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


      $actividadsproductostipo = new Actividadsproductostipo;
      $actividadsproductostipo->actividads_id = $request->actividads_id;
      $actividadsproductostipo->productostipos_id = $request->productostipos_id;
      $actividadsproductostipo->save();
      return redirect('/actividads/' . $request->actividads_id . '/actividadsproductostipos');


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
                  'id' => 'required|exists:actividadsproductostipos,id',
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

      $actividadsproductostipo = Actividadsproductostipo::find($id);
      $actividads_id = $actividadsproductostipo->actividads_id;
      $actividadsproductostipo->delete();
      return redirect('/actividads/' . $actividads_id . '/actividadsproductostipos');


    }
}
