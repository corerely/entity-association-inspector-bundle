<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Mapping;

interface AssociationMappingBuilderInterface
{
    /**
     * Generate array of associations configuration
     * where each key is an entity if any other entity relies on it
     *
     * For example if there is Product associated with Category as ManyToOne, array will look something like
     * [ 'App\Entity\Category' =>
     *      [
     *          // This array keep all entities that relies on category with all fields mapping configuration
     *          'App\Entity\Product' => [
     *              'category' => [
     *                  'owningSide' => [ManyToOne annotation configuration],
     *                  'inverseSide' => [OneToMany annotation configuration],
     *              ],
     *          ],
     *      ],
     * ]
     */
    public function getAssociationsMapping(): array;
}
