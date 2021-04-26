<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle;

use Corerely\EntityAssociationInspectorBundle\Mapping\AssociationMappingBuilderInterface;

class EntityInspector implements InspectorInterface
{
    public const CASCADE_DELETE = 'remove';

    public function __construct(
        private AssociationMappingBuilderInterface $associationMappingBuilder,
        private AssociationManagerInterface $associationManager,
    ) {
    }


    public function isSafeDelete(object $entity): bool
    {
        $associationMapping = $this->associationMappingBuilder->getAssociationsMapping();

        // If entity has no association
        if (! array_key_exists($entity::class, $associationMapping)) {
            return true;
        }

        $entityMapping = $associationMapping[$entity::class];

        foreach ($entityMapping as $owningAssociation => $fields) {
            foreach ($fields as $field => $mappingConfig) {
                // If cascade remove is enabled for relation,
                // we can skip finding related entities
                if ($this->isCascadeRemoveEnabled($mappingConfig)) {
                    continue;
                }

                // Try to find related entities
                $count = $this->associationManager->countAssociations($entity, $owningAssociation, $field);

                // If there is at least one active relation, entity can't be deleted
                if ($count > 0) {
                    return false;
                }
            }
        }

        // This point is reached in case all relation fields are cascade remove
        // or there is no active relations between given entity and it's associations
        return true;
    }

    protected function isCascadeRemoveEnabled(array $mappingConfig): bool
    {
        $orphanRemoval = $mappingConfig['inverseSide']['orphanRemoval'] ?? false;
        $cascade = $mappingConfig['inverseSide']['cascade'] ?? [];

        return true === $orphanRemoval || in_array(self::CASCADE_DELETE, $cascade, true);
    }
}
