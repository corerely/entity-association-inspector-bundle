<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle\Mapping;

use Corerely\EntityAssociationInspectorBundle\Provider\EntityListProviderInterface;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

final class AssociationMappingBuilder
{

    public function __construct(
        private EntityListProviderInterface $entityListProvider,
        private PropertyAssociationAnnotationFinder $annotationFinder,
    ) {
    }

    public function getAssociationsMapping(): array
    {
        return $this->buildAssociationsMapping();
    }

    private function getInverseSideAnnotation(ManyToOne|OneToOne $annotation): OneToMany|OneToOne|null
    {
        if (empty($annotation->inversedBy)) {
            return null;
        }

        $reflectionProperty = new \ReflectionProperty($annotation->targetEntity, $annotation->inversedBy);

        return $this->annotationFinder->findInverseSideAnnotation($reflectionProperty);
    }

    private function buildAssociationsMapping(): array
    {
        $entityClassNames = $this->entityListProvider->all();

        $associationsMapping = [];
        foreach ($entityClassNames as $entityClassName) {
            $reflectionClass = new \ReflectionClass($entityClassName);

            foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE) as $reflectionProperty) {
                $annotation = $this->annotationFinder->findOwningSideAnnotation($reflectionProperty);

                if (null !== $annotation) {
                    $annotationData = [
                        'owningSide' => $this->annotationToArray($annotation),
                        'inverseSide' => null,
                    ];

                    if ($inverseAnnotation = $this->getInverseSideAnnotation($annotation)) {
                        $annotationData['inverseSide'] = $this->annotationToArray($inverseAnnotation);
                    }

                    $association = $annotation->targetEntity;
                    $property = $reflectionProperty->getName();

                    $associationsMapping[$association][$entityClassName][$property] = $annotationData;
                }
            }
        }

        return $associationsMapping;
    }

    private function annotationToArray(object $annotation): array
    {
        return (new AnnotationArrayConverter($annotation))->toArray();
    }
}
