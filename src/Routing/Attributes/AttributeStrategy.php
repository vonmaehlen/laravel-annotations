<?php

namespace Collective\Annotations\Routing\Attributes;

use Collective\Annotations\Routing\Attributes\Attributes\Route;
use Collective\Annotations\Routing\Meta;
use Collective\Annotations\Routing\ScanStrategyInterface;
use ReflectionAttribute;
use ReflectionClass;

class AttributeStrategy implements ScanStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function getClassMetaList(ReflectionClass $class): array
    {
        $attributes = array_values(array_filter(
            $class->getAttributes(),
            fn($attribute) => $this->isRoutingAttribute($attribute->getName()),
        ));

        return array_map(
            fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
            $attributes,
        );
    }

    /**
     * @inheritDoc
     */
    public function getMethodMetaLists(ReflectionClass $class): array
    {
        $attributes = [];

        foreach ($class->getMethods() as $method) {
            if ($method->class == $class->name) {
                $results = array_values(array_filter(
                    $method->getAttributes(),
                    fn($attribute) => $this->isRoutingAttribute($attribute->getName()),
                ));

                if (count($results) > 0) {
                    $attributes[$method->name] = array_map(
                        fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
                        $results
                    );
                }
            }
        }

        return $attributes;
    }

    protected function isRoutingAttribute(mixed $object_or_class): bool
    {
        return is_a($object_or_class, Meta::class, true);
    }
}
