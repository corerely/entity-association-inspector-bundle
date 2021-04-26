<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle;

interface AssociationManagerInterface
{
    public function countAssociations(object $entity, string $owningAssociation, string $owningFieldName): int;
}
