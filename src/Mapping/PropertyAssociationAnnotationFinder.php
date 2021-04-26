<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Mapping;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

final class PropertyAssociationAnnotationFinder
{

    public function __construct(private Reader $reader)
    {
    }

    public function findOwningSideAnnotation(\ReflectionProperty $reflectionProperty): ManyToOne|OneToOne|null
    {
        $annotations = $this->reader->getPropertyAnnotations($reflectionProperty);

        foreach ($annotations as $annotation) {
            // ManyToOne is always owning side of relation
            if ($annotation instanceof ManyToOne) {
                return $annotation;
            }

            // OneToOne can be owning side. Inverse side has "mappedBy" property set
            if ($annotation instanceof OneToOne && empty($annotation->mappedBy)) {
                return $annotation;
            }
        }

        return null;
    }

    public function findInverseSideAnnotation(\ReflectionProperty $reflectionProperty): OneToMany|OneToOne|null
    {
        $annotations = $this->reader->getPropertyAnnotations($reflectionProperty);

        foreach ($annotations as $annotation) {
            // OneToMany is always inverse side
            if ($annotation instanceof OneToMany) {
                return $annotation;
            }

            // OneToOne annotation is inverse side when mappedBy is set
            if ($annotation instanceof OneToOne && ! empty($annotation->mappedBy)) {
                return $annotation;
            }
        }

        return null;
    }
}
