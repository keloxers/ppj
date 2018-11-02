<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Bouncer;
use Auth;

use App\Votante;

class UserController extends Controller
{

    public function index()
    {

        if (Bouncer::cannot('Users')) {
          $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
          return redirect()->back()->with('errors', $errors)->withInput();
        }


      $users = User::paginate(25);
      $title = "Usuarios";
      return view('users.index', ['users' => $users, 'title' => $title ]);

    }


    public function show($id)
    {
        // Bouncer::allow('admin')->to('ban-users');
        $user = Auth::user();
        // var_dump($user);

        // $user->assign('admin');

        // Bouncer::allow('SuperAdmin')->to('ban-users');

        // echo Bouncer::is($user)->a('admin');

        // echo $user->getRoles();

        // $abilities = $user->getAbilities();
        //
        // echo $abilities;


        // consulta si tiene permiso
        //echo Bouncer::can('ban-users');





    }



      public function habilitar($id)
      {

          Votante::where('users_id', $id)->update(['activo' => 0]);

          $user = User::find($id);
          $user->activo=true;
          $user->save();

          return redirect('/votantes/create');
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
        $datos = User::where('name', 'like', '%'. $request->term . '%')->get();
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


      public function searchmesa(Request $request){
        $term = $request->term;
        $datos = User::where('name', 'like', '%'. $request->term . '%')
                     ->where('activo', true)
                     ->where('esmesa', true)
                     ->get();
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
