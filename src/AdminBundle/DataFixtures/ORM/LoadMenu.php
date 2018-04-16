<?php

namespace AdminBundle\DataFixtures\ORM;

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
        $section->setIdentifier('admin_user_management')
            ->setTitle('admin.menu.user_management')
            ->setImage('bundles/admin/images/dashboard/small/users.png')
            ->setDescription('admin.hints.user_management')
            ->setDescriptionSort(1);

        $item = new SectionItem();
        $item->setTitle('admin.menu.users')
            ->setRoute('admin_user')
            ->setImage('bundles/admin/images/dashboard/users.png')
            ->addNewRoleByRoleName('ROLE_ADMIN_USER_INDEX')
            ->setDescription('admin.hints.users')
            ->setDescriptionSort(2);
        $section->addItem($item);

        $manager->persist($section);
        $manager->flush();

    }
}