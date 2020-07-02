@extends('app')

@section('title', 'Resposta')

@section('sidebar')
@parent
  <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
{!! Form::open([
  'route' => 'search.store',
  'class' => 'row'
]) !!}  
   
  <div class="input-field col s8">
    <input id="search" type="text" class="" name="search" value="{{ $sentence }}">
    <label class="active" for="first_name2">Digite sua pergunta</label>
    <input type="submit" name="" class="waves-effect waves-light btn" value="Pesquisar">
  </div>
{{ Form::close() }}
  <table>
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

   <!-- Modal Trigger -->
  <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Debug</a>

  <!-- Modal Structure -->
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h4>Debug Google Natural Language Processing</h4>
      {!! $debug !!}
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
    </div>
  </div>

  <script>
     $(document).ready(function(){
      $('.modal').modal();
    });
  </script>

@endsection