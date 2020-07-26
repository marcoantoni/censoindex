<?php

namespace App\Tree;

class Answer {
	/* Tipo da resposta */
	public const NUMBER = 0;
    public const LIST = 1;

    /* Mapeamento da tabela presente na resposta */
    public const SCHOOL = 2;
    public const COURSE = 3;

    /* Tipo da resposta */
    private $responseType;
    private $responseTable;

    private $data;
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