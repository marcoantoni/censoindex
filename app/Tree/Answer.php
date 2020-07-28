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


    /* Tipo da resposta */
    private $responseType;
    private $responseTable;

    public $data;
    public $statistics;

    public function __construct(){
        $this->statistics = array();
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
}