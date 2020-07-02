<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Escola;
use App\Municipio;
use App\UF;

class TesteController extends Controller {
    public function index(){
    	//$escolas = new Escola();
    	//return $escolas->where('co_municipio', '=', 4316006)->get();
    	//return $escolas->municipio()->where('co_municipio', '=', 4316006)->get();
    	$municipio = new Municipio();
    	return $municipio->escolas()->get();
    	
    }
}
