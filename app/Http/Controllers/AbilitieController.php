<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\User;
use App\Role;
use App\Abilitie;

class AbilitieController extends Controller
{

    public function index()
    {
      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $abilities = Abilitie::paginate(25);
      $title = "Abilities";
      return view('abilities.index', ['abilities' => $abilities, 'title' => $title ]);

    }


    public function show($id)
    {
        //
    }

    public function create()
    {
      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $title = "Crear Abilities";
      return view('abilities.create', ['title' => $title]);
    }



    public function store(Request $request)
    {

      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
                  'name' => 'required|unique:abilities,name|max:125',
                  'title' => 'required|max:125',
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


      $abilitie = new Abilitie;
      $abilitie->name = $request->name;
      $abilitie->title = $request->title;
      $abilitie->save();
      return redirect('/abilities');



    }


    public function edit($id)
    {
        //
    }


  public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }



     public function search(Request $request){
          $term = $request->term;

         //  echo $term;
         //  die;

          $datos = Abilitie::where('name', 'like', '%'. $term . '%')->get();
          $adevol = array();
          if (count($datos) > 0) {
              foreach ($datos as $dato)
                  {
                      $adevol[] = array(
                          'id' => $dato->id,
                          'value' => $dato->name,
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
