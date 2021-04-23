<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle;

interface InspectorInterface
{
    /**
     * Check if given entity has associations to any other entity
     */
    public function hasAssociations(object $entity): bool;
}
