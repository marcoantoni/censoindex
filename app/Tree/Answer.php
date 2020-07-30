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
        $this->userMessage[$level] = $msg . '<br>';    
    }

    public function getUserMessage () {
        return $this->userMessage;
    }
}