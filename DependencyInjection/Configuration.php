<?php
/*
 * This file is part of the Sensi Yaml GUI Bundle.
 *
 * (c) Michael Ofner <michael@m3byte.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sensi\Bundle\YamlGuiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
				->scalarNode('config_root_dir')->cannotBeEmpty()->defaultValue('yamlgui/')->end()
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

        return $treeBuilder;
    }
}
