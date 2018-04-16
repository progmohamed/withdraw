<?php

namespace ConfigBundle\Subscriber;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ServiceEventSubscriber implements EventSubscriberInterface, ContainerAwareInterface
{

    use ContainerAwareTrait;

    public static function getSubscribedEvents()
    {
        return [
            'admin.remove_user' => 'onRemoveUser',
        ];
    }

    public function onRemoveLanguage(GenericEvent $event)
    {
        $userId = $event->getArgument('id');
        $em = $this->container->get('doctrine')->getManager();

        $repository = $em->getRepository('ConfigBundle:ConfigUserVariable');
        $repository->removeUserVariables($userId);
    }
}