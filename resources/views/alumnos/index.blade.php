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
      {{ Form::open(array('route' => 'alumnos.finder', 'class' => 'form-inline', 'role' => 'form')) }}
      <div class="form-group mb-2">
        <input type="text" class="form-control" name="buscar" id="buscar" value="">
        {{ Form::hidden('alumnos_id', '', array('id' => 'alumnos_id', 'name' => 'alumnos_id')) }}
      </div>
      <button type="submit" class="btn btn-primary mb-2">Buscar</button>
      </form>
    </div>
    <div class="col-6 text-right">
      <a href="/alumnos/create">
        <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
      </a>
    </div>
  </div>

  @if($alumnos)

  <table class="table">
    <thead class="thead-light">
      <tr>
        <th scope="col">Alumno</th>
        <th scope="col">Dni</th>
        <th scope="col">Estado</th>
        <th scope="col">Opciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($alumnos as $alumno)
      <tr>
        <td>{{ $alumno->apellido}}, {{ $alumno->nombre}}</td>
        <td>{{ $alumno->dni }}</td>
        <td>
          @if ($alumno->activo)
            <span class="badge badge-success">Activo</span>
          @else
            <span class="badge badge-danger">Inactivo</span>
          @endif
        </td>
        <td>
          <a href="/alumnos/{{ $alumno->id }}/edit"><i class="fas fa-edit"></i></a>
          <a href="/alumnos/{{ $alumno->id }}"><i class="fas fa-eye"></i></a>


        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $alumnos->links() }}

  @endif

</div>
@endsection
