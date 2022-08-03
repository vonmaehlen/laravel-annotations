<?php

namespace Collective\Annotations\Database\Eloquent\Attributes;

use Collective\Annotations\Database\Eloquent\Attributes\Attributes\Bind;
use Collective\Annotations\Database\ScanStrategyInterface;
use ReflectionAttribute;
use ReflectionClass;

class AttributeStrategy implements ScanStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function support(ReflectionClass $class): bool
    {
        return count(array_filter($class->getAttributes(), fn($attribute) => is_a($attribute, Bind::class))) > 0;
    }

    /**
     * @inheritDoc
     */
    public function getBindings(ReflectionClass $class): array
    {
        return array_map(
            fn (ReflectionAttribute $attribute) => $attribute->newInstance()->binding,
            array_filter($class->getAttributes(), fn($attribute) => is_a($attribute, Bind::class)),
        );
    }
}
