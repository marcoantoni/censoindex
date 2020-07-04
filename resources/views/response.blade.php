@extends('app')

@section('title', 'Resposta')

@section('sidebar')
@parent
  <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
  {!! Form::open([
    'route' => 'search.store',
    'class' => ''
  ]) !!} 
    <div class="form-group">
      <div class="input-group">
        <input type="text" name="search" id="search" class="form-control" value="{{ $sentence }}">
        <div class="input-group-prepend">
          <div class="input-group-text">?</div>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Pesquisa</button>
      </div>
    </div>
  {{ Form::close() }}
 
  @if (Session::has('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <strong>Aviso</strong> {!! session('warning') !!}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif
  @if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Erro</strong> {!! session('error') !!}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif
 
  @if ($responseType == 2)
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Escola</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($escolas as $escola)
          <tr>
            <td>{{ $escola->NO_ENTIDADE }}</td>
          </tr>     
        @endforeach
      </tbody>
    </table>
  @else
    <h1>{{ $escolas }}</h1>
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