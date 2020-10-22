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
        //$log = new Log();
        //$log->sentence = $request->input('search');
        //$log->save();
        
        $sentence = $this->speelCheck($request->input('search'));
        // inicia o processamento
        $decision_tree = new DecisionTree($sentence);
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

    public function speelCheck($input){
        $host = 'https://speelcheckdissertacao.cognitiveservices.azure.com';
        $path = '/bing/v7.0/spellcheck?';
        $params = 'mkt=pt-BR&mode=proof';

        $data = array (
            'text' => urlencode ($input)
        );

        $key = '8d5decd4544f4315a1e77c0fe78e6427';

        $headers = "Content-type: application/x-www-form-urlencoded\r\n" .
            "Ocp-Apim-Subscription-Key: $key\r\n";

        $options = array (
            'http' => array (
                'header' => $headers,
                'method' => 'POST',
                'content' => http_build_query ($data)
            )
        );

        $context  = stream_context_create ($options);
        $result = file_get_contents ($host . $path . $params, false, $context);

        $json = json_encode(json_decode($result), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $obj = json_decode($json, TRUE);

        foreach ($obj['flaggedTokens'] as $key => $value) {
            $token = $value['token'];;
            $suggestion = '';

            foreach ($value['suggestions'] as $key => $value) {
                $suggestion = strval($value['suggestion']);
                // pega sempre a primeira sugestão, cujo score é o maior
                if ($key == 0){
                    $input = str_replace ($token, $suggestion, $input); 
                    break;
                }
            }
        }

       return $input;

    }

}
