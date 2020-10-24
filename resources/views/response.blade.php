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
  <div class="alert alert-success" role="alert" id="alert-success">
    Obrigado pelo seu feedback!
  </div>
  <div>
    Como você avalia a resposta recebida?
    <button type="button" class="btn btn-outline-success btn-sm" id="rightanswer" onclick="javascript: formfeedbacksubmit();" style="margin-bottom: 5px;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-hand-thumbs-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16v-1c.563 0 .901-.272 1.066-.56a.865.865 0 0 0 .121-.416c0-.12-.035-.165-.04-.17l-.354-.354.353-.354c.202-.201.407-.511.505-.804.104-.312.043-.441-.005-.488l-.353-.354.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315L12.793 9l.353-.354c.353-.352.373-.713.267-1.02-.122-.35-.396-.593-.571-.652-.653-.217-1.447-.224-2.11-.164a8.907 8.907 0 0 0-1.094.171l-.014.003-.003.001a.5.5 0 0 1-.595-.643 8.34 8.34 0 0 0 .145-4.726c-.03-.111-.128-.215-.288-.255l-.262-.065c-.306-.077-.642.156-.667.518-.075 1.082-.239 2.15-.482 2.85-.174.502-.603 1.268-1.238 1.977-.637.712-1.519 1.41-2.614 1.708-.394.108-.62.396-.62.65v4.002c0 .26.22.515.553.55 1.293.137 1.936.53 2.491.868l.04.025c.27.164.495.296.776.393.277.095.63.163 1.14.163h3.5v1H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>  
      </svg> Correta
    </button>

    <button type="button" class="btn btn-outline-danger btn-sm" id="wronganswer" data-toggle="modal" data-target="#modalfeedback" onclick="javascript: getQuestion();" style="margin-bottom: 5px;">
      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-hand-thumbs-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M6.956 14.534c.065.936.952 1.659 1.908 1.42l.261-.065a1.378 1.378 0 0 0 1.012-.965c.22-.816.533-2.512.062-4.51.136.02.285.037.443.051.713.065 1.669.071 2.516-.211.518-.173.994-.68 1.2-1.272a1.896 1.896 0 0 0-.234-1.734c.058-.118.103-.242.138-.362.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2.094 2.094 0 0 0-.16-.403c.169-.387.107-.82-.003-1.149a3.162 3.162 0 0 0-.488-.9c.054-.153.076-.313.076-.465a1.86 1.86 0 0 0-.253-.912C13.1.757 12.437.28 11.5.28v1c.563 0 .901.272 1.066.56.086.15.121.3.121.416 0 .12-.035.165-.04.17l-.354.353.353.354c.202.202.407.512.505.805.104.312.043.44-.005.488l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.415-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.353.352.373.714.267 1.021-.122.35-.396.593-.571.651-.653.218-1.447.224-2.11.164a8.907 8.907 0 0 1-1.094-.17l-.014-.004H9.62a.5.5 0 0 0-.595.643 8.34 8.34 0 0 1 .145 4.725c-.03.112-.128.215-.288.255l-.262.066c-.306.076-.642-.156-.667-.519-.075-1.081-.239-2.15-.482-2.85-.174-.502-.603-1.267-1.238-1.977C5.597 8.926 4.715 8.23 3.62 7.93 3.226 7.823 3 7.534 3 7.28V3.279c0-.26.22-.515.553-.55 1.293-.138 1.936-.53 2.491-.869l.04-.024c.27-.165.495-.296.776-.393.277-.096.63-.163 1.14-.163h3.5v-1H8c-.605 0-1.07.08-1.466.217a4.823 4.823 0 0 0-.97.485l-.048.029c-.504.308-.999.61-2.068.723C2.682 1.815 2 2.434 2 3.279v4c0 .851.685 1.433 1.357 1.616.849.232 1.574.787 2.132 1.41.56.626.914 1.28 1.039 1.638.199.575.356 1.54.428 2.591z"/> 
      </svg> Errada
    </button>
  </div>
  <script type="text/javascript">
    function getQuestion(){
      var question = $('#search').val();
      $('#questionfeedback').val(question);
    }
  </script>
  @if (session('inDomain'))
   <script type="text/javascript">
      var labelData = [];
      var labels = [];
      var data = [];
      var msg = 'Alunos ';
    </script>
    @if ($responseType == Answer::LIST)
      {{-- Visualização em lista. Testa se resposta é relacionada a escola --}}
      @if ($responseTable == Answer::SCHOOL)
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Escolas {{session('courseName') ? "em " . session('NOME_MUNICIPIO') . "/" . session('NO_UF') . " que ofertam o curso técnico em " . session('courseName') : " "}}</th>
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
              <th>{{ (session('NO_ENTIDADE')) ? "Cursos em " . session('NO_ENTIDADE') . " na cidade de " . session('NOME_MUNICIPIO') . "/" . session('NO_UF') : "Cursos na cidade de " .session('NOME_MUNICIPIO')."/".session('NO_UF') }}</th>
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
      @php $showGraph = true; @endphp 
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Escola</th>
            <th>
              Quantidade de alunos 
              {{ session('messageTransport') ? session('messageTransport') : " " }}
              {{ session('messagePhase') ? session('messagePhase') : " " }}
              {{ session('courseName') ? "no técnico em " . session('courseName') : " " }}
            </th>
          </tr>
        </thead>
        <tbody>
          <!-- Ao preencher a tabela, armazena os valores para criacao do grafico de estatisticas -->
          @foreach ($data as $key => $value)
            <tr>
              @php $count = $value[1]->count(); @endphp
              <td>{{ $value[0] }}</td>
              <td>{{ $count }}</td>
              <script type="text/javascript">
                labels.push('{{ $value[0] }}');
                labelData.push('{{ $value[0] }}');
                data.push('{{ $count }}');
              </script>
            </tr>
          @endforeach
        </tbody>
      </table>
    {{-- Aqui a resposta é numérica e só contem uma entidade --}}
    @else
      <p>
        @php $showGraph = false; @endphp
        @if (session('NO_ENTIDADE')) 
          {{ session('NO_ENTIDADE') }} na cidade de 
          <script type="text/javascript">msg += '{{ session("NO_ENTIDADE") }}';</script>
           @php $showGraph = true; @endphp
        @endif

        @if (session('NOME_MUNICIPIO') == false) 
          {{ session('NO_UF') }} 
        @else
          {{ session('NOME_MUNICIPIO') }}/{{ session('NO_UF') }}
        @endif

        tem

        <b>{{ $data }}<script type="text/javascript">data.push({{ $data }});</script></b> 
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
          <script type="text/javascript">msg += ' - {{ session("messageTransport") }}';</script>
          @php $showGraph = true; @endphp
        @endif

        {{-- Apresenta a menssagem caso a pesquisa busque informações sobre o curso --}}
        @if (session('courseName'))
          no curso técnico em {{ session('courseName') }}
          <script type="text/javascript">msg += ' - {{ session("courseName") }}';</script>
          @php $showGraph = true; @endphp
        @endif

        {{-- Apresenta a menssagem do ano em que o aluno está cursando --}}
        @if (session('messageYear'))
        {{ session('messageYear') }}
          <script type="text/javascript">msg += ' - {{ session("messageYear") }}';</script>
          @php $showGraph = true; @endphp
        @endif
        {{-- Apresenta a menssagem caso a pesquisa busque informações sobre a etapa de educação --}}
        @if (session('messagePhase'))
        {{ session('messagePhase') }}
          <script type="text/javascript">msg += ' - {{ session("messagePhase") }}';</script>
          @php $showGraph = true; @endphp
        @endif
        <script type="text/javascript">    
          labels.push(msg);
          console.log('msg: ' + msg);
        </script>
      </p>
    @endif
    <!-- gráfico de estatísticas só se aplica a alunos-->
    @if ($responseTable == Answer::STUDENT && $showGraph)
      <canvas id="chartCity" style="width=75px; height=75px;"></canvas>
      <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
      <script>
        var ctx = document.getElementById('chartCity');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)',
                      'rgba(255, 193, 86, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                      'rgba(255, 99, 132, 1)',
                      'rgba(54, 162, 235, 1)',
                      'rgba(255, 193, 86, 1)',
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
 
        var url = '{{ url("/stats") }}'+'/'+{{ session("CO_UF") }} + '/' + {{ session("CO_MUNICIPIO") }};
        console.log('url=' + url);
        $.get(url, function( response ) {
          console.log(data);
          addData(myChart, 'Total de alunos em {{ session("NOME_MUNICIPIO") }}/{{ session("NO_UF") }}', response['city']);
        });
      </script>
    @endif
  @endif {{--inDomain--}}
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
          <h4>Session</h4>
          Escola: {{ session('NO_ENTIDADE') }} <br>
          Cidade: {{ session('NOME_MUNICIPIO') }} <br>
          Estado: {{ session('NO_UF') }} <br>
          Transporte: {{ session('messageTransport') }} <br>
          Curso: {{ session('courseName') }} <br>
          Fase: {{ session('messagePhase') }} <br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalfeedback" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Avaliação</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {!! Form::open([
            'route' => 'log.store',
            'id'    => 'formfeedback',
            'class' => ''
          ]) !!} 
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Pergunta</label>
              <input type="text" class="form-control" id="questionfeedback" name="questionfeedback" required>
            </div>
            <div class="form-group">
              <label for="message-text" class="col-form-label">Mensagem:</label>
              <textarea class="form-control" id="feedback" name="feedback" required></textarea>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Enviar</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    $('#alert-success').hide();

    function formfeedbacksubmit(){
    
      $.ajax({
        url: '{{ url("/log/storerightanswer") }}',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          questionfeedback: $('#search').val()
        },
        success: function(dataResult){
          showAlertSucess();           
        }
      });
    }

    $('#formfeedback').submit(function(e){
      e.preventDefault();
      $.ajax({
        url: '{{ url("/log") }}',
        type: 'post',
        data:$('#formfeedback').serialize(),
        success:function(){
          $('#modalfeedback').modal('hide')
          showAlertSucess();
        }
      });
    });

    function showAlertSucess(){
      $('#alert-success').show();
      setInterval(
        function(){ 
          $('#alert-success').hide(); 
        }, 3000);
      $('#rightanswer').prop('disabled', true);
      $('#wronganswer').prop('disabled', true);
    }
  </script>
@endsection