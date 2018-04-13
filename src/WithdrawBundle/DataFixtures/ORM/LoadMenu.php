<?php

namespace WithdrawBundle\DataFixtures\ORM;

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
        $section->setIdentifier('withdraw')
                ->setTitle('withdraw.menu.withdraw')
                ->setImage('bundles/withdraw/images/dashboard/small/withdraw.png')
                ->setDescription('withdraw.hints.withdraw')
                ->setDescriptionSort(3);

        $item = new SectionItem();
        $item   ->setTitle('withdraw.menu.site')
            ->setRoute('withdraw_site_list')
            ->setImage('bundles/withdraw/images/dashboard/site.png')
            ->addNewRoleByRoleName('ROLE_WITHDRAW_SITE_LIST')
            ->setDescription('withdraw.hints.site')
            ->setDescriptionSort(4);
        $section->addItem($item);



        $manager->persist($section);
        $manager->flush();

    }
}