<?php

namespace App\Http\Controllers;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use Bouncer;
use App\Institucion;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class InstitucionController extends Controller
{

  public function index()
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $institucions = Institucion::paginate(25);
    $title = "Instituciones";
    return view('institucions.index', ['institucions' => $institucions, 'title' => $title ]);

  }



    public function create()
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $title = "Institucion crear";
      return view('institucions.create', ['title' => $title]);
    }



    public function store(Request $request)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $validator = Validator::make($request->all(), [
        'institucion' => 'required|unique:institucions,institucion|max:125',
        'ciudads_id' => 'required|exists:ciudads,id',
        'nivel_educativo' => 'required',

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

      $institucion = new Institucion;
      $institucion->institucion = $request->institucion;
      $institucion->nivel_educativo = $request->nivel_educativo;
      $institucion->ciudads_id = $request->ciudads_id;
      $institucion->activo = $activo;

      if ($request->hasFile('file')) {
        // recibe la imagen y la achica.
        $file = $request->file('file');
        $url_foto = $file->hashName('public/institucions');
        $image = Image::make($file);
        $image->widen(200);
        Storage::put($url_foto, (string) $image->encode());

        $institucion->url_foto = $url_foto;

      } else {
        $institucion->url_foto = '';
      }


      $institucion->save();
      return redirect('/institucions');



    }





  public function edit($id)
  {

    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $institucion = Institucion::find($id);
    $title = "Institucion Editar";
    return view('institucions.edit', ['institucion' => $institucion, 'title' => $title ]);
  }



  public function update(Request $request, $id)
  {
    if (Bouncer::cannot('Configuracion')) {
      $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
      return redirect()->back()->with('errors', $errors)->withInput();
    }

    $validator = Validator::make($request->all(), [
      'institucion' => 'required|unique:institucions,institucion,'.$id . '|max:125',
      'ciudads_id' => 'required|exists:ciudads,id',
      'nivel_educativo' => 'required',


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

    $institucion = Institucion::find($id);
    $institucion->institucion = $request->institucion;
    $institucion->nivel_educativo = $request->nivel_educativo;
    $institucion->ciudads_id = $request->ciudads_id;
    $institucion->activo = $activo;

    if ($request->hasFile('file')) {
      // recibe la imagen y la achica.
      $file = $request->file('file');
      $url_foto = $file->hashName('public/jugadors');
      $image = Image::make($file);
      $image->widen(200);
      Storage::put($url_foto, (string) $image->encode());

      $institucion->url_foto = $url_foto;

    } else {
      $institucion->url_foto = '';
    }

    $institucion->save();
    return redirect('/institucions');

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
      'id' => 'required|exists:institucions,id',
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

    $institucion = Institucion::find($id);
    $institucion->delete();
    return redirect('/institucions/');


  }




    public function finder(Request $request){

      $institucions = Institucion::where('institucion', 'like', '%'. $request->buscar . '%')->paginate(25);


      $title = "Institucion buscando: " . $request->buscar;
      return view('institucions.index', ['institucions' => $institucions, 'title' => $title ]);

    }



  public function search(Request $request){
    $term = $request->term;

    //  echo $term;
    //  die;

    $datos = Institucion::where('name', 'like', '%'. $term . '%')->get();
    $adevol = array();
    if (count($datos) > 0) {
      foreach ($datos as $dato)
      {
        $adevol[] = array(
          'id' => $dato->id,
          'value' => $dato->institucion,
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
      'id' => 'required|exists:institucions,id',
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

    $institucion = Institucion::find($id);
    $title='Institucion ver';
    return view('institucions.show', ['institucion' => $institucion, 'title' => $title]);

  }



    public function eliminarfoto($id)
    {

      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $institucion = Institucion::find($id);

      Storage::delete($institucion->url_foto);
      $institucion->url_foto='';
      $institucion->save();
      return redirect('/institucions/' . $institucion->id . '/edit');
    }


}
