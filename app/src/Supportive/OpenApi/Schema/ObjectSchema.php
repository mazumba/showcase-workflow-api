<?php

declare(strict_types=1);

namespace App\Supportive\OpenApi\Schema;

use App\Supportive\OpenApi\Example;
use Attribute;
use OpenApi\Attributes\Schema;
use ReflectionClass;
use ReflectionException;

#[Attribute]
final class ObjectSchema extends Schema
{
    /**
     * @param class-string $class
     *
     * @throws ReflectionException
     */
    public function __construct(string $class)
    {
        $className = (new ReflectionClass($class))->getShortName();
        parent::__construct(
            description: \sprintf('The schema for %s objects', $className),
            example: new Example($class)
        );
    }
}
