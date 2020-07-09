<?php


namespace App\Tree;

use App\Decorators\Token;
use App\Escola;
use App\Matricula;
use App\Tree\Location;
use App\Tree\School\School;
use App\Tree\Student\Student;
use Cache;
use Google\Cloud\Language\LanguageClient;
use Google\Cloud\Language\V1\AnnotateTextRequest\Features;
use Google\Cloud\Language\V1\AnnotateTextResponse;
use Google\Cloud\Language\V1\Document;
use Google\Cloud\Language\V1\Document\Type;
use Google\Cloud\Language\V1\Entity;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Google\Cloud\Language\V1\EntityMention;
use Google\Cloud\Language\V1\EntityMention\Type as MentionType;
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
use Google\Cloud\Language\V1\PartOfSpeech\Tag;
use Google\Cloud\Language\V1\PartOfSpeech\Tense;
use Google\Cloud\Language\V1\PartOfSpeech\Voice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;
use Session;

class DecisionTree {
    private $annotation;
    private $conditions = [];
    private $entityies;
    private $orderBy;
    public $query;
    public $response;
    public $sentence;
    private $tokens;
    public $responseType;
    public $userMessage = [];

    protected function normalizeSentence(string $sentence): string {
        $sentence = trim(preg_replace('/\s+/',' ',$sentence));
        $sentence = preg_replace('/(?<=\b\s)(\b[A-Z]{2}\b)/',' - \\1',$sentence);
        return $sentence;
    }

    public function __construct(string $sentence) {
        $this->sentence = $this->normalizeSentence($sentence);
        $this->tokens = array();
        $this->conditions = array();
    }

    public function process() {

        app(Pipeline::class)->send($this)->through([Location::class])->thenReturn();
        
        if (preg_match('/alun|estudante|matricula/', $this->sentence)) {
            app(Pipeline::class)
                ->send($this)
                ->through([
                    Student::class,
                ])->thenReturn();
        } else{
            app(Pipeline::class)->send($this)->through([School::class])->thenReturn();
        } 

        foreach ($this->conditions as $key => $condition) {
            $this->query = $this->query->where($condition['field'], $condition['operator'], $condition['value']);
        }

        
        // shows the answer to the user, which can be list or numeric
        // if contains the radical "quant" the answer is numeric

        if (preg_match('/quant/', $this->sentence)) {
            $this->responseType = 1;
            $this->response = $this->query->count();
        } else {
            $this->responseType = 2;
            // sorting only with list
            $this->query->orderBy($this->orderBy['column'], $this->orderBy['order']);
            $this->response = $this->query->get();
        
            // show user alert
            $count = $this->response->count();
            if ($count > 100) {
                Session::flash('warning', 'Sua pesquisa retornou '. $count . ' resultados!');
            } else if ($count == 0) {
                Session::flash('error', 
                    'Sua pesquisa não retornou nenhum resultado. Podem ter acontecido três coisas <br>
                        A resposta está certa e é mesmo <b>zero</b></br>
                        Não consegui entender sua pergunta. Lembre de escrever o nome da cidade e a UF do seguinte com maiúsculas como em <b>Porto Alegre/RS</b></br>
                        Talvez eu não tenho essa informação no momento </br>
                    '
                );
            }
        }

    }

    public function analyze() {
        $debug = '';

        # Your Google Cloud Platform project ID
        $projectId = 'YOUR_PROJECT_ID';

        # Instantiates a client
        $language = new LanguageServiceClient([
            'projectId' => env('GOOGLE_PROJECT_ID')
        ]);

        // Create a new Document, add text as content and set type to PLAIN_TEXT
        $document = (new Document())->setContent(strtoupper($this->sentence))->setType(Type::PLAIN_TEXT);

        $features = (new Features())->setExtractEntities(true)->setExtractSyntax(true);

        $this->annotation = $language->annotateText($document, $features);
        
        $debug .= 'ENTITIES<br>';
        $this->entityies = $this->annotation->getEntities();
        
        foreach ($this->entityies as $entity) {
            $debug .= sprintf('Name: %s <br>', $entity->getName());
            $debug .= sprintf('Type: %s <br>', EntityType::name($entity->getType()));
            $debug .= sprintf('Salience: %s <br>', $entity->getSalience());
            if ($entity->getMetadata()->offsetExists('wikipedia_url')) {
                $debug .= sprintf('Wikipedia URL: %s <br>', $entity->getMetadata()->offsetGet('wikipedia_url'));
            }
            if ($entity->getMetadata()->offsetExists('mid')) {
                $debug .= sprintf('Knowledge Graph MID: %s <br>', $entity->getMetadata()->offsetGet('mid'));
            }
            $debug .= '+++++++++++++++++++++++++<br>';
        }

        $debug .= '***********************<br>';
       
        // Call the analyze Entities function
        $response = $language->analyzeSyntax($document, []);

        // Print out information about each entity
        $tokens = $response->getTokens();
        foreach ($tokens as $token) {
            $debug .= sprintf('Token text: %s <br>' , $token->getText()->getContent());
            $debug .= sprintf('Token part of speech: %s <br>' , Tag::name($token->getPartOfSpeech()->getTag()));
            $debug .= sprintf('Token dependency edge: %s <br>' , $token->getDependencyEdge()->serializeToJsonString());
            $debug .= sprintf('Token lemma: %s <br>' , $token->getLemma());
            $this->tokens[] = $token->getLemma();
            $debug .= sprintf('Token aspect: %s <br>' , Aspect::name($token->getPartOfSpeech()->getAspect()));
            $debug .= sprintf('Token case: %s <br>' , PBCase::name($token->getPartOfSpeech()->getCase()));
            $debug .= sprintf('Token form: %s <br>' , Form::name($token->getPartOfSpeech()->getForm()));
            $debug .= sprintf('Token gender: %s <br>' , Gender::name($token->getPartOfSpeech()->getGender()));
            $debug .= sprintf('Token mood: %s <br>' , Mood::name($token->getPartOfSpeech()->getMood()));
            $debug .= sprintf('Token number: %s <br>' , Number::name($token->getPartOfSpeech()->getNumber()));
            $debug .= sprintf('Token person: %s <br>' , Person::name($token->getPartOfSpeech()->getPerson()));
            $debug .= sprintf('Token proper: %s <br>' , Proper::name($token->getPartOfSpeech()->getProper()));
            $debug .= sprintf('Token reciprocity: %s <br>' , Reciprocity::name($token->getPartOfSpeech()->getReciprocity()));
            $debug .= sprintf('Token tense: %s <br>' , Tense::name($token->getPartOfSpeech()->getTense()));
            $debug .= sprintf('Token voice: %s <br>' , Voice::name($token->getPartOfSpeech()->getVoice()));
            $debug .= '-------------------------<br>';

        }

        return $debug;

    }

    public function getEntityies(){
        return $this->entityies;
    }

    public function getTokens(){
        return $this->tokens;
    }

    public function addCondition(array $condition){
        $this->conditions[] = $condition;
    }

    public function setOrder(array $orderBy){
        $this->orderBy = $orderBy;
    }

}