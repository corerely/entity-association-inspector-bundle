<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory;

use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Entity\Owner;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Owner|Proxy createOne(array $attributes = [])
 * @method static Owner[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class OwnerFactory extends ModelFactory
{

    protected static function getClass(): string
    {
        return Owner::class;
    }

    protected function getDefaults(): array
    {
        return [];
    }
}
