<?php
declare(strict_types = 1);

namespace Corerely\EntityAssociationInspectorBundle\Tests;

use Corerely\EntityAssociationInspectorBundle\CorerelyEntityAssociationInspectorBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class TestKernel extends BaseKernel
{
    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new CorerelyEntityAssociationInspectorBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.xml');
    }
}
