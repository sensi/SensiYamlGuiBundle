<?php

namespace Sensi\Bundle\YamlGuiBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class YamlFileUpdatedEvent extends Event
{
    /** @var string */
    private $fileName;

    /**
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
}