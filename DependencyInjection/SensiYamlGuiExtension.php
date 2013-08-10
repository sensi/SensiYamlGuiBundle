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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class SensiYamlGuiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    	$bundles = $container->getParameter('kernel.bundles');
		
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
		
		
		if ($config['sonata_admin_modus'] && isset($bundles['SonataAdminBundle'])) {
			$sonata_templates = $container->getParameter('sonata.admin.configuration.templates');
			$container->setParameter('sensi.yamlgui.base_template', $sonata_templates['layout']);
			$container->setParameter('sensi.yamlgui.sonata_admin_modus', true);
		} else {
			$container->setParameter('sensi.yamlgui.base_template', 'SensiYamlGuiBundle:Default:layout.html.twig');
			$container->setParameter('sensi.yamlgui.sonata_admin_modus', false);
		}
		
		$container->setParameter('sensi.yamlgui.config_root_dir', $config['config_root_dir']);
		$container->setParameter('sensi.yamlgui.managed_files', $config['managed_files']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
