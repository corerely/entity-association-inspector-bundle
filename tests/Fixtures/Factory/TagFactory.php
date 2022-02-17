<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory;

use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Entity\Tag;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Tag|Proxy createOne(array $attributes = [])
 * @method static Tag[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class TagFactory extends ModelFactory
{

    protected static function getClass(): string
    {
        return Tag::class;
    }

    protected function getDefaults(): array
    {
        return [];
    }
}
