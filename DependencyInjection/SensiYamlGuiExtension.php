<?php

namespace Sensi\Bundle\YamlGuiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
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
		
		$container->setParameter('sensi.yamlgui.managed_files', $config['managed_files']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
		
		
    }
}
