<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

final class DoctrineAssociationManager implements AssociationManagerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Count number of association that relies on given entity using owning side field for join query
     */
    public function countAssociations(object $entity, string $owningAssociation, string $owningFieldName): int
    {
        // Try to find related entities
        $repository = $this->entityManager->getRepository($owningAssociation);

        /** @var QueryBuilder $qb */
        $qb = $repository->createQueryBuilder('a');

        $count = $qb
            ->select('count(a.id)')
            ->innerJoin(sprintf('a.%s', $owningFieldName), 'j')
            ->andWhere('j = :entity')
            ->setParameter('entity', $entity)
            ->getQuery()
            ->getSingleScalarResult();

        return (int)$count;
    }
}
