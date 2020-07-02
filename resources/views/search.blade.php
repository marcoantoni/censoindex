@extends('app')

@section('title', 'Relatório de matriculas do municipio')

@section('sidebar')
@parent
  <p>This is appended to the master sidebar.</p>
@endsection

@section('content')  
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">CensoIndex</h1>
    <p class="lead">Faça uma pergunta referente aos dados educacionais que tentaremos responder</p>
  </div>
  {!! Form::open([
    'route' => 'search.store',
    'class' => 'col s12'
  ]) !!} 
    <div class="form-group">
      <div class="input-group mb-2">
        <input type="text" name="search" id="search" class="form-control" placeholder="Digite sua pergunta">
        <div class="input-group-prepend">
          <div class="input-group-text">?</div>
        </div>
      </div>
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-primary">Pesquisa</button>
    </div>
  {{ Form::close() }}
@endsection