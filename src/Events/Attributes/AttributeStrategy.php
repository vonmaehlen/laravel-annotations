<?php

namespace Collective\Annotations\Events\Attributes;

use Collective\Annotations\Events\Attributes\Attributes\Hears;
use Collective\Annotations\Events\ScanStrategyInterface;
use ReflectionAttribute;
use ReflectionMethod;

class AttributeStrategy implements ScanStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function getEvents(ReflectionMethod $method): array
    {
        return array_map(
            fn (ReflectionAttribute $attribute) => $attribute->newInstance()->events,
            array_values(array_filter(
                $method->getAttributes(),
                fn($attribute) => is_a($attribute, Hears::class, true)
            )),
        );
    }
}
