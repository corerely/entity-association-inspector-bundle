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

    public function testRemoveOneToManyWithEnabledCascadeRemove(): void
    {
        $product = ProductFactory::createOne([
            'comments' => CommentFactory::new()->many(3),
        ]);

        ProductFactory::assert()->count(1);
        CommentFactory::assert()->count(3);

        $this->assertCount(3, $product->getComments());

        $inspector = $this->getInspector();
        $this->assertTrue($inspector->isSafeToRemove($product->_real()));
    }

    public function testRemoveManyToOneWithInverseSide(): void
    {
        $product = ProductFactory::createOne([
            'comments' => CommentFactory::new()->many(1),
        ]);

        ProductFactory::assert()->count(1);
        $comment = $product->getComments()->first();

        $inspector = $this->getInspector();
        $this->assertTrue($inspector->isSafeToRemove($comment));
    }

    public function testRemoveManyToOneWithoutInverseSide(): void
    {
        $category = CategoryFactory::createOne();
        $product = ProductFactory::createOne([
            'category' => $category,
        ]);

        ProductFactory::assert()->count(1);
        $this->assertNotNull($product->getCategory());

        $inspector = $this->getInspector();
        $this->assertTrue($inspector->isSafeToRemove($product->_real()));
    }

    public function testRemoveOneToManyWithNoCascadeRemove(): void
    {
        [$category1, $category2] = CategoryFactory::createMany(2);
        $product = ProductFactory::createOne([
            'category' => $category1,
        ]);

        ProductFactory::assert()->count(1);
        $this->assertSame($category1->getId(), $product->getCategory()->getId());

        $inspector = $this->getInspector();

        // Cannot delete category with relation
        $this->assertFalse($inspector->isSafeToRemove($category1->_real()));
        // But can delete category without relation
        $this->assertTrue($inspector->isSafeToRemove($category2->_real()));
    }

    public function testRemoveOneToOneOwningSide(): void
    {
        $product = ProductFactory::createOne([
            'owner' => OwnerFactory::createOne(),
        ]);

        ProductFactory::assert()->count(1);
        $this->assertNotNull($product->getOwner());

        $inspector = $this->getInspector();

        $this->assertTrue($inspector->isSafeToRemove($product->_real()));
    }

    public function testRemoveOneToOneInverseSide(): void
    {
        [$owner1, $owner2] = OwnerFactory::createMany(2);
        $product = ProductFactory::createOne([
            'owner' => $owner1,
        ]);

        ProductFactory::assert()->count(1);
        $this->assertSame($owner1->getId(), $product->getOwner()->getId());

        $inspector = $this->getInspector();

        $this->assertFalse($inspector->isSafeToRemove($owner1->_real()));
        $this->assertTrue($inspector->isSafeToRemove($owner2->_real()));
    }

    public function testRemoveManyToManyOwningSide(): void
    {
        $product = ProductFactory::createOne([
            'tags' => TagFactory::new()->many(3),
        ]);

        ProductFactory::assert()->count(1);
        $this->assertCount(3, $product->getTags());

        $inspector = $this->getInspector();

        $this->assertTrue($inspector->isSafeToRemove($product->_real()));
    }

    public function testRemoveManyToManyInverseSide(): void
    {
        [$tag1, $tag2, $tag3] = TagFactory::createMany(3);

        $product = ProductFactory::createOne([
            'tags' => [$tag1, $tag2],
        ]);

        ProductFactory::assert()->count(1);
        $this->assertCount(2, $product->getTags());

        $inspector = $this->getInspector();

        $this->assertFalse($inspector->isSafeToRemove($tag1->_real()));
        $this->assertFalse($inspector->isSafeToRemove($tag2->_real()));
        $this->assertTrue($inspector->isSafeToRemove($tag3->_real()));
    }

    private function getInspector(): EntityInspector
    {
        return self::getContainer()->get('corerely_entity_association.inspector');
    }
}
