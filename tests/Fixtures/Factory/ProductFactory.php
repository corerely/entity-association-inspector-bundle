<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory;

use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Entity\Product;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Product|Proxy createOne(array $attributes = [])
 * @method static Product[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class ProductFactory extends ModelFactory
{

    protected function getDefaults(): array
    {
        return [];
    }

    protected static function getClass(): string
    {
        return Product::class;
    }
}
