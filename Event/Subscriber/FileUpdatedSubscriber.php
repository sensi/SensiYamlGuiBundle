<?php

namespace Sensi\Bundle\YamlGuiBundle\Event\Subscriber;

use Sensi\Bundle\YamlGuiBundle\Event\YamlFileUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class FileUpdatedSubscriber implements EventSubscriberInterface
{
    /** @var Session */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'sensi.file_updated' => ['onUpdateFile', 20]
        ];
    }

    /**
     * @param YamlFileUpdatedEvent $e
     */
    public function onUpdateFile(YamlFileUpdatedEvent $e)
    {
        $this->session->getFlashBag()->add('sonata_flash_success', 'sensi.yamlgui.flash.saved');
    }

}