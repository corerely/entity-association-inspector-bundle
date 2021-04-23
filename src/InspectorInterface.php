<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle;

interface InspectorInterface
{
    /**
     * Check if it's safe to delete given entity.
     */
    public function isSafeDelete(object $entity): bool;
}
