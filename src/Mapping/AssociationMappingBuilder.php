<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Mapping;

use Doctrine\ORM\EntityManagerInterface;

final class AssociationMappingBuilder
{
    private array $mapping = [];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Generate array of associations
     *
     * For example if there is Category associated with Product as ManyToOne array will looks like for Product
     * [
     *      [
     *           'association' => App\Entity\Category,
     *           'fieldName' => 'product',
     *      ],
     * ]
     */
    public function getAssociationsMapping(string $entityClassName): array
    {
        return $this->mapping[$entityClassName] ??= $this->buildMapping($entityClassName);
    }

    private function buildMapping(string $entityClassName): array
    {
        $associations = [];
        $metadata = $this->entityManager->getClassMetadata($entityClassName);

        foreach ($metadata->getAssociationMappings() as $mapping) {
            // If owning side, check if "inversedBy" is orphanRemoval or cascade remove, otherwise need to register "targetEntity" as one that need to be checked before delete
            if ($mapping['isOwningSide']) {
                $targetEntity = $mapping['targetEntity'];

                if ($mapping['inversedBy']) {
                    $targetMetadata = $this->entityManager->getClassMetadata($targetEntity);
                    $targetAssociationMapping = $targetMetadata->getAssociationMapping($mapping['inversedBy']);

                    if ($targetAssociationMapping['orphanRemoval'] || $targetAssociationMapping['isCascadeRemove']) {
                        continue;
                    }
                }

                $associations[] = [
                    'association' => $entityClassName,
                    'fieldName' => $mapping['fieldName'],
                ];
            }
        }

        return $associations;
    }
}
