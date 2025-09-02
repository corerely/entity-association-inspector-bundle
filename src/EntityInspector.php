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
        $entityClassName = ClassUtils::getClass($entity);
        $associations = $this->mappingBuilder->getAssociationsMapping($entityClassName);

        foreach ($associations as ['association' => $association, 'fieldName' => $fieldName]) {
            $count = $this->repository->countAssociations($entity, $association, $fieldName);

            // If there is at least one active relation, entity can't be deleted
            if ($count > 0) {
                return false;
            }
        }

        // There is no active relations between given entity and it's associations
        return true;
    }
}
