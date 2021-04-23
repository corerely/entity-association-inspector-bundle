<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle\Mapping;

final class AnnotationArrayConverter
{

    public function __construct(private object $annotation)
    {
    }

    public function toArray(): array
    {
        $reflectionClass = new \ReflectionClass($this->annotation);

        $annotationArray = [];
        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $annotationArray[$reflectionProperty->getName()] = $this->annotation->{$reflectionProperty->getName()};
        }

        return $annotationArray;
    }
}
