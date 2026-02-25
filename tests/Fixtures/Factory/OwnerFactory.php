<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory;

use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Entity\Owner;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Owner>
 */
final class OwnerFactory extends PersistentObjectFactory
{

    public static function class(): string
    {
        return Owner::class;
    }

    protected function defaults(): array
    {
        return [];
    }
}
