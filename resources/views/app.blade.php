<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>CensoIndex</title>
    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
        .bd-example-modal-lg .modal-dialog{
          display: table;
          position: relative;
          margin: 0 auto;
          top: calc(50% - 24px);
        }

        .bd-example-modal-lg .modal-dialog .modal-content{
          background-color: transparent;
          border: none;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/4.5/examples/pricing/pricing.css" rel="stylesheet">
  </head>
  <body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
      <h5 class="my-0 mr-md-auto font-weight-normal">PPGCP - UFSM</h5>
      <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-dark" href="#" data-toggle="modal" data-target="#modalHelp">Ajuda</a>
        <a class="p-2 text-dark" href="#">Sobre</a>
        <a class="p-2 text-dark" href="#">Origem dos dados</a>
      </nav>
      @if (isset($debug))
        <a class="btn btn-outline-primary" href="#" data-toggle="modal" data-target="#modalDebug">Debug NLP</a>&nbsp;&nbsp;
      @endif
    </div>
    <div class="container">
      <main>
        @yield('content')
      </main>
      <!--
      <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <div class="row">
          <div class="col-12 col-md">
            <img class="mb-2" src="/docs/4.5/assets/brand/bootstrap-solid.svg" alt="" width="24" height="24">
            <small class="d-block mb-3 text-muted">&copy; 2017-2020</small>
          </div>
          <div class="col-6 col-md">
            <h5>Features</h5>
            <ul class="list-unstyled text-small">
              <li><a class="text-muted" href="#">Cool stuff</a></li>
              <li><a class="text-muted" href="#">Random feature</a></li>
              <li><a class="text-muted" href="#">Team feature</a></li>
              <li><a class="text-muted" href="#">Stuff for developers</a></li>
              <li><a class="text-muted" href="#">Another one</a></li>
              <li><a class="text-muted" href="#">Last time</a></li>
            </ul>
          </div>
          <div class="col-6 col-md">
            <h5>Resources</h5>
            <ul class="list-unstyled text-small">
              <li><a class="text-muted" href="#">Resource</a></li>
              <li><a class="text-muted" href="#">Resource name</a></li>
              <li><a class="text-muted" href="#">Another resource</a></li>
              <li><a class="text-muted" href="#">Final resource</a></li>
            </ul>
          </div>
          <div class="col-6 col-md">
            <h5>About</h5>
            <ul class="list-unstyled text-small">
              <li><a class="text-muted" href="#">Team</a></li>
              <li><a class="text-muted" href="#">Locations</a></li>
              <li><a class="text-muted" href="#">Privacy</a></li>
              <li><a class="text-muted" href="#">Terms</a></li>
            </ul>
          </div>
        </div>
      </footer>-->

      <!-- modal spinner -->
    <div id="modalspinner" class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div style="width: 48px">
              <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Pesquisando...</span>
              </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalHelp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ajuda</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Faça perguntas como se fossem dirigidas a outra pessoa. No momento, consigo responder essas perguntas :) <br>
            <br><a href="#">Quais escolas federais tem em Santa Maria/RS</a> - consigo aplicar restrições de escolas particulares e públicas (municipais, estaduais e federais)
            <br><a href="#" class="questionHelp" onclick="javascript: setQuestion(this);">quais escolas particulares existem em Frederico Westphalen/RS</a>
            <br><a href="#" class="questionHelp" onclick="javascript: setQuestion(this);">quantos alunos tem na cidade de Rolante/RS</a>
            <br><a href="#" class="questionHelp" onclick="javascript: setQuestion(this);">quantos alunos tem na escola visconde de cairu em Santa Rosa/RS</a>
            <br><a href="#" class="questionHelp" onclick="javascript: setQuestion(this);">quantos alunos tem em Rolante/RS que usam transporte público</a>
            <br><a href="#" class="questionHelp" onclick="javascript: setQuestion(this);">quantos alunos tem no ifrs Rolante/RS no curso tecnico em informatica</a>
            <br><a href="#" class="questionHelp" onclick="javascript: setQuestion(this);">quais cursos tem na cidade de Taquara/RS</a>
            <br><a href="#" class="questionHelp" onclick="javascript: setQuestion(this);">onde tem o curso técnico em informática em Porto Alegre</a>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
          $('#btn-submit').prop('disabled', true);
      });
     $('#search').on('keyup', function() {
          if (this.value.length > 20){
            $('#btn-submit').prop('disabled', false);
            $(this).popover('hide');
          } else {
            $('#btn-submit').prop('disabled', true);
          }
      });
            function modal(){
        $('#modalspinner').modal('show');
      }
       $(function () {
        $('[data-toggle="popover"]').popover({
          sanitize: false,
          content: function () {
          return $("#PopoverContent").html();
          }
        });
      });

      function setQuestion(link){
        $('#search').val($(link).text());
        $('#modalHelp').modal('hide');
        $('#btn-submit').prop('disabled', false);
      }
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-171693860-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-171693860-1');
    </script>
  </div>
</body>
</html>