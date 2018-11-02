<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

  use App\Votante;
  use App\Alumno;
  use App\Categoria;
  use App\Proyecto;
  use App\Voto;
  use App\User;
  use Auth;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {



      $user = User::find(Auth::user()->id);

      if (!$user->esmesa) {
        return view('home');
      }


      $votante = Votante::where('users_id', $user->id)
                        ->where('activo',1)
                        ->first();

      if (!$votante) {
        return view('home');
      }


      $alumno = Alumno::where('id', $votante->alumnos_id)
                        ->where('activo',1)
                        ->first();

      if (!$alumno) {
        return view('home');
      }


      $categorias = Categoria::orderby('id','asc')->get();


      foreach ($categorias as $categoria) {

        $voto = Voto::where('categorias_id', $categoria->id)
                    ->where('votantes_id', $votante->id)
                    ->first();

        if (!$voto) {
          
          $proyectos = Proyecto::where('categorias_id', $categoria->id)->get();
          return view('home', ['categoria' => $categoria,
                               'votante' => $votante,
                               'proyectos' => $proyectos ]);
        }


      }


        return view('home');
    }
}
