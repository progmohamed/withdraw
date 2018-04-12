<?php

namespace ConfigBundle\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
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