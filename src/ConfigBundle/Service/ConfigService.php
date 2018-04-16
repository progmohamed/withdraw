<?php

namespace ConfigBundle\Service;

use ConfigBundle\Entity\ConfigVariable;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ConfigService implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    private $config = [];

    public function getGlobalConfigValue($variable, $default = null)
    {
        if (!isset($this->config['global'][$variable])) {
            $em = $this->container->get('doctrine')->getManager();
            $entity = $em->getRepository('ConfigBundle:ConfigVariable')->getGlobalVariableValue($variable, $default);
            $this->config['global'][$variable] = $entity;
        }
        return $this->config['global'][$variable];
    }

    public function getUserConfigValue($user, $variable, $default = null)
    {
        if (!isset($this->config['user'][$user][$variable])) {
            $em = $this->container->get('doctrine')->getManager();
            $entity = $em->getRepository('ConfigBundle:ConfigUserVariable')->getUserVariableValue($user, $variable, $default);
            $this->config['user'][$user][$variable] = $entity;
        }
        return $this->config['user'][$user][$variable];
    }

    public function addVariable($variable, $type, $sectionTranslation, $variableTranslation, $value, $scope, $data = null, $sortOrder = null)
    {
        $em = $this->container->get('doctrine')->getManager();
        $newVariable = new ConfigVariable();
        $newVariable->setVariable($variable)
            ->setType($type)
            ->setSectionTranslation($sectionTranslation)
            ->setVariableTranslation($variableTranslation)
            ->setValue($value)
            ->setScope($scope)
            ->setData($data)
            ->setSortOrder($sortOrder);
        $em->persist($newVariable);
        $em->flush();
    }
}