@extends('app')

@section('content')  
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <img src="{{ url('images/book.png') }}" style="width: 224px; height: 180px;">
    <h1 class="display-4">CensoIndex</h1>
    <p class="lead">Faça uma pergunta referente aos dados do Censo Escolar que tentarei responder</p>
  </div>
  {!! Form::open([
    'route' => 'search.store',
    'class' => 'col s12',
  ]) !!} 
    <div class="form-group">
      <div class="input-group mb-2">
        <input type="text" name="search" id="search" class="form-control" placeholder="Digite sua pergunta" required>
        <div class="input-group-prepend">
          <div class="input-group-text">?</div>
        </div>
      </div>
    </div>
    <div class="text-center">
      <button type="submit" id="btn-submit" class="btn btn-primary" onclick="modal();">Pesquisa</button>
      <br>
      <br>
      <h5>Informações sobre</h5>
      <img src="{{ url('images/school.png') }}" style="width: 75px; height: 75px;" title='Tente perguntar "quais escolas públicas tem em Erechim/RS"'>
      <img src="{{ url('images/help-your-student-college-student-icon-11562880688qcza431pme.png') }}" style="width: 90px; height: 60px;" title='Tente perguntar "quantos alunos tem em Osório/RS no IFRS"'>
      <img src="{{ url('images/courses-icon-7.png') }}" style="width: 75px; height: 75px;" title='Tente perguntar "onde tem o curso técnico em enfermagem em Santa Maria/RS" ou "cursos do IFRS em Osório/RS"'>
    </div>
  {{ Form::close() }}
@endsection