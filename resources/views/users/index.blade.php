@extends('layouts.app')

@section('content')
<div class="container">

  <h1>Usuarios</h1>

  @if($users)

  <table class="table">
    <thead class="thead-light">
      <tr>
        <th scope="col">Nombre</th>
        <th scope="col">Email</th>
        <th scope="col">Opciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
      <tr>
        <td>{{ $user->name}}</td>
        <td>{{ $user->email}}</td>
        <td>

          <a href="/users/{{ $user->id }}" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
          <a href="/users/{{ $user->id }}/destroy" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash-alt"></i></a>
          <a href="/users/{{ $user->id }}/assignedroles" data-toggle="tooltip" data-placement="top" title="Abilities"><i class="fas fa-key"></i></a>

        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $users->links() }}

  @endif

</div>
@endsection
