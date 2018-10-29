@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$( function() {
  $.noConflict();
  $( "#role" ).autocomplete({
    source: "/roles/search",
    minLength: 2,
    select: function( event, ui ) {

      $('#roles_id').val( ui.item.id );
    }
  });
} );
</script>


<div class="container">

  <div class="row">

    <div class="col-8">
      <h1>{{$title}}</h1>
    </div>
    <div class="col-4 text-right">
        {{ Form::open(array('route' => 'assignedroles.store', 'class' => 'form-inline', 'role' => 'form')) }}
        {{ Form::hidden('users_id', $user->id, array('id' => 'users_id', 'name' => 'users_id')) }}

        <div class="form-group mb-2">
          <input type="text" class="form-control" name="role" id="role" value="">
          {{ Form::hidden('roles_id', '', array('id' => 'roles_id', 'name' => 'roles_id')) }}
        </div>

        <button type="submit" class="btn btn-primary mb-2">Agregar</button>
      </form>
    </div>

  </div>


  @if($assignedroles)

  <table class="table">
    <thead class="thead-light">
      <tr>
        <th scope="col">Role</th>
        <th scope="col">Title</th>
        <th scope="col">Opciones</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($assignedroles as $assignedrole)

      <tr>
        <td>{{ $assignedrole->roles->name}}</td>
        <td>{{ $assignedrole->roles->title}}</td>
        <td>
          <a href="/assigned_roles/{{ $assignedrole->id }}/destroy"><i class="fas fa-trash-alt"></i></a>

        </td>
      </tr>
      @endforeach
    </tbody>
  </table>


  @endif


</div>



@stop
