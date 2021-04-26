<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Mapping;

use Corerely\EntityAssociationInspectorBundle\Mapping\AssociationMappingBuilder;
use Corerely\EntityAssociationInspectorBundle\Mapping\PropertyAssociationAnnotationFinder;
use Corerely\EntityAssociationInspectorBundle\Provider\EntityListProviderInterface;
use Corerely\EntityAssociationInspectorBundle\Tests\TestKernel;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Framework\TestCase;

class AssociationMappingBuilderTest extends TestCase
{

    public function testGetAssociationsMapping()
    {
        $kernel = new TestKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        $annotationFinder = $container->get(PropertyAssociationAnnotationFinder::class);

        $mockedListProvider = $this->createPartialMock(EntityListProviderInterface::class, ['all']);
        $mockedListProvider->expects($this->once())->method('all')->willReturn([
            EntityA::class,
            EntityB::class,
            EntityC::class,
        ]);

        $subject = new AssociationMappingBuilder($mockedListProvider, $annotationFinder);

        $this->assertEquals([
            EntityB::class => [
                EntityA::class => [
                    'entityB' => [
                        'owningSide' => [
                            'targetEntity' => EntityB::class,
                            'inversedBy' => 'entitiesA',
                            'cascade' => null,
                            'fetch' => 'LAZY'
                        ],
                        'inverseSide' => [
                            'targetEntity' => EntityA::class,
                            'mappedBy' => 'entityB',
                            'cascade' => ['remove'],
                            'orphanRemoval' => true,
                            'indexBy' => null,
                            'fetch' => 'LAZY'
                        ],
                    ],
                    'entityBOneToOne' => [
                        'owningSide' => [
                            'targetEntity' => EntityB::class,
                            'inversedBy' => null,
                            'cascade' => null,
                            'fetch' => 'LAZY',
                            'mappedBy' => null,
                            'orphanRemoval' => false,
                        ],
                        'inverseSide' => null,
                    ],
                ],
            ],
            EntityC::class => [
                EntityA::class => [
                    'entityC' => [
                        'owningSide' => [
                            'targetEntity' => EntityC::class,
                            'inversedBy' => 'entityA',
                            'cascade' => null,
                            'orphanRemoval' => false,
                            'mappedBy' => null,
                            'fetch' => 'LAZY'
                        ],
                        'inverseSide' => [
                            'targetEntity' => EntityA::class,
                            'inversedBy' => null,
                            'mappedBy' => 'entityC',
                            'cascade' => ['remove'],
                            'orphanRemoval' => false,
                            'fetch' => 'LAZY'
                        ],
                    ],
                ],
            ],
        ], $subject->getAssociationsMapping());
    }
}

class EntityA
{
    /**
     * @ORM\ManyToOne(targetEntity="Corerely\EntityAssociationInspectorBundle\Tests\Mapping\EntityB", inversedBy="entitiesA")
     */
    private $entityB;

    /**
     * @ORM\OneToOne(targetEntity="Corerely\EntityAssociationInspectorBundle\Tests\Mapping\EntityB")
     */
    private $entityBOneToOne;

    /**
     * @ORM\OneToOne(targetEntity="Corerely\EntityAssociationInspectorBundle\Tests\Mapping\EntityC", inversedBy="entityA")
     */
    private $entityC;
}

class EntityB
{
    /**
     * @ORM\OneToMany(targetEntity="Corerely\EntityAssociationInspectorBundle\Tests\Mapping\EntityA", mappedBy="entityB", cascade="remove", orphanRemoval=true)
     */
    private $entitiesA;
}

class EntityC
{
    /**
     * @ORM\OneToOne(targetEntity="Corerely\EntityAssociationInspectorBundle\Tests\Mapping\EntityA", mappedBy="entityC", cascade={"remove"})
     */
    private $entityA;
}
