<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <title>@yield('title')</title>
    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="https://materializecss.com/dist/css/materialize.min.css"  media="screen,projection"/>
    <link href="https://materializecss.com/templates/starter-template/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <style>
      body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
      }
      main {
        flex: 1 0 auto;
      }
    </style>
  </head>
  <body>
    <header>
      <!-- Dropdown Structure -->
      <ul id="dropdown1" class="dropdown-content">
        <li><a href="#!">one</a></li>
        <li><a href="#!">two</a></li>
        <li class="divider"></li>
        <li><a href="#!">three</a></li>
      </ul>
      <nav class="light-blue lighten-1" role="navigation">
        <div class="nav-wrapper container">
          <!-- <a href="#!" class="brand-logo">Logo</a>-->
          <ul class="left hide-on-med-and-down">
            <li><a href="#">Sobre</a></li>
            <li><a href="#">Origem dos dados</a></li>
            <li><a href="#">Ajuda</a></li>
          </ul>
        </div>
      </nav>
    </header>


    <div class="section no-pad-bot" id="index-banner">
      <div class="container">
        <br><br>
        <h1 class="header center orange-text">Starter Template</h1>
        <div class="row center">
          <h5 class="header col s12 light">A modern responsive front-end framework based on Material Design</h5>
        </div>
        <div class="row center">
          <a href="http://materializecss.com/getting-started.html" id="download-button" class="btn-large waves-effect waves-light orange">Get Started</a>
        </div>
        <br><br>
      </div>
    </div>


    <main class="container section">
    
        @yield('content')
    
      <br><br>
    </main>

    <footer class="page-footer light-blue">
      <div class="container">
        <div class="row">
          <div class="col l6 s12">
            <h5 class="white-text">O que é o FUNDEB?</h5>
            <p class="grey-text text-lighten-4">O Fundo de Manutenção e Desenvolvimento da Educação Básica e de Valorização dos Profissionais da Educação (Fundeb) atende toda a educação básica, da creche ao ensino médio. Substituto do Fundo de Manutenção e Desenvolvimento do Ensino Fundamental e de Valorização do Magistério (Fundef), que vigorou de 1997 a 2006, o Fundeb está em vigor desde janeiro de 2007 e se estenderá até 2020.</p>
            <a class="white-text" href="http://portal.mec.gov.br/fundeb">Para mais detalhes</a></li>
          </div>
          <div class="col l3 s12">
            <h5 class="white-text">Origem dos dados</h5>
            <ul>
            <li><a class="white-text" href="https://www.fnde.gov.br/index.php/financiamento/fundeb/area-para-gestores/dados-estatisticos">Dados estatistícos</a></li>
            <li><a class="white-text" href="#!">Link 3</a></li>
            <li><a class="white-text" href="#!">Link 4</a></li>
            </ul>
          </div>
          <div class="col l3 s12">
            <h5 class="white-text">Contribua</h5>
            <ul>
              <li><a class="white-text" href="https://github.com/marcoantoni/fundeb/">GitHub</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer-copyright">
        <div class="container">
          Made by <a class="orange-text text-lighten-3" href="http://materializecss.com">Materialize</a>
        </div>
      </div>
    </footer>
    <!--  Scripts-->
    <script type="text/javascript" src="https://materializecss.com/bin/materialize.js"></script>
    <script src="js/init.js"></script>
    <script>
      $(document).ready(function() {
        $(".dropdown-trigger").dropdown();
      });
    </script>
  </body>
</html>