<?php

namespace LogBundle\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use LogBundle\Entity\Log;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\GenericEvent;


class EntitySubscriber implements EventSubscriber, ContainerAwareInterface
{

    use ContainerAwareTrait;

    private static $preservedId;

    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'postRemove',
        ];
    }

    public function getContainer()
    {
        return $this->container;
    }


    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        self::$preservedId = $entity->getId();
        if ($entity instanceof Log) {
            $this->logPreRemove($entity);
        }
    }

    private function logPreRemove(Log $entity)
    {
        $count = 0;
        $service = $this->container->get('log.service');
        foreach($service->getRelatedServices() as $service) {
            $count += $service->getLocale()->getLogRestrictions($entity->getId());
        }
        if($count) {
            throw new \Exception('لم يتم حذف ' . $entity . ' لوجود بيانات مرتبطة به في أماكن أخرى');
        }
        return true;
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Log) {
            $this->logPostRemove($entity);
        }
    }

    private function logPostRemove(Log $log)
    {
        $id = self::$preservedId;
        $eventDispatcher = $this->container->get('event_dispatcher');
        $event = new GenericEvent();
        $event->setArgument('id', $id);
        $eventDispatcher->dispatch('log.remove_log', $event);
    }

}