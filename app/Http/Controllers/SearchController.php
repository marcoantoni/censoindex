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

use App\Municipio;
use App\Tree\DecisionTree;

class SearchController extends Controller {

    public function index(){
        return view('search')->with(['pagetitle' => 'CensoIndex']);
    }
    
    public function store(Request $request) {
        $decision_tree = new DecisionTree($request->input('search'));
        $analyse = $decision_tree->analyze();
        $decision_tree->process();
        
        return view ('response')->with([
            'escolas'   => $decision_tree->response,
            'sentence'  => $decision_tree->sentence,
            'debug'     => $analyse,
            'responseType' => $decision_tree->responseType,
            'pagetitle' => 'CensoIndex - Resposta da pergunta'
        ]);
    }

}
