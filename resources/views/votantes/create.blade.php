@extends('layouts.app')

@section('content')
<?php
  use App\Votante;
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
$( function() {
  $.noConflict();
  $( "#user" ).autocomplete({
    source: "/users/searchmesa",
    minLength: 1,
    select: function( event, ui ) {
      $('#users_id').val( ui.item.id );
    }
  });

  $( "#alumno" ).autocomplete({
    source: "/alumnos/search",
    minLength: 1,
    select: function( event, ui ) {
      $('#alumnos_id').val( ui.item.id );
    }
  });


} );
</script>



<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h1>{{ $title }}</h1>
          <h3>
            <a href='/votantes/create'>
              Actualizar estados de las mesas
            </a>
          </h3>
          <br>
          <table class="table">
            <thead class="thead-light">
              <tr>
                <th scope="col">Mesa</th>
                <th scope="col">Votante</th>
                <th scope="col">Estado</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr>
                <td>{{ $user->name}}</td>
                <?php
                      $votante = Votante::where('users_id', '=' ,$user->id)
                                        ->where('activo', '=' , 1)
                                        ->first();
                ?>
                <td>
                  @if ($votante)
                  {{ $votante->alumnos->apellido}}, {{ $votante->alumnos->nombre}} - Dni: {{ $votante->alumnos->dni}}
                  @endif
                </td>
                <td>
                  @if ($user->activo)
                    <span class="badge badge-success">Libre</span>
                  @else
                    <span class="badge badge-danger">Votando</span>
                  @endif
                </td>
                <td>
                  @if (!$user->activo)
                  <a href="/users/{{ $user->id }}/habilitar"><i class="fas fa-close"></i> Habilitar</a>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </div>

        <div class="card-body">

          <div class="container">
            <div class="row">

              <div class="col-12">

                {{ Form::open(array('route' => 'votantes.store',  'autocomplete' => 'off')) }}
                <div class="form-group">
                  <label for="">Usuario</label>
                  <input type="user" class="form-control" name="user" id="user" placeholder="Usuario">
                  {{ Form::hidden('users_id', '', array('id' => 'users_id', 'name' => 'users_id')) }}
                </div>
                <div class="form-group">
                  <label for="">Alumno</label>
                  <input type="alumno" class="form-control" name="alumno" id="alumno" placeholder="Alumno">
                  {{ Form::hidden('alumnos_id', '', array('id' => 'alumnos_id', 'name' => 'alumnos_id')) }}
                </div>
                <button type="submit" class="btn btn-primary">Habilitar</button>
                {{ Form::close() }}
              </div>

            </div>

          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@stop
