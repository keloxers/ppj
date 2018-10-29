@extends('layouts.app')

@section('content')

<div class="container">

  <div class="row">

    <div class="col-6">
      <h1>{{$title}}</h1>
    </div>
    <div class="col-6 text-right">
      <a href="/abilities/create">
        <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
      </a>
    </div>

  </div>


  @if($abilities)

  <table class="table">
    <thead class="thead-light">
      <tr>
        <th scope="col">Nombre</th>
        <th scope="col">Title</th>
        <th scope="col">Opciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($abilities as $abilitie)
      <tr>
        <td>{{ $abilitie->name}}</td>
        <td>{{ $abilitie->title}}</td>
        <td>
          <a href="/abilities/{{ $abilitie->id }}"><i class="fas fa-edit"></i></a>
          <a href="/abilities/{{ $abilitie->id }}/destroy"><i class="fas fa-trash-alt"></i></a>
          <a href="/abilities/{{ $abilitie->id }}/show"><i class="fas fa-eye"></i></a>

        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $abilities->links() }}

  @endif

</div>
@endsection
