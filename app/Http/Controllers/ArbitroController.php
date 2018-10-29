<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\Arbitro;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ArbitroController extends Controller
{

  public function index()
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }


    $arbitros = Arbitro::paginate(25);
    $title = "Arbitros";
    return view('arbitros.index', ['arbitros' => $arbitros, 'title' => $title ]);

  }



    public function create()
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $title = "Arbitro crear";
      return view('arbitros.create', ['title' => $title]);
    }



    public function store(Request $request)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
        'arbitro' => 'required|unique:arbitros,arbitro|max:125',
        'dni' => 'required|unique:arbitros,dni|max:8',
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

      $activo = 0;
      if ($request->activo=='on') { $activo = 1; }

      $arbitro = new Arbitro;
      $arbitro->arbitro = $request->arbitro;
      $arbitro->dni = $request->dni;
      $arbitro->activo = $activo;

      if ($request->hasFile('file')) {
        // recibe la imagen y la achica.
        $file = $request->file('file');
        $url_foto = $file->hashName('public/arbitros');
        $image = Image::make($file);
        $image->widen(200);
        Storage::put($url_foto, (string) $image->encode());

        $arbitro->url_foto = $url_foto;

      } else {
        $arbitro->url_foto = '';
      }

      $arbitro->save();
      return redirect('/arbitros');



    }





  public function edit($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $arbitro = Arbitro::find($id);
    $title = "Arbitro Editar";
    return view('arbitros.edit', ['arbitro' => $arbitro, 'title' => $title ]);
  }



  public function update(Request $request, $id)
  {
    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $validator = Validator::make($request->all(), [
      'arbitro' => 'required|unique:arbitros,arbitro,'.$id . '|max:125',
      'dni' => 'required|unique:arbitros,dni,'.$id . '|max:8',

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

    $activo = 0;
    if ($request->activo=='on') { $activo = 1; }

    $arbitro = Arbitro::find($id);
    $arbitro->arbitro = $request->arbitro;
    $arbitro->dni = $request->dni;
    $arbitro->activo = $activo;

        if ($request->hasFile('file')) {
          // recibe la imagen y la achica.
          $file = $request->file('file');
          $url_foto = $file->hashName('public/arbitros');
          $image = Image::make($file);
          $image->widen(200);
          Storage::put($url_foto, (string) $image->encode());

          $arbitro->url_foto = $url_foto;

        } else {
          $arbitro->url_foto = '';
        }

    $arbitro->save();
    return redirect('/arbitros');

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
      'id' => 'required|exists:arbitros,id',
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

    $arbitro = Arbitro::find($id);
    $arbitro->delete();
    return redirect('/arbitros/');


  }




    public function finder(Request $request){

      $arbitros = Arbitro::where('arbitro', 'like', '%'. $request->buscar . '%')->paginate(25);


      $title = "Arbitros buscando: " . $request->buscar;
      return view('arbitros.index', ['arbitros' => $arbitros, 'title' => $title ]);

    }



  public function search(Request $request){
    $term = $request->term;

    //  echo $term;
    //  die;

    $datos = Arbitro::where('name', 'like', '%'. $term . '%')->get();
    $adevol = array();
    if (count($datos) > 0) {
      foreach ($datos as $dato)
      {
        $adevol[] = array(
          'id' => $dato->id,
          'value' => $dato->arbitro,
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




  public function show($id)
  {

    $request = new Request([
      'id' => $id,
    ]);

    $validator = Validator::make($request->all(), [
      'id' => 'required|exists:arbitros,id',
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

    $arbitro = Arbitro::find($id);
    $title='arbitro ver';
    return view('arbitros.show', ['arbitro' => $arbitro, 'title' => $title]);

  }

  public function eliminarfoto($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $arbitro = Arbitro::find($id);

    Storage::delete($arbitro->url_foto);
    $arbitro->url_foto='';
    $arbitro->save();
    return redirect('/arbitros/' . $arbitro->id . '/edit');
  }



}
