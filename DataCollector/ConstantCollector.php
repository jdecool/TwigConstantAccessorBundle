<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DataCollector;

use JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class ConstantCollector extends DataCollector
{
    /** @var ConstantCollection */
    private $accessors;

    /**
     * Constructor
     *
     * @param ConstantCollection $accessors
     */
    public function __construct(ConstantCollection $accessors)
    {
        $this->accessors = $accessors;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'accessors' => $this->accessors->toArray(),
        ];
    }

    /**
     * Get all constants accessors declared
     *
     * @return ConstantCollection
     */
    public function getAccessors()
    {
        return $this->data['accessors'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'constant_accessor.constant_collector';
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
    }
}
