<?php

namespace LogBundle\Service\LogService;

use AdminBundle\Entity\User;
use CommonBundle\Classes\PublicService;
use LogBundle\Entity\Log;
use LogBundle\Entity\LogService as LogServiceEntity;

class LogService extends PublicService
{

    public function getName()
    {
        return "السجلات";
    }

    public function log($serviceName, $message, $parameters = null, $user = null, $book = null)
    {
        $em = $this->container->get('doctrine')->getManager();
        $logRepository = $em->getRepository("LogBundle:LogService");
        if (!$logService = $logRepository->findOneBy(['name' => $serviceName])) {
            $logService = new LogServiceEntity();
            $logService->setName($serviceName);
            $em->persist($logService);
        }

        $adminService = $this->container->get('admin.service');
        /** @var User $userEntity */
        $username = null;
        $userEntity = $adminService->getUserById($user);
        if ($userEntity) {
            $username = $userEntity['username'];
        }

        $newLog = new Log();
        $newLog->setLogService($logService)
            ->setMessage($message)
            ->setParameter(serialize($parameters))
            ->setUser($user)
            ->setUsername($username)
            ->setBook($book);
        $em->persist($newLog);
        $em->flush();

        $sendEmail = (bool)$this->container->get('config.service')->getGlobalConfigValue('sendLog', false);
        if ($sendEmail == true) {
            $this->container->get('taskmanager.service')->addTaskRunImmediately(
                'log:send-log-email',
                ['id' => $newLog->getId()],
                'Send log email'
            );
        }

    }
}