<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DataCollector;

use JDecool\Bundle\TwigConstantAccessorBundle\Twig\ConstantAccessorExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class ConstantCollector extends DataCollector
{
    /** @var ConstantAccessorExtension */
    private $accessor;

    /**
     * Constructor
     *
     * @param ConstantAccessorExtension $accessor
     */
    public function __construct(ConstantAccessorExtension $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'constants' => $this->accessor->getGlobals(),
        ];
    }

    /**
     * Get defined constants
     *
     * @return array
     */
    public function getConstants()
    {
        return $this->data['constants'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'constant_accessor.constant_collector';
    }
}
