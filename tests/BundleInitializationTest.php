<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests;

use Corerely\EntityAssociationInspectorBundle\EntityInspector;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BundleInitializationTest extends KernelTestCase
{
    private ContainerInterface $testContainer;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->testContainer = self::getContainer();
    }

    public function testEntityInspectorService(): void
    {
        $this->assertTrue($this->testContainer->has(EntityInspector::class));

        $service = $this->testContainer->get(EntityInspector::class);
        $this->assertInstanceOf(EntityInspector::class, $service);
    }
}
