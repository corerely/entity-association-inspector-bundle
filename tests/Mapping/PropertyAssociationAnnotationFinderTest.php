<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Mapping;

use Corerely\EntityAssociationInspectorBundle\Mapping\PropertyAssociationAnnotationFinder;
use Corerely\EntityAssociationInspectorBundle\Tests\TestKernel;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\TestCase;

class PropertyAssociationAnnotationFinderTest extends TestCase
{

    public function testFindOwningSideAnnotation()
    {
        $kernel = new TestKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        $subject = $container->get(PropertyAssociationAnnotationFinder::class);

        $reflectionProperty1 = new \ReflectionProperty(Entity::class, 'prop1');
        $reflectionProperty2 = new \ReflectionProperty(Entity::class, 'prop2');
        $reflectionProperty3 = new \ReflectionProperty(Entity::class, 'prop3');
        $reflectionProperty4 = new \ReflectionProperty(Entity::class, 'prop4');

//        $subject = new PropertyAssociationAnnotationFinder($reader);

        $this->assertInstanceOf(ORM\ManyToOne::class, $subject->findOwningSideAnnotation($reflectionProperty1));
        $this->assertInstanceOf(ORM\OneToOne::class, $subject->findOwningSideAnnotation($reflectionProperty2));

        $this->assertNull($subject->findOwningSideAnnotation($reflectionProperty3));
        $this->assertNull($subject->findOwningSideAnnotation($reflectionProperty4));
    }

    public function testFindInverseSideAnnotation()
    {
        $kernel = new TestKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        $subject = $container->get(PropertyAssociationAnnotationFinder::class);

        $reflectionProperty1 = new \ReflectionProperty(Entity::class, 'prop1');
        $reflectionProperty2 = new \ReflectionProperty(Entity::class, 'prop2');
        $reflectionProperty3 = new \ReflectionProperty(Entity::class, 'prop3');
        $reflectionProperty4 = new \ReflectionProperty(Entity::class, 'prop4');

        $this->assertNull($subject->findInverseSideAnnotation($reflectionProperty1));
        $this->assertNull($subject->findInverseSideAnnotation($reflectionProperty2));

        $this->assertInstanceOf(ORM\OneToOne::class, $subject->findInverseSideAnnotation($reflectionProperty3));
        $this->assertInstanceOf(ORM\OneToMany::class, $subject->findInverseSideAnnotation($reflectionProperty4));
    }
}

class Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="SomeClass")
     */
    private $prop1;

    /**
     * @ORM\OneToOne(targetEntity="SomeClass")
     */
    private $prop2;

    /**
     * @ORM\OneToOne(targetEntity="SomeClass", mappedBy="someProp")
     */
    private $prop3;

    /**
     * @ORM\OneToMany(targetEntity="SomeClass", mappedBy="someProp")
     */
    private $prop4;
}
