@extends('layouts.app')

@section('content')
<div class="container">

  <div class="row">

    <div class="col-6">
      <h1>{{$title}}</h1>
    </div>
    <div class="col-6 text-right">
      <a href="/roles/create">
        <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
      </a>
    </div>

  </div>

  @if($roles)

  <table class="table">
    <thead class="thead-light">
      <tr>
        <th scope="col">Nombre</th>
        <th scope="col">Title</th>
        <th scope="col">Opciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($roles as $role)
      <tr>
        <td>{{ $role->name}}</td>
        <td>{{ $role->title}}</td>
        <td>
          <!-- <a href="/roles/{{ $role->id }}"><i class="fas fa-edit"></i></a> -->
          <!-- <a href="/roles/{{ $role->id }}/destroy"><i class="fas fa-trash-alt"></i></a> -->
          <a href="/roles/{{ $role->id }}/permissions"><i class="fas fa-eye"></i></a>

        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $roles->links() }}

  @endif

</div>
@endsection
