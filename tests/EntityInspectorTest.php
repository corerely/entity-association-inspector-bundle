<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests;

use Corerely\EntityAssociationInspectorBundle\EntityInspector;
use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory\CategoryFactory;
use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory\CommentFactory;
use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory\OwnerFactory;
use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory\ProductFactory;
use Corerely\EntityAssociationInspectorBundle\Tests\Fixtures\Factory\TagFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class EntityInspectorTest extends KernelTestCase
{
    use ResetDatabase, Factories;

    public function testDeleteOneToManyWithEnabledCascadeRemove(): void
    {
        $product = ProductFactory::createOne([
            'comments' => CommentFactory::new()->many(3),
        ]);

        ProductFactory::assert()->count(1);
        CommentFactory::assert()->count(3);

        $this->assertCount(3, $product->getComments());

        $inspector = $this->getInspector();
        $this->assertTrue($inspector->isSafeDelete($product->object()));
    }

    public function testDeleteManyToOneWithInverseSide(): void
    {
        $product = ProductFactory::createOne([
            'comments' => CommentFactory::new()->many(1),
        ]);

        ProductFactory::assert()->count(1);
        $comment = $product->getComments()->first();

        $inspector = $this->getInspector();
        $this->assertTrue($inspector->isSafeDelete($comment));
    }

    public function testDeleteManyToOneWithoutInverseSide(): void
    {
        $category = CategoryFactory::createOne();
        $product = ProductFactory::createOne([
            'category' => $category,
        ]);

        ProductFactory::assert()->count(1);
        $this->assertNotNull($product->getCategory());

        $inspector = $this->getInspector();
        $this->assertTrue($inspector->isSafeDelete($product->object()));
    }

    public function testDeleteOneToManyWithNoCascadeRemove(): void
    {
        [$category1, $category2] = CategoryFactory::createMany(2);
        $product = ProductFactory::createOne([
            'category' => $category1,
        ]);

        ProductFactory::assert()->count(1);
        $this->assertSame($category1->getId(), $product->getCategory()->getId());

        $inspector = $this->getInspector();

        // Cannot delete category with relation
        $this->assertFalse($inspector->isSafeDelete($category1->object()));
        // But can delete category without relation
        $this->assertTrue($inspector->isSafeDelete($category2->object()));
    }

    public function testDeleteOneToOneOwningSide(): void
    {
        $product = ProductFactory::createOne([
            'owner' => OwnerFactory::createOne(),
        ]);

        ProductFactory::assert()->count(1);
        $this->assertNotNull($product->getOwner());

        $inspector = $this->getInspector();

        $this->assertTrue($inspector->isSafeDelete($product->object()));
    }

    public function testDeleteOneToOneInverseSide(): void
    {
        [$owner1, $owner2] = OwnerFactory::createMany(2);
        $product = ProductFactory::createOne([
            'owner' => $owner1,
        ]);

        ProductFactory::assert()->count(1);
        $this->assertSame($owner1->getId(), $product->getOwner()->getId());

        $inspector = $this->getInspector();

        $this->assertFalse($inspector->isSafeDelete($owner1->object()));
        $this->assertTrue($inspector->isSafeDelete($owner2->object()));
    }

    public function testDeleteManyToManyOwningSide(): void
    {
        $product = ProductFactory::createOne([
            'tags' => TagFactory::new()->many(3),
        ]);

        ProductFactory::assert()->count(1);
        $this->assertCount(3, $product->getTags());

        $inspector = $this->getInspector();

        $this->assertTrue($inspector->isSafeDelete($product->object()));
    }

    public function testDeleteManyToManyInverseSide(): void
    {
        [$tag1, $tag2, $tag3] = TagFactory::createMany(3);

        $product = ProductFactory::createOne([
            'tags' => [$tag1, $tag2],
        ]);

        ProductFactory::assert()->count(1);
        $this->assertCount(2, $product->getTags());

        $inspector = $this->getInspector();

        $this->assertFalse($inspector->isSafeDelete($tag1->object()));
        $this->assertFalse($inspector->isSafeDelete($tag2->object()));
        $this->assertTrue($inspector->isSafeDelete($tag3->object()));
    }

    private function getInspector(): EntityInspector
    {
        return self::getContainer()->get('corerely_entity_association.inspector');
    }
}
