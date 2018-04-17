<?php

namespace WithdrawBundle\DataFixtures\ORM;

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
        // Crawling Default config
        $sendLogVariable = new ConfigVariable();
        $sendLogVariable->setVariable('subDomainAsInternal')
            ->setType(ConfigVariable::TYPE_BOOLEAN)
            ->setValue(1)
            ->setSectionTranslation('withdraw.config.withdraw_section')
            ->setVariableTranslation('withdraw.config.sub_domain_as_internal')
            ->setScope(ConfigVariable::SCOPE_GLOBAL);
        $manager->persist($sendLogVariable);

        $sendLogVariable = new ConfigVariable();
        $sendLogVariable->setVariable('userAgent')
            ->setType(ConfigVariable::TYPE_TEXT)
            ->setValue('Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36')
            ->setSectionTranslation('withdraw.config.withdraw_section')
            ->setVariableTranslation('withdraw.config.user_agent')
            ->setScope(ConfigVariable::SCOPE_GLOBAL);
        $manager->persist($sendLogVariable);

        $manager->flush();
    }
}