@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><h1>{{ $title }}</h1></div>

        <div class="card-body">

          <div class="container">
            <div class="row">

              <div class="col-12">

                {{ Form::open(array('route' => 'alumnos.store',  'autocomplete' => 'off')) }}
                <div class="form-group">
                  <label for="">Apellido</label>
                  <input type="apellido" class="form-control" name="apellido" id="apellido" placeholder="Apellido">
                </div>
                <div class="form-group">
                  <label for="">Nombre</label>
                  <input type="nombre" class="form-control" name="nombre" id="nombre" placeholder="Nombre">
                </div>
                <div class="form-group">
                  <label for="">Dni</label>
                  <input type="dni" class="form-control" name="dni" id="dni" placeholder="Dni">
                </div>


                <div class="form-group">
                  <label for="activo">Activo</label>
                  <input type="checkbox" data-toggle="toggle" name="activo" id="activo" checked>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
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
