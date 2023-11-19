<?php

namespace App\EventSubscriber;

class AdminAssetsSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            \Pimcore\Event\BundleManagerEvents::CSS_PATHS => 'onCssPaths',
        ];
    }

    public function onCssPaths(\Pimcore\Event\BundleManager\PathsEvent $event)
    {
        $event->setPaths(array_merge($event->getPaths(), ['/css/env.css']));
    }
}
