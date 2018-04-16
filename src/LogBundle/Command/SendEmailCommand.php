<?php

namespace LogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('log:send-log-email')
            ->setDescription('Send log email')
            ->addArgument('id', InputArgument::REQUIRED, 'Log ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        if ($id) {
            $em = $this->getContainer()->get('doctrine')->getManager();
            $repository = $em->getRepository('LogBundle:Log');
            $log = $repository->find($id);
            if ($log) {
                $adminEmail = $this->getContainer()->get('config.service')->getGlobalConfigValue('adminEmail', null);

                $message = \Swift_Message::newInstance()
                    ->setSubject('Log - ' . $log->getLogService()->getName())
                    ->setFrom($adminEmail)
                    ->setTo($adminEmail)
                    ->setBody(
                        $this->getContainer()->get('templating')->render(
                            'LogBundle:Log:sendEmail.html.twig',
                            ['entity' => $log]
                        )
                    );

            }
        }
    }

}