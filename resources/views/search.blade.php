@extends('app')

@section('title', 'Relatório de matriculas do municipio')

@section('sidebar')
@parent
  <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
<br><br><br><br><br>
{!! Form::open([
  'route' => 'search.store',
  'class' => 'row'
]) !!}  
    
  <div class="input-field col s8">
    <input id="search" type="text" class="" name="search">
    <label class="active" for="first_name2">Digite sua pergunta</label>
    <input type="submit" name="">
  </div>
  
{{ Form::close() }}

@endsection