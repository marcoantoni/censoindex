<?php


namespace App\Decorators;


use BadMethodCallException;
use Google\Cloud\Language\V1\DependencyEdge;
use Google\Cloud\Language\V1\PartOfSpeech;
use Google\Cloud\Language\V1\TextSpan;
use Google\Cloud\Language\V1\Token as BaseToken;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\ForwardsCalls;


/**
 * Class Token
 * @package App\Collections\Token
 *
 * @mixin BaseToken|DependencyEdge|TextSpan|PartOfSpeech
 */
class Token
{
    use ForwardsCalls;

    protected $token;
    /**
     * @var Collection
     */
    private $collection;
    /**
     * @var int
     */
    private $index;

    public function __construct(BaseToken $token, Collection $collection, int $index)
    {
        $this->token = $token;
        $this->collection = $collection;
        $this->index = $index;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|void
     * @throws BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->token,$name)){
            return $this->forwardCallTo($this->token, $name, $arguments);
        } else if (method_exists($this->token->getDependencyEdge(),$name)){
            return $this->forwardCallTo($this->token->getDependencyEdge(), $name, $arguments);
        } else if (method_exists($this->token->getText(),$name)){
            return $this->forwardCallTo($this->token->getText(), $name, $arguments);
        } else if (method_exists($this->token->getPartOfSpeech(),$name)){
            return $this->forwardCallTo($this->token->getPartOfSpeech(), $name, $arguments);
        }

        static::throwBadMethodCallException($name);
    }


    public function getParent(): ?Token {
        $headTokenIndex = $this->getDependencyEdge()->getHeadTokenIndex();
        return $this->collection->first(function(Token $token) use ($headTokenIndex) {
            return $token->getIndex() == $headTokenIndex;
        });
    }

    /**
     * @return Collection|Token[]
     */
    public function getDependencies(): Collection {
        return $this->collection->filter(function(Token $token){
            return $token->getDependencyEdge()->getHeadTokenIndex() === $this->index;
        });
    }

    public function getDependenciesFlat($levels = 1): Collection {
        if ($levels == 1) {
            return $this->getDependencies();
        } else if ($levels > 1) {
            return $this->getDependencies()->map(function(Token $token) use ($levels) {
                return $token->getDependenciesFlat($levels - 1);
            })->merge($this->getDependencies())->flatten()->unique()
                ->keyBy(function(Token $token) {return $token->getIndex(); })
                ->sortBy(function(Token $token) {return $token->getIndex(); });
        }
        return collect([$this]);
    }

    /**
     * @return BaseToken
     */
    public function getToken(): BaseToken
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }


}
