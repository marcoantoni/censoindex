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
  {{-- Quanto a resposta é em lista, faz a contagem para dar os avisos ao usuário --}}
  @if ($responseType == Answer::LIST)
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
    {{-- Visualização em lista. Testa se resposta é relacionada a escola --}}
    @if ($responseTable == Answer::SCHOOL)
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
    {{-- Visualização em lista. Testa se resposta é relacionada a cursos --}}
    @elseif($responseTable == Answer::COURSE)
      <table class="table table-striped">
        <thead>
          <tr>
            <th>{{ (session('NO_ENTIDADE')) ? session('NO_ENTIDADE') : session('NOME_MUNICIPIO') }}</th>
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
  {{-- Se a resposta não for em lista, ela pode ser do tipo NUMBERLIST, ou seja, retornou mais que um resultado para uma resposta numérica --}}
  {{-- Esse tipo de resposta só estará relacionada a tabela Matriculas --}}
  @elseif ($responseType == Answer::NUMBERLIST)
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Escola</th>
          <th>
            Quantidade de alunos 
            @if (!session('messageTransport'))
              {{ session('messageTransport') }}             
            @endif
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($schoolsList as $key => $value)
          <tr>
            <td>{{ $value[0]}}</td>
            <td>{{ $value[1]->count() }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  {{-- Aqui a resposta é numérica e só contem uma entidade --}}
  @else
    <p>      
      @if (session('NO_ENTIDADE') != false) 
        {{ session('NO_ENTIDADE') }} 
      @endif

      @if (session('NOME_MUNICIPIO') == false) 
        {{ session('NO_UF') }} 
      @else
        {{ session('NOME_MUNICIPIO') }}/{{ session('NO_UF') }}
      @endif

      tem

      <b>{{ $data }}</b> 

      @if ($responseTable == Answer::SCHOOL)
        escolas
      @else
        alunos
        {{-- Apresenta  a menssagem caso a pesquisa busque informações sobre transporte utilizado --}}
        @if (session('messageTransport'))
          {{ session('messageTransport') }}
        @endif
      @endif
    </p>
  @endif

  <h1>Debug</h1>
  Escola {{ session('NO_ENTIDADE') }} <br>
  Cidade {{ session('NOME_MUNICIPIO') }} <br>
  Estado {{ session('NO_UF') }} <br>

  <!-- Modal debug-->
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