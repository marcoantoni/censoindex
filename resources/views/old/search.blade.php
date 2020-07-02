@extends('app')

@section('title', 'Relatório de matriculas do municipio')

@section('sidebar')
@parent
  <p>This is appended to the master sidebar.</p>
@endsection

@section('content')  

  	{!! Form::open([
	  'route' => 'search.store',
	  'class' => 'col s12'
	]) !!}  
      <div class="row">
        <div class="input-field col s8">
          <i class="material-icons prefix">search</i>
          <input name="search" id="icon_prefix" type="text" class="validate">
          <label for="icon_prefix">First Name</label>
        </div>
      </div>
    {{ Form::close() }}

@endsection