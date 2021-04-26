<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle\Tests;

use Corerely\EntityAssociationInspectorBundle\AssociationManagerInterface;
use Corerely\EntityAssociationInspectorBundle\EntityInspector;
use Corerely\EntityAssociationInspectorBundle\Mapping\AssociationMappingBuilderInterface;
use PHPUnit\Framework\TestCase;

class EntityInspectorTest extends TestCase
{

    public function testIsSafeDeleteReturnTrueIfHasNoAssociations()
    {
        $mappedConfig = [
            'OtherEntityClass' => [
                // configs
            ],
        ];
        $mockedAssociationConfigBuilder = $this->createMock(AssociationMappingBuilderInterface::class);
        $mockedAssociationConfigBuilder->method('getAssociationsMapping')->willReturn($mappedConfig);

        $subject = new EntityInspector($mockedAssociationConfigBuilder, $this->createMock(AssociationManagerInterface::class));

        $this->assertTrue($subject->isSafeDelete(new StubEntity()));
    }

    public function testIsSafeDeleteReturnTrueIfAllAssociationsCascadeRemove()
    {
        $mappedConfig = [
            StubEntity::class => [
                'AssociatedEntityClassName' => [
                    'prop' => [
                        'owningSide' => [],
                        'inverseSide' => [
                            'cascade' => ['remove'],
                        ],
                    ],
                    'prop2' => [
                        'owningSide' => [],
                        'inverseSide' => [
                            'orphanRemoval' => true,
                        ],
                    ],
                ],
            ],
        ];
        $mockedAssociationConfigBuilder = $this->createMock(AssociationMappingBuilderInterface::class);
        $mockedAssociationConfigBuilder->method('getAssociationsMapping')->willReturn($mappedConfig);

        $mockedAssociationManager = $this->createMock(AssociationManagerInterface::class);
        $mockedAssociationManager->expects($this->never())->method('countAssociations');

        $subject = new EntityInspector($mockedAssociationConfigBuilder, $mockedAssociationManager);

        $this->assertTrue($subject->isSafeDelete(new StubEntity()));
    }

    public function testIsSafeDeleteReturnFalseIfEntityHasAssociations()
    {
        $mappedConfig = [
            StubEntity::class => [
                'AssociatedEntityClassName' => [
                    'prop' => [
                        'owningSide' => [],
                        'inverseSide' => [
                            'cascade' => ['persist', 'remove'],
                        ],
                    ],
                    'prop2' => [
                        'owningSide' => [],
                    ],
                ],
            ],
        ];
        $mockedAssociationConfigBuilder = $this->createMock(AssociationMappingBuilderInterface::class);
        $mockedAssociationConfigBuilder->method('getAssociationsMapping')->willReturn($mappedConfig);

        $mockedAssociationManager = $this->createMock(AssociationManagerInterface::class);
        $mockedAssociationManager->expects($this->once())->method('countAssociations')->willReturn(1);

        $subject = new EntityInspector($mockedAssociationConfigBuilder, $mockedAssociationManager);

        $this->assertFalse($subject->isSafeDelete(new StubEntity()));
    }
}

class StubEntity
{

}
