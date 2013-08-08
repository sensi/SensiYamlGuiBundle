<?php
namespace Sensi\Bundle\YamlGuiBundle\Block;

use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;

class YamlGuiDashboardBlockService extends BaseBlockService
{

	protected $managed_files;

    public function getName()
    {
        return 'Sensi Yaml GUI Dashboard Block';
    }

    public function getDefaultSettings()
    {
        return array('managed_files' => $this->managed_files);
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
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
}