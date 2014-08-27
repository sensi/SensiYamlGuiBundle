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

use Sensi\Bundle\YamlGuiBundle\Event\YamlFileUpdatedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
    
    public function editAction($configFile)
    {
        if (!array_key_exists($configFile, $this->container->getParameter('sensi.yamlgui.managed_files'))) {
            throw new \InvalidArgumentException('The file ' . $configFile . ' is not managed by sensi yaml gui.');
        }
        
        $configurator = $this->container->get('sensi_yaml_gui.configurator');
        $configurator->setFilename($configFile);
        
        $form = $this->container->get('form.factory')->create(new YamlConfigType($configurator));

        $request = $this->container->get('request');
        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $configurator->mergeParameters($form->getData());
                $configurator->write();
                $this->getEventDispatcher()->dispatch('sensi.file_updated', new YamlFileUpdatedEvent($configFile));
            }
        }
        
        if($this->container->getParameter('sensi.yamlgui.sonata_admin_modus')) {
            return $this->render('SensiYamlGuiBundle:Sonata:gui.html.twig', array(
                'form'    => $form->createView(),
                'configFile' => $configFile,
            ));
        }
        
        return $this->render('SensiYamlGuiBundle:Default:gui.html.twig', array(
            'form' => $form->createView(),
            'configFile' => $configFile,
        ));
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }
}
