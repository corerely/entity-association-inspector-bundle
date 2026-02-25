<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory;

use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Entity\Product;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Product>
 */
final class ProductFactory extends PersistentObjectFactory
{

    protected function defaults(): array
    {
        return [];
    }

    public static function class(): string
    {
        return Product::class;
    }
}
