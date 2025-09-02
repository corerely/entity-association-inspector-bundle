<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle;

use Corerely\EntityAssociationInspectorBundle\Mapping\AssociationMappingBuilder;
use Corerely\EntityAssociationInspectorBundle\Repository\AssociationRepository;
use Doctrine\Common\Util\ClassUtils;

final readonly class EntityInspector
{
    public function __construct(
        private AssociationRepository $repository,
        private AssociationMappingBuilder $mappingBuilder,
    ) {
    }

    public function isSafeToRemove(object $entity): bool
    {
        $associationMapping = $this->mappingBuilder->getAssociationsMapping();
        $entityClassName = ClassUtils::getClass($entity);

        // If entity has no association
        if (!array_key_exists($entityClassName, $associationMapping)) {
            return true;
        }

        $associations = $associationMapping[$entityClassName];
        foreach ($associations as $association) {
            $owningAssociation = $association['association'];
            $fieldName = $association['fieldName'];

            $count = $this->repository->countAssociations($entity, $owningAssociation, $fieldName);
            // If there is at least one active relation, entity can't be deleted
            if ($count > 0) {
                return false;
            }
        }

        // There is no active relations between given entity and it's associations
        return true;
    }
}
