<?php

namespace App\Console\Commands;

use App\Escola;
use App\Municipio;
use App\Tree\Answer;
use App\Tree\DecisionTree;
use App\UF;
use Cache;
use DB;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
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
use Google\Cloud\Language\V1\Token;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Parse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pergunta {sentence} {--A|with-analysis} {--Q|show-query} {--as-array}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws ApiException
     * @throws ValidationException
     */
    public function handle()
    {
        $sentence = $this->argument('sentence');


        $decision_tree = new DecisionTree($sentence);

        if ($this->option('with-analysis')) {
            $annotateTextResponse = $decision_tree->getAnnotateTextResponse();
            $analysis = $this->analyze_all($annotateTextResponse);
            $this->output->write($analysis);
        }

        $answer = $decision_tree->process();

        if ($answer->isWithinDomain()) {

            foreach ($answer->getWarnings() as $warning) {
                $this->output->warning($warning);
            }

            if ($this->option('show-query')) {
                $this->output->write(sprintf(
                    preg_replace('/\?/','%s',$decision_tree->getQuery()->toSql()),
                    ...array_map(function($value) {
                        if (is_bool($value)) return "true";
                        else if (is_string($value)) return "'$value'";
                        else return $value;
                    },$decision_tree->getQuery()->getBindings())
                ));
            }

            $this->output->note(sprintf("Tipo de resposta: %s", Answer::name($answer->getType())));

            $response = $answer->getValue();

            if (is_array($response)) {
                if ($this->option('as-array')){
                    $response = "[".PHP_EOL."'".join("',".PHP_EOL."'",$response)."'".PHP_EOL."]";
                } else {
                    $response = join(PHP_EOL,$response);
                }
            }

            $this->output->success($response);

            return 0;
        } else {
            $this->error("Desculpe, não entendi sua pergunta");
            $this->error("Você deve perguntar alguma coisa sobre escolas");

            return 1;
        }
    }


    /**
     * @param AnnotateTextResponse $response
     * @return string
     */
    function analyze_all(AnnotateTextResponse $response)
    {
        // Process Entities
        $entities = $response->getEntities();
        $output = '';
        foreach ($entities as $entity) {
            $output .= sprintf('Name: %s' . PHP_EOL, $entity->getName());
            $output .= sprintf('Type: %s' . PHP_EOL, EntityType::name($entity->getType()));
            $output .= sprintf('Salience: %s' . PHP_EOL, $entity->getSalience());
            if ($entity->getMetadata()->offsetExists('wikipedia_url')) {
                $output .= sprintf('Wikipedia URL: %s' . PHP_EOL, $entity->getMetadata()->offsetGet('wikipedia_url'));
            }
            if ($entity->getMetadata()->offsetExists('mid')) {
                $output .= sprintf('Knowledge Graph MID: %s' . PHP_EOL, $entity->getMetadata()->offsetGet('mid'));
            }
            $output .= sprintf('Mentions:' . PHP_EOL);
            foreach ($entity->getMentions() as $mention) {
                /** @var EntityMention $mention */
                $output .= sprintf('  Begin Offset: %s' . PHP_EOL, $mention->getText()->getBeginOffset());
                $output .= sprintf('  Content: %s' . PHP_EOL, $mention->getText()->getContent());
                $output .= sprintf('  Mention Type: %s' . PHP_EOL, MentionType::name($mention->getType()));
                $output .= sprintf(PHP_EOL);
            }
            $output .= sprintf(PHP_EOL);
        }
        // Process Syntax
        $tokens = $response->getTokens();
        // Print out information about each entity
        $i = 0;
        foreach ($tokens as $token) {
            /** @var Token $token */
            $output .= sprintf('Token index: %d' . PHP_EOL, $i++);
            $output .= sprintf('Token text: %s' . PHP_EOL, $token->getText()->getContent());
            $output .= sprintf('Token part of speech: %s' . PHP_EOL, Tag::name($token->getPartOfSpeech()->getTag()));
            $output .= sprintf('Token dependency edge: %s' . PHP_EOL, $token->getDependencyEdge()->serializeToJsonString());
            $output .= sprintf('Token lemma: %s' . PHP_EOL, $token->getLemma());
            $output .= sprintf('Token aspect: %s' . PHP_EOL, Aspect::name($token->getPartOfSpeech()->getAspect()));
            $output .= sprintf('Token case: %s' . PHP_EOL, PBCase::name($token->getPartOfSpeech()->getCase()));
            $output .= sprintf('Token form: %s' . PHP_EOL, Form::name($token->getPartOfSpeech()->getForm()));
            $output .= sprintf('Token gender: %s' . PHP_EOL, Gender::name($token->getPartOfSpeech()->getGender()));
            $output .= sprintf('Token mood: %s' . PHP_EOL, Mood::name($token->getPartOfSpeech()->getMood()));
            $output .= sprintf('Token number: %s' . PHP_EOL, Number::name($token->getPartOfSpeech()->getNumber()));
            $output .= sprintf('Token person: %s' . PHP_EOL, Person::name($token->getPartOfSpeech()->getPerson()));
            $output .= sprintf('Token proper: %s' . PHP_EOL, Proper::name($token->getPartOfSpeech()->getProper()));
            $output .= sprintf('Token reciprocity: %s' . PHP_EOL, Reciprocity::name($token->getPartOfSpeech()->getReciprocity()));
            $output .= sprintf('Token tense: %s' . PHP_EOL, Tense::name($token->getPartOfSpeech()->getTense()));
            $output .= sprintf('Token voice: %s' . PHP_EOL, Voice::name($token->getPartOfSpeech()->getVoice()));
            $output .= sprintf(PHP_EOL);
        }
        return $output;
    }
}
