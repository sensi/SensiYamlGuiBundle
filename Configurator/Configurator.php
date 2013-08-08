<?php
namespace Sensi\Bundle\YamlGuiBundle\Configurator;
use Symfony\Component\Yaml\Yaml;

/**
 * YAMLGUI Configurator
 *
 * @author Michael Ofner <michael@m3byte.com>
 */
class Configurator
{
    protected $filename;
    protected $parameters;
    protected $kernelDir;

    public function __construct($kernelDir)
    {
        $this->kernelDir = $kernelDir . "/config/yamlgui/";
    }

    public function isFileWritable()
    {
        return is_writable($this->filename);
    }

    public function clean()
    {
        if (file_exists($this->getCacheFilename())) {
            @unlink($this->getCacheFilename());
        }
    }
    
    public function setFilename($name)
    {
    	if (!file_exists($name)) {
    		$this->filename = $this->kernelDir . $name;
    	} else {
    		$this->filename = $name;
    	}
    }
    
    public function getFilename()
    {
    	return $this->filename;
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
    		if (strpos($key, '_')) {
    			$parentKey = explode('_', $key);
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
        $filename = $this->isFileWritable() ? $this->filename : $this->getCacheFilename();

        return file_put_contents($filename, $this->render());
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
        
        if (!file_exists($this->filename)) {
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
