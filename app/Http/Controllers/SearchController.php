<?php

namespace App\Http\Controllers;

use App;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Cloud\Language\V1\AnnotateTextResponse;
use Google\Cloud\Language\V1\Document;
use Google\Cloud\Language\V1\Entity;
use Google\Cloud\Language\V1\EntityMention;
use Google\Cloud\Language\LanguageClient;
use Google\Cloud\Language\V1\LanguageServiceClient;
use Google\Cloud\Language\V1\PartOfSpeech\Aspect;
use Google\Cloud\Language\V1\PartOfSpeech\Form;
use Google\Cloud\Language\V1\PartOfSpeech\Gender;
use Google\Cloud\Language\V1\PartOfSpeech\Mood;
use Google\Cloud\Language\V1\PartOfSpeech\Number;
use Google\Cloud\Language\V1\PartOfSpeech\PBCase;
use Google\Cloud\Language\V1\PartOfSpeech\Person;
use Google\Cloud\Language\V1\PartOfSpeech\Proper;
use Google\Cloud\Language\V1\PartOfSpeech\Reciprocity;
use Google\Cloud\Language\V1\PartOfSpeech\Tense;
use Google\Cloud\Language\V1\PartOfSpeech\Voice;
use Google\Cloud\Language\V1\Token;
use Google\Cloud\Language\V1\AnnotateTextRequest\Features;
use Google\Cloud\Language\V1\Document\Type;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Google\Cloud\Language\V1\EntityMention\Type as MentionType;
use Google\Cloud\Language\V1\PartOfSpeech\Tag;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Log;

use App\Municipio;
use App\Tree\DecisionTree;

class SearchController extends Controller {

    public function index(){
        return view('search');
    }
    
    public function store(Request $request) {
        /* Grava as perguntas no BD */;
        $log = new Log();
        $log->sentence = $request->input('search');
        $log->save();
        // inicia o processamento
        $decision_tree = new DecisionTree($request->input('search'));
        $analyse = $decision_tree->analyze();
        
        $answer = $decision_tree->process();
        
        return view ('response')->with([
            'data'   => $answer->data,
            'sentence'  => $decision_tree->sentence,
            'debug'     => $analyse,
            'responseType' => $answer->getResponseType(),
            'responseTable' => $answer->getResponseTable(),
            'stats' => $answer->statistics->stats,
            'userMessage' => $answer->getUserMessage()
        ]);
    }

}
