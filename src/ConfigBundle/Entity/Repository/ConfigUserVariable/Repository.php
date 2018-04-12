<?php

namespace ConfigBundle\Entity\Repository\ConfigUserVariable;

use Doctrine\ORM\EntityRepository;
use ConfigBundle\Entity\ConfigVariable;

class Repository extends EntityRepository
{
    public function getUserSectionsAndVariables($userId)
    {
        $configVariable = new ConfigVariable();
        $em = $this->getEntityManager();
        $sectionsDql = "SELECT DISTINCT c.sectionTranslation
        FROM ConfigBundle:ConfigVariable c
        WHERE c.scope = :scope_user OR c.scope = :scope_overridable
        ORDER BY c.sortOrder ASC
        ";

        $sectionParameters['scope_user'] = $configVariable::SCOPE_USER;
        $sectionParameters['scope_overridable'] = $configVariable::SCOPE_OVERRIDABLE;
        $sectionsQuery = $em->createQuery($sectionsDql);

        if (sizeof($sectionParameters)) {
            $sectionsQuery->setParameters($sectionParameters);
        }
        $sections = $sectionsQuery->getResult();

        $variables = [];
        foreach ($sections as $section) {
            $variablesDql = "SELECT c, cu
            FROM ConfigBundle:ConfigVariable c
            LEFT JOIN c.configUserVariable cu WITH cu.user = :userId
            WHERE (c.scope = :scope_user OR c.scope = :scope_overridable) AND c.sectionTranslation = :sectionTranslation
            ORDER BY c.sortOrder ASC
            ";
            $variablesParameters['userId'] = $userId;
            $variablesParameters['scope_user'] = $configVariable::SCOPE_USER;
            $variablesParameters['scope_overridable'] = $configVariable::SCOPE_OVERRIDABLE;
            $variablesParameters['sectionTranslation'] = $section['sectionTranslation'];
            $variablesQuery = $em->createQuery($variablesDql);

            if (sizeof($variablesParameters)) {
                $variablesQuery->setParameters($variablesParameters);
            }
            $variables[$section['sectionTranslation']] = $variablesQuery->getResult();
        }

        return $variables;

    }

    public function getUserVariableValue($userId, $variable, $default)
    {
        $configVariable = new ConfigVariable();

        $em = $this->getEntityManager();
        $dql = "SELECT cu.value as user_value, c.value as global_vaue
        FROM ConfigBundle:ConfigVariable c
        LEFT JOIN c.configUserVariable cu WITH cu.user = :userId
        WHERE (c.scope = :scope_user OR c.scope = :scope_overridable) AND c.variable = :variable
        ";

        $parameters['userId'] = $userId;
        $parameters['scope_user'] = $configVariable::SCOPE_USER;
        $parameters['scope_overridable'] = $configVariable::SCOPE_OVERRIDABLE;
        $parameters['variable'] = $variable;
        $query = $em->createQuery($dql);

        if (sizeof($parameters)) {
            $query->setParameters($parameters);
        }

        if (null != $variableValue = $query->getOneOrNullResult()) {
            if(isset($variableValue['user_value'])){
                return $variableValue['user_value'];
            }else{
                return $variableValue['global_vaue'];
            }
        } else {
            return $default;
        }
    }


    public function removeUserVariables($userId)
    {
        $em = $this->getEntityManager();
        $dql = " DELETE FROM ConfigBundle:ConfigUserVariable cuv WHERE cuv.user = :user ";
        $query = $em->createQuery($dql);
        $query->setParameter('user', $userId);
        $query->execute();
    }
}