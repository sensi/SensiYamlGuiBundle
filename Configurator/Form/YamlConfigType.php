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
    /**
     * @var Sensi\Bundle\YamlGuiBundle\Configurator\Configurator $configurator
     */
    protected $configurator;

    /**
     * @param Configurator $configurator
     */
    public function __construct(Configurator $configurator)
    {
        $this->configurator = $configurator;
    }

    /**
     * Here the form get's generated from the loaded yaml config array.
     * Notice that at the moment only 2 levels were available.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $configs = $this->configurator->read();

        /* @todo: 1. Implement better way to handle form types and
         *        also the validation stuff and other form attributes.
         *        2. Implement custom ignored mechanism
         */
        $this->handleLevelsRecursive($builder, $configs);
    }

    protected function handleLevelsRecursive(FormBuilderInterface $builder, $item, $key = NULL)
    {
        $separator = Configurator::LEVEL_SEPARATOR;

        foreach ($item as $subKey => $subItem) {

            if(isset($key)){
                $label = str_replace('.', '_', $key . $separator . $subKey);
            } else {
                $label = str_replace('.', '_', $subKey);
            }

            if (is_array($subItem)) {

                $this->handleLevelsRecursive($builder, $subItem, $label);
            } else {
                $options = array(
                    'label'=> $label,
                    'data' => $subItem,
                    'translation_domain' => 'sensi_yaml_gui',
                    'required' => false
                );

                $builder->add($label, $this->whatFormTypeFor($subItem), $options);
            }
        }
    }

    /**
     * {@inhiredoc}
     */
    public function getName()
    {
        return 'sensi_yaml_gui';
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
