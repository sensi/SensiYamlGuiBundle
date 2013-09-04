<?php
/*
 * This file is part of the Sensi Yaml GUI Bundle.
 *
 * (c) Michael Ofner <michael@m3byte.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sensi\Bundle\YamlGuiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sensi\Bundle\YamlGuiBundle\Configurator\Form\YamlConfigType;

class DefaultController extends Controller
{
	
	/**
     * @param string   $view
     * @param array    $parameters
     * @param Response $response
     *
     * @return Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $parameters['base_template'] = isset($parameters['base_template']) ? $parameters['base_template'] : $this->container->getParameter('sensi.yamlgui.base_template');
        $parameters['admin_pool']    = $this->get('sonata.admin.pool');
        return parent::render($view, $parameters);
    }
	
    public function listAction()
    {
        return $this->render('SensiYamlGuiBundle:Default:list.html.twig', array('managed_files' => $this->container->getParameter('sensi.yamlgui.managed_files')));
    }
    
    public function editAction($config_file)
    {
		if (!key_exists($config_file, $this->container->getParameter('sensi.yamlgui.managed_files')))
		{
			throw new \InvalidArgumentException('The file ' . $config_file . ' is not managed by sensi yaml gui.');
		}
    	
    	$configurator = $this->container->get('sensi_yaml_gui.configurator');
		$configurator->setFilename($config_file);
		
        $form = $this->container->get('form.factory')->create(new YamlConfigType($configurator));

        $request = $this->container->get('request');
        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $configurator->mergeParameters($form->getData());
                $configurator->write();
                $this->addFlashMessage('sonata_flash_success', 'sensi.yamlgui.flash.saved');
            }
        }
    	
    	if($this->container->getParameter('sensi.yamlgui.sonata_admin_modus')) {
			return $this->render('SensiYamlGuiBundle:Sonata:gui.html.twig', array(
				'form'    => $form->createView(),
				'config_file' => $config_file,
			));
		}
		
        return $this->render('SensiYamlGuiBundle:Default:gui.html.twig', array(
        	'form' => $form->createView(),
        	'config_file' => $config_file,
        ));
    }
    
    protected function addFlashMessage($type, $message) {
    	$this->container->get('session')->getFlashBag()->add($type, $message);
    }
}
