<?php

namespace App\Tree;

use App\Escola;
use App\Matricula;
use App\Tree\Answer;
use App\Tree\Location;
use App\Tree\Course\Course;
use App\Tree\School\School;
use App\Tree\Student\Student;
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
use Google\Cloud\Language\V1\PartOfSpeech\Gender;
use Google\Cloud\Language\V1\PartOfSpeech\Tag;
use Google\Cloud\Language\V1\PartOfSpeech\Person;
use Illuminate\Pipeline\Pipeline;
use Session;

class DecisionTree {

    private $annotation;
    private $conditions = [];
    private $entityies;
    public $query;
    public $response;
    public $sentence;
    private $tokens;

    public $answer;

    protected function normalizeSentence(string $sentence): string {
        $sentence = trim(preg_replace('/\s+/',' ',$sentence));
        $sentence = preg_replace('/(?<=\b\s)(\b[A-Z]{2}\b)/',' - \\1',$sentence);
        return $sentence;
    }

    public function __construct(string $sentence) {
        $this->sentence = $this->normalizeSentence($sentence);
        $this->tokens = array();
        $this->conditions = array();
        $this->answer = new Answer();
        // Por padrao a resposta se refere a lista de escolas. 
        $this->answer->setResponseTable(Answer::SCHOOL);
        $this->answer->setResponseType(Answer::LIST);
    }

    public function process() {

        $questionIsCourse = false;
        app(Pipeline::class)->send($this)->through([Location::class])->thenReturn();
        
        // Percorre os tokens para maperar em qual tabela a pergunta está se relacionando
        foreach ($this->tokens as $key => $token) {
            
            if (preg_match('/alun|estudante|matricula/', $token)) {
                app(Pipeline::class)
                    ->send($this)
                    ->through([
                        Student::class,
                    ])->thenReturn();
                    break;
            } else  if (preg_match('/escola|instituto|colegio/', $token)){
                app(Pipeline::class)
                    ->send($this)
                    ->through([School::class])
                    ->thenReturn();
                    break;
            } else  if (preg_match('/curso/', $token)){
                app(Pipeline::class)
                    ->send($this)
                    ->through([Course::class])
                    ->thenReturn();
                $questionIsCourse = true;
                $this->answer->setResponseTable(Answer::COURSE);
                break;
            }            
        }

        if (! $questionIsCourse) {
            foreach ($this->conditions as $key => $condition) {
                $this->query = $this->query->where($condition['field'], $condition['operator'], $condition['value']);
            }
        } 
        // Se o primeiro token tiver o radical quant, a resposta é numérica
        if (preg_match('/quant/', $this->tokens[0])) {
            $this->answer->setResponseType(Answer::NUMBER);
            $this->response = $this->query->count();
            
            if (session('schoolsFound') > 1) {
                $this->answer->setResponseType(Answer::NUMBERLIST);
            }

        } else {
            $this->response = $this->query->get();
        }
        return $this->answer;
    }

    public function analyze() {
        $debug = '';

        # Your Google Cloud Platform project ID
        $projectId = 'YOUR_PROJECT_ID';

        # Instantiates a client
        $language = new LanguageServiceClient([
            'projectId' => env('GOOGLE_PROJECT_ID'),
            'language'  => 'pt'
        ]);

        // Create a new Document, add text as content and set type to PLAIN_TEXT
        $document = (new Document())->setContent($this->sentence)->setType(Type::PLAIN_TEXT);

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
            
            $debug .= sprintf('Token gender: %s <br>' , Gender::name($token->getPartOfSpeech()->getGender()));
            $debug .= sprintf('Token person: %s <br>' , Person::name($token->getPartOfSpeech()->getPerson()));
            $debug .= '-------------------------<br>';
            
            // so adiciona o token se ele nao for uma stopword
            if ($this->removeStopWords($token->getLemma())){
                $this->tokens[] = $token->getLemma();
            }
        }

        return $debug;

    }

    public function getEntityies(){
        return $this->entityies;
    }

    public function setEntityies($entityies){
        //$this->entityies = array();
        $this->entityies = $entityies;
        foreach ($entityies as $key => $value) {
            print('setEntidade: ' . $value->getName() );
        }
    }

    public function getTokens(){
        return $this->tokens;
    }

    // adiciona uma condição  a clausula where
    public function addCondition(array $condition){
        $this->conditions[] = $condition;
    }

    public function getConditions(){
        return $this->conditions;
    }
    
    /**
    *
    * Teste se uma palavra é uma stopword
    * Não é uma lista completa de stopwords em pt-br
    * Essas stopwords são comuns nas perguntas que o sistema pretende responder
    * @author   Marco Antoni <marco.antoni910@gmail.com>
    * @return   bool
    *
    */
    public function removeStopWords(String $word){
        $stopwords = array('a', 'o', 'as', 'os', 'de', 'que', 'do', 'em', 'para', 'é', 'dos', 'no');
        
        if (array_search($word, $stopwords))
            return false;
        else
            return true;

    }

    public function getQuery(){
        return $this->query;
    }

    public function setQuery($query){
        $this->query = $query;
    }
}