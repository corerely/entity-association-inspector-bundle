<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Provider;

use Doctrine\ORM\EntityManagerInterface;

class DoctrineEntityListProvider implements EntityListProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function all(): array
    {
        return $this->entityManager->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
    }
}
