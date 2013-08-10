<?php
/*
 * This file is part of the Sensi Yaml GUI Bundle.
 *
 * (c) Michael Ofner <michael@m3byte.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sensi\Bundle\YamlGuiBundle\Configurator\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Sensi\Bundle\YamlGuiBundle\Configurator\Configurator;

class YamlConfigType extends AbstractType
{
	protected $configurator;

    public function __construct(Configurator $configurator)
    {
        $this->configurator = $configurator;
    }
	
	/**
	 * Here the form get's generated from the loaded yaml config array.
	 * Notice that at the moment only 2 levels were available.
	 *
	 * @param FormBuilderInterface $builder
	 * @param array				   $options
	 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $configs = $this->configurator->read();
        // Notice: Max. 2 levels were supported! All deeper levels will be ignored.
        /* @todo: 1. Implement better way to handle form types and 
         *   	  also the validation stuff and other form attributes.
         *		  2. Implement custom ignored mechanism
         */
        foreach ($configs as $key => $item) {
        	// Handle second level
        	if (is_array($item)) {
        		foreach ($item as $subKey => $subItem) {
        			// Ignore subItems with contains an array
        			if (is_array($subItem)) {
        				continue;
        			}
        			$options = array('label'=> strtoupper($key)."--".$subKey, 'data' => $subItem);
        			$builder->add($key."--".$subKey, $this->whatFormTypeFor($subItem), $options);
        		}
        	} else {
        		// Handle first level
        		$options = array('label'=> $key, 'data' => $item);
        		$builder->add(str_replace('.', '_', $key), $this->whatFormTypeFor($item), $options);
        	}
        }
        ;
    }

    public function getName()
    {
        return 'sensi_yamlgui';
    }
    
    /**
     * Try to get the form type from given value.
     *
     * @param string $value 
     * @return string Selected form type. Default is 'text' if nothing else found.
     */
    protected function whatFormTypeFor($value) {
    	$type = 'text';
    	
    	if (is_string($value)) {
    		$type = 'text';
    	} elseif (is_numeric($value)) {
    		$type = 'text';
    	} elseif (is_float($value) || is_double($value)) {
    		$type = 'float';
    	} elseif (is_bool($value)) {
    		$type = 'checkbox';
    	}
    	
    	return $type;
    }
}
