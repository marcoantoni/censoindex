<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;

class LogController extends Controller {
	
	public function store(Request $request) {
		$log = new Log();
		$log->sentence = $request->input('questionfeedback');
		$log->feedback = $request->input('feedback');
		$log->correct  = 0;
		$log->save();
		return 'sucess';
	}

	// Rota via POST 
	public function storeRightAnswer(Request $request) {
		$log = new Log();
		$log->sentence = $request->input('questionfeedback');
		$log->feedback = '';
		$log->correct  = 1;
		$log->save();
		return 'sucess';
	}
}
