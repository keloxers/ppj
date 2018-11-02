@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <h1>{{$title}}</h1>
      <br>
    </div>
  </div>

  <div class="row">
    <div class="col-6">
      {{ Form::open(array('route' => 'votantes.finder', 'class' => 'form-inline', 'role' => 'form')) }}
      <div class="form-group mb-2">
        <input type="text" class="form-control" name="buscar" id="buscar" value="">
        {{ Form::hidden('votantes_id', '', array('id' => 'votantes_id', 'name' => 'votantes_id')) }}
      </div>
      <button type="submit" class="btn btn-primary mb-2">Buscar</button>
      </form>
    </div>
    <div class="col-6 text-right">
      <a href="/votantes/create">
        <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
      </a>
    </div>
  </div>



</div>
@endsection
