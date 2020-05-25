<?php

declare(strict_types=1);

namespace Ckrack\OptimusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): NodeParentInterface
    {
        $treeBuilder = new TreeBuilder('ckrack_optimus');
        /** @var ArrayNodeDefinition */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
        ->children()
        ->integerNode('prime')
            /*
             * use the lowest possible prime, because optimus must instantiate with valid numbers.
             * they will be replaced by configuration provided by the recipe.
             */
            ->defaultValue(3)
                ->info('Large prime number lower than 2147483647')
            ->end()
                ->integerNode('inverse')
                ->defaultValue(715827883)
                ->info('The inverse prime so that (PRIME * INVERSE) & MAXID == 1')
            ->end()
                ->integerNode('random')
                    ->defaultValue(1592469642)
                    ->info('A large random integer lower than 2147483647')
                ->end()
                ->booleanNode('passthrough')
                    ->info('If true, will continue with other param converters.')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
