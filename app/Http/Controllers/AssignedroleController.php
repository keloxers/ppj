<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\User;
use App\Role;
use App\Assignedrole;

class AssignedroleController extends Controller
{

    public function index($id)
    {

      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $user = User::find($id);

      $assignedroles = Assignedrole::where('entity_type','App\User')
                                       ->where('entity_id',$id)->get();


      $title = "Roles del usuario: " . $user->name;
      return view('assignedroles.index', ['assignedroles' => $assignedroles, 'user' => $user, 'title' => $title ]);

    }



    public function store(Request $request)
    {
      if (Bouncer::cannot('Users')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
                  'roles_id' => 'required|exists:roles,id',
                  'users_id' => 'required|exists:users,id',
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


      $assignedrole = new Assignedrole;
      $assignedrole->role_id = $request->roles_id;
      $assignedrole->entity_id = $request->users_id;
      $assignedrole->entity_type = 'App\User';
      $assignedrole->save();
      return redirect('/users/' . $request->users_id . '/assignedroles');


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
}
