<?php

namespace LogBundle\DataFixtures\ORM;

use ConfigBundle\Entity\ConfigVariable;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadConfigData implements FixtureInterface, ContainerAwareInterface
{
    public $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $sendLogVariable = new ConfigVariable();
        $sendLogVariable->setVariable('sendLog')
            ->setType(ConfigVariable::TYPE_BOOLEAN)
            ->setValue(0)
            ->setSectionTranslation('admin.config.general_section')
            ->setVariableTranslation('log.config.send_log')
            ->setScope(ConfigVariable::SCOPE_GLOBAL);
        $manager->persist($sendLogVariable);

        $manager->flush();
    }
}