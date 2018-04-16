<?php

namespace ConfigBundle\Entity\Repository\ConfigVariable;

use ConfigBundle\Entity\ConfigVariable;
use Doctrine\ORM\EntityRepository;

class Repository extends EntityRepository
{
    public function getGlobalSectionsAndVariables()
    {
        $configVariable = new ConfigVariable();
        $em = $this->getEntityManager();
        $sectionsDql = "SELECT DISTINCT c.sectionTranslation
        FROM ConfigBundle:ConfigVariable c
        WHERE c.scope = :scope_global OR c.scope = :scope_overridable
        ORDER BY c.sortOrder ASC
        ";

        $sectionParameters['scope_global'] = $configVariable::SCOPE_GLOBAL;
        $sectionParameters['scope_overridable'] = $configVariable::SCOPE_OVERRIDABLE;
        $sectionsQuery = $em->createQuery($sectionsDql);

        if (sizeof($sectionParameters)) {
            $sectionsQuery->setParameters($sectionParameters);
        }
        $sections = $sectionsQuery->getResult();

        $variables = [];
        foreach ($sections as $section) {
            $variablesDql = "SELECT c
            FROM ConfigBundle:ConfigVariable c
            WHERE (c.scope = :scope_global OR c.scope = :scope_overridable) AND c.sectionTranslation = :sectionTranslation
            ORDER BY c.sortOrder ASC
            ";
            $variablesParameters['scope_global'] = $configVariable::SCOPE_GLOBAL;
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


    public function getGlobalVariableValue($variable, $default)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT c.value
        FROM ConfigBundle:ConfigVariable c
        WHERE c.variable = :variable
        ";

        $parameters['variable'] = $variable;
        $query = $em->createQuery($dql);

        if (sizeof($parameters)) {
            $query->setParameters($parameters);
        }

        if (null != $query->getOneOrNullResult()) {
            return $query->getSingleScalarResult();
        } else {
            return $default;
        }
    }

}