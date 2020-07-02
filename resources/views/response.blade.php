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
        <div class="input-group-prepend">
          <div class="input-group-text">@</div>
        </div>
        <input type="text" name="search" id="search" class="form-control" placeholder="{{ $sentence }}">
        <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Pesquisa</button>
      </div>
    </div>
  {{ Form::close() }}
  
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
  <!-- JS code -->
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
@endsection