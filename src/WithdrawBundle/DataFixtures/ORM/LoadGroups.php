<?php

namespace WithdrawBundle\DataFixtures\ORM;

use AdminBundle\Entity\Group;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadGroups implements FixtureInterface, ContainerAwareInterface
{
    public $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $groups = [
            'Site List'   => 'ROLE_WITHDRAW_SITE_LIST',
            'Site New'    => 'ROLE_WITHDRAW_SITE_NEW',
            'Site Show'   => 'ROLE_WITHDRAW_SITE_SHOW',
            'Site Edit'   => 'ROLE_WITHDRAW_SITE_EDIT',
            'Site Delete' => 'ROLE_WITHDRAW_SITE_DELETE',
        ];

        foreach ($groups as $k => $group) {
            $group = new Group($k, [$group]);
            $manager->persist($group);
        }
        $manager->flush();

    }
}