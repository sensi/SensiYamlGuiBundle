<?php
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $configs = $this->configurator->read();
        // Notice: Max. 2 levels were supported!
        /* @todo: Implement better way to handle form types and 
         *   	  also the validation stuff and other form attributes.
         */
        foreach ($configs as $key => $item) {
        	// Handle second level
        	if (is_array($item)) {
        		foreach ($item as $subKey => $subItem) {
        			// @todo: implement required attr or other form options
        			$options = array('label'=> $key."_".$subKey, 'data' => $subItem);
        			$builder->add($key."_".$subKey, $this->whatFormTypeFor($subItem), $options);
        		}
        	} else {
        		// Handle first level
        		// @todo: implement required attr or other form options
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
