<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\User;
use App\Role;

class RoleController extends Controller
{

    public function index()
    {

      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }


      $roles = Role::paginate(25);
      $title = "Roles";
      return view('roles.index', ['roles' => $roles, 'title' => $title ]);

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

      $title = "Crear Role";
      return view('roles.create', ['title' => $title]);
    }



    public function store(Request $request)
    {
      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
                  'name' => 'required|unique:roles,name|max:125',
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


      $role = new Role;
      $role->name = $request->name;
      $role->title = $request->name;
      $role->save();
      return redirect('/roles');



    }


    public function destroy($id)
    {
      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $request = new Request([
          'id' => $id,
      ]);

      $validator = Validator::make($request->all(), [
                  'id' => 'required|exists:roles,id',
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

      $role = Role::find($id);
      $role->delete();
      return redirect('/roles/');


    }

       public function search(Request $request){
            $term = $request->term;

           //  echo $term;
           //  die;

            $datos = Role::where('name', 'like', '%'. $term . '%')->get();
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
