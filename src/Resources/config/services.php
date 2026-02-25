<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Corerely\EntityAssociationInspectorBundle\EntityInspector;
use Corerely\EntityAssociationInspectorBundle\Repository\AssociationRepository;
use Corerely\EntityAssociationInspectorBundle\Mapping\AssociationMappingBuilder;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $config): void {
    $services = $config->services();

    $services->set('corerely_entity_association.association_repository', AssociationRepository::class)
        ->args([service('doctrine.orm.default_entity_manager')]);

    $services->set('corerely_entity_association.association_mapping_builder', AssociationMappingBuilder::class)
        ->args([service('doctrine.orm.default_entity_manager')]);

    $services->set('corerely_entity_association.inspector', EntityInspector::class)
        ->args([
            service('corerely_entity_association.association_repository'),
            service('corerely_entity_association.association_mapping_builder'),
        ]);

    $services->alias(EntityInspector::class, 'corerely_entity_association.inspector');
};
