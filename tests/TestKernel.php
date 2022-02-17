<?php
declare(strict_types=1);

namespace Corerely\EntityAssociationInspectorBundle\Tests;

use Corerely\EntityAssociationInspectorBundle\CorerelyEntityAssociationInspectorBundle;
use Corerely\EntityAssociationInspectorBundle\Tests\DependencyInjection\PublicServicesPass;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Zenstruck\Foundry\ZenstruckFoundryBundle;

class TestKernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new ZenstruckFoundryBundle(),
            new CorerelyEntityAssociationInspectorBundle(),
        ];
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/../var/cache' . spl_object_hash($this);
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->setParameter('kernel.project_dir', __DIR__);
        $container->addCompilerPass(new PublicServicesPass());

        $loader->load(__DIR__ . '/config/config.yaml');
    }
}
