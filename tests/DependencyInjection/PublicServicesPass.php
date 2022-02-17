<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PublicServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $services = ['corerely_entity_association.inspector'];

        foreach ($services as $service) {
            $definition = $container->getDefinition($service);
            $definition->setPublic(true);
        }
    }
}
