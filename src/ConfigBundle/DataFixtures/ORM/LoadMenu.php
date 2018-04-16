<?php

namespace ConfigBundle\DataFixtures\ORM;

use AdminBundle\Entity\Section;
use AdminBundle\Entity\SectionItem;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        $section->setIdentifier('config')
            ->setTitle('config.menu.config')
            ->setImage('bundles/config/images/dashboard/small/config.png')
            ->setDescription('config.hints.config')
            ->setDescriptionSort(5);

        $item = new SectionItem();
        $item->setTitle('config.menu.admin_config')
            ->setRoute('config_config_variable_admin')
            ->setImage('bundles/config/images/dashboard/admin_config.png')
            ->addNewRoleByRoleName('ROLE_CONFIG_CONFIG_VARIABLE_ADMIN')
            ->setDescription('config.hints.admin_config')
            ->setDescriptionSort(6);
        $section->addItem($item);

        $item = new SectionItem();
        $item->setTitle('config.menu.user_config')
            ->setRoute('config_config_variable_user')
            ->setImage('bundles/config/images/dashboard/user_config.png')
            ->addNewRoleByRoleName('ROLE_ADMIN')
            ->setDescription('config.hints.user_config')
            ->setDescriptionSort(7);
        $section->addItem($item);

        $manager->persist($section);
        $manager->flush();

    }
}