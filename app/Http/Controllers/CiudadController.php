<?php

namespace App\Http\Controllers;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Validator;
use App\Provincia;
use App\Ciudad;
use Bouncer;

class CiudadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

        $provincia = Provincia::find($id);
        $ciudads = Ciudad::where('provincias_id', $provincia->id)->paginate(25);
        $title = "Provincia: " . $provincia->provincia;
        return view('ciudads.index', ['provincia' => $provincia,
                                           'ciudads' => $ciudads,
                                           'title' => $title ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {

      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }


      $provincia = Provincia::find($id);
      $title = "Ciudad";
      return view('ciudads.create', ['provincia' => $provincia,
                                      'title' => $title]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }


              $activo=1;
              if($request->activo=="") {
                $activo=0;
              };


              $validator = Validator::make($request->all(), [
                          'ciudad' => 'required|unique:ciudads|max:125',
                          'provincias_id' => 'required|exists:provincias,id|max:125',

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


              $ciudad = new Ciudad;
              $ciudad->ciudad = $request->ciudad;
              $ciudad->codigopostal = $request->codigopostal;
              $ciudad->provincias_id = $request->provincias_id;
              $ciudad->activo = $activo;
              $ciudad->save();
              return redirect('/provincias/' . $ciudad->provincias_id . '/ciudads');



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }


      $ciudad = Ciudad::find($id);
      $title = "Ciudad";
      return view('ciudads.show', ['ciudad' => $ciudad,'title' => $title]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $ciudad = Ciudad::find($id);
      $title = "Ciudad";
      return view('ciudads.edit', ['ciudad' => $ciudad,'title' => $title]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $activo=1;
      if($request->activo=="") {
        $activo=0;
      };


      $ciudad = Ciudad::find($id);
      $ciudad->ciudad = $request->ciudad;
      $ciudad->codigopostal = $request->codigopostal;
      $ciudad->provincias_id = $request->provincias_id;
      $ciudad->activo = $activo;
      $ciudad->save();
      return redirect('/provincias/' . $ciudad->provincias_id . '/ciudads');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      if (Bouncer::cannot('Configuracion')) {
        $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
        return redirect()->back()->with('errors', $errors)->withInput();
      }

      $ciudad = Ciudad::find($id);
      $provincias_id = $ciudad->provincias_id;
      $ciudad->delete();

      return redirect('/provincias/' . $provincias_id . '/ciudads');
    }

   public function finder(Request $request)
   {
       //
       if (Bouncer::cannot('Configuracion')) {
         $errors[] = 'No tiene autorizacion para ingresar a este modulo.';
         return redirect()->back()->with('errors', $errors)->withInput();
       }


       $ciudads = Ciudad::where('ciudad', 'like', '%'. $request->buscar . '%')->paginate(15);
       $title = "ciudad: buscando " . $request->buscar;
       return view('ciudads.index', ['ciudads' => $ciudads, 'title' => $title ]);

   }


   public function search(Request $request){
        $term = $request->term;

       //  echo $term;
       //  die;

        $datos = Ciudad::where('ciudad', 'like', '%'. $request->term . '%')->where('activo', true)->get();
        $adevol = array();
        if (count($datos) > 0) {
            foreach ($datos as $dato)
                {
                    $provincia = Provincia::find($dato->provincias_id);
                    $adevol[] = array(
                        'id' => $dato->id,
                        'value' => $dato->ciudad . ', ' . $provincia->provincia,
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
