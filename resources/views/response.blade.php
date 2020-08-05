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
  {{-- exibe as mensagens na tela --}}
  @foreach ($userMessage as $key => $message)
    @if ($key == Answer::INFO)
     <div class="alert alert-info alert-dismissible fade show" role="alert">
        {!! $message !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @elseif ($key == Answer::WARNING)
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {!! $message !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @else ($key == Answer::ERROR)
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {!! $message !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif
  @endforeach
    
  @if (session('inDomain'))
   <script type="text/javascript">
      var labelData = [];
      var labels = [];
      var data = [];
    </script>
    @if ($responseType == Answer::LIST)
      {{-- Visualização em lista. Testa se resposta é relacionada a escola --}}
      @if ($responseTable == Answer::SCHOOL)
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Escolas {{session('courseName') ? " que ofertam o curso técnico em " . session('courseName') : " "}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $value)
              <tr>
                <td>
                  {{ $value->NO_ENTIDADE }}
                  @if ($value->TP_SITUACAO_FUNCIONAMENTO == 2)
                    <span class="badge badge-warning">Paralizada</span>
                  @elseif ($value->TP_SITUACAO_FUNCIONAMENTO == 4)
                    <span class="badge badge-warning">Extinta</span>
                  @elseif ($value->TP_SITUACAO_FUNCIONAMENTO == 3)
                    <span class="badge badge-danger">Extinta em 2019</span>
                  @endif 
                </td>
              </tr>     
            @endforeach
          </tbody>
        </table>
      {{-- Visualização em lista. Testa se resposta é relacionada a cursos --}}
      @elseif($responseTable == Answer::COURSE)
        <table class="table table-striped">
          <thead>
            <tr>
              <th>{{ (session('NO_ENTIDADE')) ? "Cursos em " . session('NO_ENTIDADE') . " na cidade de " . session('NOME_MUNICIPIO') : "Cursos na cidade de " .session('NOME_MUNICIPIO') }}</th>
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
          @foreach ($data as $key => $value)
            <tr>
              @php $count = $value[1]->count(); @endphp
              <td>{{ $value[0] }}</td>
              <td>{{ $count }}</td>
              <script type="text/javascript">
                labels.push('{{ $value[0] }}');
                labelData.push('dnfids');
                data.push('{{ $count }}');
              </script>
            </tr>
          @endforeach
        </tbody>
      </table>
    {{-- Aqui a resposta é numérica e só contem uma entidade --}}
    @else
      <p>      
        @if (session('NO_ENTIDADE')) 
          {{ session('NO_ENTIDADE') }} na cidade de 
          <script type="text/javascript">
            labels.push('{{ session('NO_ENTIDADE') }}');
            data.push({{ $data }});
          </script>
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
        @elseif ($responseTable == Answer::STUDENT)
          alunos
        @else
          cursos  
        @endif
        
        {{-- Apresenta  a menssagem caso a pesquisa busque informações sobre transporte utilizado --}}
        @if (session('messageTransport'))
          {{ session('messageTransport') }}
        @endif

        {{-- Apresenta a menssagem caso a pesquisa busque informações sobre o curso --}}
        @if (session('courseName'))
          no curso técnico em {{ session('courseName') }}
        @endif
      </p>
    @endif
    @if (session('NOME_MUNICIPIO') != false)
      <script type="text/javascript">
        labels.push('{{ session('NOME_MUNICIPIO') }}');
        data.push({{ $stats['city'] }});
      </script>
    @endif
    <canvas id="chartCity" style="width=75px; height=75px;"></canvas>
    <canvas id="chartState" style="width=75px; height=75px;"></canvas>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script>
      var ctx = document.getElementById('chartCity');
      var myChart = new Chart(ctx, {
          type: 'pie',
          data: {
              labels: labels,
              datasets: [{
                  label: 'No ifs',
                  data: data,
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                  ],
                  borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                  ],
                  borderWidth: 1
              }]
          }
      });
      
      /* https://www.chartjs.org/docs/latest/developers/updates.html */
      function addData(chart, label, data) {
        chart.data.labels.push(label);
        chart.data.datasets.forEach((dataset) => {
            dataset.data.push(data);
        });
        chart.update();
      }

      @if (session('messageTransport'))
      $.ajax({
        url: 'http://127.0.0.1:8000/stats/43/4316006',
        type: 'GET',
        
        success: function(data) {
          addData(myChart, 'Total de alunos', data.city);
            alert('sucess' + data.city);
        }
      });
      @endif
    

    </script>

    
  @endif {{--inDomain--}}
  

  <h1>Debug</h1>
  Escola {{ session('NO_ENTIDADE') }} <br>
  Cidade {{ session('NOME_MUNICIPIO') }} <br>
  Estado {{ session('NO_UF') }} <br>
  Transporte {{ session('messageTransport') }} <br>
  Curso {{ session('courseName') }} <br>

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