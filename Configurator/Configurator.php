<?php
/*
 * This file is part of the Sensi Yaml GUI Bundle.
 *
 * (c) Michael Ofner <michael@m3byte.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensi\Bundle\YamlGuiBundle\Configurator;
use Symfony\Component\Yaml\Yaml;

/**
 * YAMLGUI Configurator
 *
 * @author Michael Ofner <michael@m3byte.com>
 */
class Configurator
{
    /**
     * Absolute path tho yaml config file to read from. After calling read() the array  
     * get's set to parameters property.
     * @var string $filename
     */
    protected $filename;
    
    /**
     * Contains array of the given yaml config file after reading it.
     * @var array $parameters
     */
    protected $parameters;
    
    /**
     * @var string $configDir
     */
    protected $configDir;
	
	/**
	 * @param string $configDir
	 */
    public function __construct($configDir)
    {
        $this->configDir = $configDir;
    }
    
    /**
     * Set absolute file path of the yaml config file
     *
     * @param strin $name
     */
    public function setFilename($name)
    {
        if (file_exists($name)) {
            $this->filename = $name;
        } else {
            $this->filename = str_replace('//', '/', $this->configDir . '/' . $name);
        }
    }
    
    /**
     * @return string
     */
    public function getFilename()
    {
    	return $this->filename;
    }
	
	/**
	 * Checks if the file is writeable throw the application
	 * @void
	 */
    public function isFileWritable()
    {
        return is_writable($this->filename);
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Prepares the parameters to write into file. Merges form parameters and given 
     * yaml parameters together. 
     * 
     * @param array $parameters
     */
    public function mergeParameters($parameters)
    {
    	foreach($parameters as $key => $value) {
    		if (strpos($key, '--')) {
    			$parentKey = explode('--', $key);
    			if (isset($this->parameters[$parentKey[0]][$parentKey[1]])) {
    				$this->parameters[$parentKey[0]][$parentKey[1]] = $value;
    				unset($parameters[$key]);
    			}
    		}
    	}
    	
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    /**
     * Renders parameters as a string.
     *
     * @return string
     */
    public function render()
    {
        return Yaml::dump($this->parameters);
    }

    /**
     * Writes parameters to given filename or temporary in the cache directory.
     *
     * @return boolean
     */
    public function write()
    {
        if (!$this->isFileWritable()) {
        	throw new \Exception('The config file: '. $this->filename . ' is not writeable!');
		}
		
        return file_put_contents($this->filename, $this->render());
    }

    /**
     * Reads parameters from given yaml file.
     *
     * @throw \InvalidArgumentException if file doesn't exists, is not writeable or is invalid.
     * @return array
     */
    public function read()
    {
        $filename = $this->filename;
        
        if (!file_exists($filename)) {
        	throw new \InvalidArgumentException(sprintf('The %s file doesn\'t exists. Create the config file first to continue.', $filename));
        }
        
        if (!$this->isFileWritable()) {
			throw new \InvalidArgumentException(sprintf('The %s file is not writeable.', $filename));
        }

        $ret = Yaml::parse($filename);
        if (false === $ret || array() === $ret) {
            throw new \InvalidArgumentException(sprintf('The %s file is not valid.', $filename));
        }
		return $this->parameters = $ret;
    }

}
