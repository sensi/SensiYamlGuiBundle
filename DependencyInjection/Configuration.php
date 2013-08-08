<?php

namespace Sensi\Bundle\YamlGuiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sensi_yaml_gui', 'array');
		$rootNode
			->children()
				->scalarNode('sonata_admin_modus')->defaultValue(false)->end()
				->scalarNode('config_root_dir')->defaultValue("%kernel.root_dir%/config/yamlgui/")->end()
				->arrayNode('managed_files')
                    ->isRequired()
            		->requiresAtLeastOneElement()
            		->useAttributeAsKey('name')
            		->prototype('array')
                	->children()
                    	->scalarNode('title')->isRequired()->end()
                	->end()
            	->end()
            ->end()
			
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
