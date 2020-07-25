@extends('app')

@section('content')
  {!! Form::open([
    'route' => 'search.store',
    'class' => ''
  ]) !!} 
    <div class="form-group">
      <div class="input-group">
        <input type="text" name="search" id="search" class="form-control" value="{{ $sentence }}" required>
        <div class="input-group-prepend">
          <div class="input-group-text">?</div>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-left: 10px;" onclick="modal();">Pesquisa</button>
      </div>
    </div>
  {{ Form::close() }}        

  @if ($responseType == 1)
    @php
      $count = $data->count();
    @endphp
    
    @if ($count > 100) 
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Aviso</strong> Sua pesquisa retornou {{ $count }} resultados
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @elseif ($count == 0) 
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Erro</strong> Sua pesquisa não retornou nenhum resultado. Podem ter acontecido três coisas <br>
                A resposta está certa e é mesmo <b>zero</b></br>
                Não consegui entender sua pergunta.Tente escrever o nome da cidade e a UF do seguinte com maiúsculas. Ex: <b>Porto Alegre/RS</b></br>
                Talvez eu não tenho essa informação no momento </br>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if ($responseTable == 2)
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Escola</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $value)
            <tr>
              <td>{{ $value->NO_ENTIDADE }}</td>
            </tr>     
          @endforeach
        </tbody>
      </table>
    @elseif($responseTable == 3)
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Escola</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $value)
            <tr>
              <td>{{ $value->NOME }}</td>
            </tr>     
          @endforeach
        </tbody>
      </table>
    @endif
  @else
    <h1>{{ $data }}</h1>
  @endif

  <!-- Modal -->
  <div class="modal fade" id="modalDebug" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Debug Google Natural Language Processing</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {!! $debug !!}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
@endsection