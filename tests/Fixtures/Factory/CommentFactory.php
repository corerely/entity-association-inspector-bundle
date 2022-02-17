<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory;

use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Entity\Comment;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Comment|Proxy createOne(array $attributes = [])
 * @method static Comment[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class CommentFactory extends ModelFactory
{

    protected function getDefaults(): array
    {
        return [];
    }

    protected static function getClass(): string
    {
        return Comment::class;
    }
}
