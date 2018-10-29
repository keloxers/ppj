<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\User;
use App\Role;
use App\Permission;

class PermissionController extends Controller
{

    public function index($id)
    {

      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }


      $role = Role::find($id);
      $permissions = Permission::where('entity_type','roles')
                                       ->where('entity_id',$id)->get();
      $title = "Abilities por Rol: " . $role->name;
      return view('permissions.index', ['permissions' => $permissions, 'role' => $role, 'title' => $title ]);

    }



    public function store(Request $request)
    {
      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }


      $validator = Validator::make($request->all(), [
                  'roles_id' => 'required|exists:roles,id',
                  'abilities_id' => 'required|exists:abilities,id',
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


      $permission = new Permission;
      $permission->ability_id = $request->abilities_id;
      $permission->entity_id = $request->roles_id;
      $permission->entity_type = 'roles';
      $permission->save();
      return redirect('/roles/' .  $request->roles_id . '/permissions');



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

      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $request = new Request([
          'id' => $id,
      ]);

      $validator = Validator::make($request->all(), [
                  'id' => 'required|exists:permissions,id',
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

      $permission = Permission::find($id);
      $roles_id = $permission->entity_id;
      $permission->delete();
      return redirect('/roles/' .  $roles_id . '/permissions');


    }
}
