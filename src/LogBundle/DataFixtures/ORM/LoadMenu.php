<?php

namespace LogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AdminBundle\Entity\Section;
use AdminBundle\Entity\SectionItem;

class LoadMenu implements FixtureInterface, ContainerAwareInterface
{
    public $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $section = new Section();
        $section->setIdentifier('log')
                ->setTitle('log.menu.log')
                ->setImage('bundles/log/images/dashboard/small/log.png')
                ->setDescription('log.hints.log')
                ->setDescriptionSort(3);

        $item = new SectionItem();
        $item   ->setTitle('log.menu.log_record')
            ->setRoute('log_log_list')
            ->setImage('bundles/log/images/dashboard/log_record.png')
            ->addNewRoleByRoleName('ROLE_LOG_LOG_LIST')
            ->setDescription('log.hints.log_record')
            ->setDescriptionSort(4);
        $section->addItem($item);



        $manager->persist($section);
        $manager->flush();

    }
}