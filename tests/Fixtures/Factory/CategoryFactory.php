<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory;

use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Entity\Category;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Category|Proxy createOne(array $attributes = [])
 * @method static Category[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class CategoryFactory extends ModelFactory
{

    protected function getDefaults(): array
    {
        return [];
    }

    protected static function getClass(): string
    {
        return Category::class;
    }
}
