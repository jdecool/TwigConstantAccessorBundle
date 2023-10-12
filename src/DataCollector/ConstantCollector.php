<?php

namespace JDecool\Bundle\TwigConstantAccessorBundle\DataCollector;

use JDecool\Bundle\TwigConstantAccessorBundle\Accessor\ConstantCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class ConstantCollector extends DataCollector
{
    public function __construct(
        private ConstantCollection $accessors,
    ) {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data = [
            'accessors' => $this->accessors->toArray(),
        ];
    }

    /**
     * Get all constants accessors declared.
     */
    public function getAccessors(): ConstantCollection
    {
        return $this->data['accessors'];
    }

    public function getName(): string
    {
        return 'constant_accessor.constant_collector';
    }

    public function reset(): void
    {
    }
}
