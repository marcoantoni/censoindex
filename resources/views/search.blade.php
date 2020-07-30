@extends('app')

@section('content')  
  <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <img src="{{ url('images/5bcb9feeaa45161a29590148df90a654.jpg') }}">
    <h1 class="display-4">CensoIndex</h1>
    <p class="lead">Faça uma pergunta referente aos dados do Censo Escolar que tentarei responder</p>
  </div>
  {!! Form::open([
    'route' => 'search.store',
    'class' => 'col s12',
  ]) !!} 
    <div class="form-group">
      <div class="input-group mb-2">
        <input type="text" name="search" id="search" class="form-control" placeholder="Digite sua pergunta" data-toggle="popover" data-trigger="click" data-placement="top" data-html="true" data-title="Tente perguntar" required>
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
      <img src="{{ url('images/school.png') }}" style="width: 75px; height: 75px;" title='Tente perguntar "quais escolas tem em Erechim/RS"'>
    </div>
  {{ Form::close() }}
  <div id="PopoverContent" style="display: none;">
    Quais escolas federais tem na cidade de Santa Maria/RS<br>
    Quantas escolas publicas tem na cidade de Porto Alegre/RS    
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
@endsection