<?php
/*
 * This file is part of the Sensi Yaml GUI Bundle.
 *
 * (c) Michael Ofner <michael@m3byte.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sensi\Bundle\YamlGuiBundle\Block;

use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;

/**
 * This BlockService is required to use the bundle in SonataAdminBundle's Dashboard.
 * A list of all managed files throw the yaml gui will be displayed in the dashboard.
 *
 * @author Michael Ofner <michael@m3byte.com>
 */
class YamlGuiDashboardBlockService extends BaseBlockService
{

	protected $managed_files;

    public function getName()
    {
        return 'Sensi Yaml GUI Dashboard Block';
    }

    public function getDefaultSettings()
    {
        return array('managed_files' => $this->managed_files, 'translation_domain'=> 'sensi_yaml_gui');
    }
    
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = array_merge($this->getDefaultSettings(), $blockContext->getSettings());
		return $this->renderResponse('SensiYamlGuiBundle:Sonata:dashboard_block.html.twig', array(
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ), $response);
    }
    
    public function setManagedFiles(array $files) {
    	$this->managed_files = $files;
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }

}