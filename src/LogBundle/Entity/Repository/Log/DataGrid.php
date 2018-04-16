<?php

namespace LogBundle\Entity\Repository\Log;

use AdminBundle\Classes\DataGrid as AdminDataGrid;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class DataGrid extends AdminDataGrid
{
    public function getGrid($paginator, $page)
    {
        $em = $this->getEntityManager();
        $parameters = [];
        $dql = "SELECT l
        FROM LogBundle:Log l
        LEFT JOIN l.logService ls        
        WHERE 1=1 ";

        $elementValue = $this->getFormDataElement('id');
        if ($elementValue) {
            $dql .= "AND l.id IN(:ids) ";
            $parameters['ids'] = $this->commaDelimitedToArray($elementValue);
        }

        $elementValue = $this->getFormDataElement('dateFrom');
        if ($elementValue) {
            $dateTime = $this->parseDateTime($elementValue);
            if ($dateTime) {
                $dql .= "AND l.createdAt >= :dateFrom ";
                $parameters['dateFrom'] = $dateTime;
            }
        }

        $elementValue = $this->getFormDataElement('dateTo');
        if ($elementValue) {
            $dateTime = $this->parseDateTime($elementValue);
            if ($dateTime) {
                $dql .= "AND l.createdAt <= :dateTo ";
                $parameters['dateTo'] = $dateTime;
            }
        }

        $elementValue = $this->getFormDataElement('username');
        if ($elementValue) {
            $dql .= "AND l.username LIKE :username ";
            $parameters['username'] = "%" . $elementValue . "%";
        }

        $elementValue = $this->getFormDataElement('userId');
        if ($elementValue) {
            $dql .= "AND l.user LIKE :userId ";
            $parameters['userId'] = $elementValue;
        }

        $elementValue = $this->getFormDataElement('service');
        if ($elementValue) {
            $dql .= "AND ls.id IN(:service) ";
            $parameters['service'] = $elementValue;
        }

        $dql .= "ORDER BY l.createdAt ";

        $query = $em->createQuery($dql);
        if (sizeof($parameters)) {
            $query->setParameters($parameters);
        }

        $pagination = $paginator->paginate(
            $query,
            $page,
            10,
            ['wrap-queries' => true]
        );
        return $pagination;
    }

    public function getFilterForm($formFactory, $formActionUrl, $data = null, $options = [])
    {
        $form = $formFactory->createBuilder(FormType::class, $data, $options);
        $form
            ->setMethod('POST')
            ->setAction($formActionUrl)
            ->add('id', TextType::class, [
                'label' => 'admin.titles.id'
            ])
            ->add('dateFrom', TextType::class, [
                'label' => 'admin.titles.date_from',
                'attr'  => ['class' => 'date'],
            ])
            ->add('dateTo', TextType::class, [
                'label' => 'admin.titles.date_to',
                'attr'  => ['class' => 'date'],
            ])
            ->add('userId', TextType::class, [
                'label' => 'User id'
            ])
            ->add('username', TextType::class, [
                'label' => 'admin.titles.username'
            ])
            ->add('service', EntityType::class, [
                'label'    => 'log.titles.component',
                'class'    => 'LogBundle:LogService',
                'multiple' => true,
            ]);
        return $form->getForm();
    }
}