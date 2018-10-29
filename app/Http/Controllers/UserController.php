<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Bouncer;
use Auth;

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
