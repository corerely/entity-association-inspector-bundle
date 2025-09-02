<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Mapping;

use Doctrine\ORM\EntityManagerInterface;

final class AssociationMappingBuilder
{
    private ?array $mapping = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Generate array of associations configuration
     * where each key is an entity if any other entity relies on it
     *
     * For example if there is Category associated Product as ManyToOne array will looks like
     * [ 'App\Entity\Product' =>
     *      [
     *           'association' => App\Entity\Category,
     *           'fieldName' => 'product',
     *      ],
     * ]
     */
    public function getAssociationsMapping(): array
    {
        return $this->mapping ??= $this->buildMapping();
    }

    private function buildMapping(): array
    {
        $entities = $this->entityManager->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();

        $associationsMapping = [];
        foreach ($entities as $entityClassName) {
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

                    $associationsMapping[$targetEntity][] = [
                        'association' => $entityClassName,
                        'fieldName' => $mapping['fieldName'],
                    ];
                }
            }
        }

        return $associationsMapping;
    }
}
