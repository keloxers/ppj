@extends('layouts.app')

@section('content')

<?php
  use App\Categoria;
  use App\Proyecto;
  use App\Voto;
?>

@if (isset($categoria))

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h1>{{ $categoria->categoria }}</h1></div>

                <div class="card-body">

                  <h2>Elegí una opción</h2>
                  <br>
                  @foreach ($proyectos as $key => $proyecto)

                    <a href='/voto/{{$categoria->id}}/{{$votante->id}}/{{$proyecto->id}}'>
                    <button type="button" class="btn btn-danger btn-lg"><strong>{{$proyecto->proyecto}}</strong></button><br><br>
                    </a>

                  @endforeach

                </div>
            </div>
        </div>
    </div>
</div>

@else


<meta http-equiv="refresh" content="6;url=/home" />


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if ( Auth::user()->esfiscal ==1)
                  <div class="card-header"><h1>Resultados</h1></div>

                  <?php
                    $categorias = Categoria::orderby('id','asc')->get();


                    foreach ($categorias as $categoria) {
                      $total_votantes = 0;
                      echo '<h3>' . $categoria->categoria . '</h3><br>';
                      $proyectos = Proyecto::where('categorias_id',$categoria->id)
                                           ->orderby('id','asc')->get();


                       foreach ($proyectos as $proyecto) {

                         $votos = Voto::where('categorias_id', $categoria->id)
                                     ->where('proyectos_id', $proyecto->id)->count();

                          echo $proyecto->proyecto . " votos: " . $votos . '<br>';
                          $total_votantes += $votos;

                       }

                       echo '<br><br><hr>';
                       echo 'Total votantes: ' . $total_votantes;

                       echo '<hr><br><br>';
                    }


                  ?>


                @else
                  <div class="card-header"><h1>Esperando a que se habilite esta mesa</h1></div>
                @endif

            </div>
        </div>
    </div>
</div>

@endif


@endsection
