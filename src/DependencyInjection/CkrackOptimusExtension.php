<?php

declare(strict_types=1);

namespace Ckrack\OptimusBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CkrackOptimusExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(\dirname(__DIR__, 2).'/Resources/config'));
        $loader->load('services.xml');

        foreach (['prime', 'inverse', 'random', 'passthrough'] as $parameter) {
            $container->setParameter('optimus.'.$parameter, $config[$parameter]);
        }
    }
}
