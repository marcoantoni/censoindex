<?php

namespace App\Tree;

class Answer {
	/* Tipo da resposta */
	public const NUMBER = 0;
    public const LIST = 1;
    /* Quanto ocorre de aparecer mais de uma entidade em uma resposta númerica */
    public const NUMBERLIST = 10;

    /* Mapeamento da tabela presente na resposta */
    public const SCHOOL = 2;
    public const STUDENT = 3;
    public const COURSE = 4;

    /* Tipo da mensagem do usuário */
    public const ERROR = 'error';
    public const WARNING = 'warning';
    public const INFO = 'info';

    /* Tipo da resposta */
    private $responseType;
    private $responseTable;

    public $data;
    public $statistics;

    private $userMessage;

    public function __construct(){
        $this->userMessage = array();
        $this->statistics = array();
        session(['inDomain' => false]); // A pergunta não está no domínio permitido
    }

    public function getResponseType(){
        return $this->responseType;
    }
    
    public function setResponseType($responseType){
        $this->responseType = $responseType;
    }

    public function getResponseTable(){
        return $this->responseTable;
    }
    
    public function setResponseTable($responseTable){
        $this->responseTable = $responseTable;
    }

    public function getData(){
        return $this->data;
    }

    public function setData($data){
        $this->$data = $data;
    }

    public function addUserMessage(string $level, string $msg) {
        if (isset($this->userMessage[$level])){
           $this->userMessage[$level] = $this->userMessage[$level] . $msg . '<br>';
        } else {
           $this->userMessage[$level] = $msg . '<br>';
        }
    }

    public function getUserMessage () {
        return $this->userMessage;
    }

    // Gera as mensagens que serão apresentadas para o usuario com base no número de resultos da consulta
    public function generateMessages(int $numResults) {
        $msg = "Sua pesquisa retornou <b>$numResults</b> resultados" . (session('NOME_MUNICIPIO') ? " para a cidade de " . session('NOME_MUNICIPIO') : " ");
        
        // se a pesquisa não retornou nenhum resultado, verifica se ela está relacionada a cursos       
        if ($numResults == 0){
            // se a varíavel estiver como false ela não foi alterada
            if  (session('courseName') == false) {
                $this->addUserMessage(
                    Answer::ERROR, 
                    "Sua pesquisa não retornou nenhum resultado. Tente reescrever sua pergunta usando letras maiúsculas no nome da cidade"
                );
            // se a varíavel estiver como 'none' ela foi alterada em app\Tree\Course
            } else if (session('courseName') == null) {
                $this->addUserMessage(
                    Answer::INFO, 
                    session('NOME_MUNICIPIO') . ' não oferta nenhum curso técnico'
                );
            // se a varíavel estiver for diferente de 'none' ela foi alterada em app\Tree\Course e encontrou um curso
            } else if (session('courseName')) {
                $this->addUserMessage(
                    Answer::INFO, 
                    'O curso técnico em <b>'.session('courseName').'</b> não é ofertado na cidade de ' . session('NOME_MUNICIPIO')
                );
            }
        } else if ($numResults > 100) {
            $this->addUserMessage(
                Answer::WARNING, 
                $msg
            );   
        } else {
            $this->addUserMessage(
                Answer::INFO, 
                $msg
            );
        }
    }

}