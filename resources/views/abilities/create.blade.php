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

                {{ Form::open(array('route' => 'abilities.store',  'autocomplete' => 'off')) }}
                <div class="form-group">
                  <label for="">Name</label>
                  <input type="name" class="form-control" name="name" id="name" placeholder="Ingrese el name">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Title</label>
                  <input type="title" class="form-control" name="title" id="title" placeholder="Ingrese el title">
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
