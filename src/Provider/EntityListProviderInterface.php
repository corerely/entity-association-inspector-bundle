<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle\Provider;

interface EntityListProviderInterface
{
    /**
     * Get all entities class names
     */
    public function all(): array;
}
