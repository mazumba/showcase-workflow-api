<?php

declare(strict_types=1);

namespace App\Supportive\OpenApi;

use AllowDynamicProperties;
use OpenApi\Attributes as OA;
use ReflectionClass;
use ReflectionException;

#[AllowDynamicProperties]
final class Example
{
    /**
     * @param class-string $class
     *
     * @throws ReflectionException
     */
    public function __construct(string $class)
    {
        $reflectionClass = new ReflectionClass($class);

        foreach ($reflectionClass->getProperties() as $property) {
            $propertyAttributes = $property->getAttributes();

            foreach ($propertyAttributes as $attribute) {
                /** @var OA\Property $propertyAttribute */
                $propertyAttribute = $attribute->newInstance();
                if ($propertyAttribute instanceof OA\Property && $propertyAttribute->example !== null) {
                    $propertyName = $property->getName();
                    $this->{$propertyName} = $propertyAttribute->example;
                    break;
                }
            }
        }
    }
}
