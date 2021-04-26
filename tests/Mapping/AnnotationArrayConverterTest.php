<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle\Tests\Mapping;

use Corerely\EntityAssociationInspectorBundle\Mapping\AnnotationArrayConverter;
use PHPUnit\Framework\TestCase;

class AnnotationArrayConverterTest extends TestCase
{

    public function testToArray()
    {
        $annotation = new Annotation();
        $annotation->title = 'Test';

        $converter = new AnnotationArrayConverter($annotation);

        $this->assertEquals([
            'title' => 'Test',
        ], $converter->toArray());
    }
}

class Annotation implements \Doctrine\ORM\Mapping\Annotation
{
    public string $title;

    private array $dummyArray;
    protected $dummyArray2;

    public function dummyMethod()
    {
    }
}
