<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory;

use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Entity\Owner;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Owner>
 */
final class OwnerFactory extends PersistentProxyObjectFactory
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
