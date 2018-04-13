<?php

namespace WithdrawBundle\Entity\Repository\Site;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AdminBundle\Classes\DataGrid as AdminDataGrid;
use WithdrawBundle\Entity\Site;

class DataGrid extends AdminDataGrid
{
    public function getGrid($paginator, $page)
    {
        $em = $this->getEntityManager();
        $parameters = [];
        $dql = "SELECT s
        FROM WithdrawBundle:Site s
        LEFT JOIN s.metrics sm
        WHERE 1=1 ";

        $elementValue = $this->getFormDataElement('id');
        if ($elementValue) {
            $dql .= "AND s.id IN(:ids) ";
            $parameters['ids'] = $this->commaDelimitedToArray($elementValue);
        }

        $elementValue = $this->getFormDataElement('url');
        if ($elementValue) {
            $dql .= "AND s.url LIKE :url ";
            $parameters['url'] = "%" . $elementValue . "%";
        }

        $elementValue = $this->getFormDataElement('status');
        if ($elementValue) {
            $dql .= "AND s.status IN(:status)";
            $parameters['status'] = $elementValue;
        }

        $dql .= "ORDER BY s.id ";

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
            ->add('url', TextType::class, [
                'label' => 'withdraw.site.url'
            ])
            ->add('status', ChoiceType::class, array(
                'label' => 'withdraw.site.status.status',
                'choices' => array(
                    'withdraw.site.status.new' => Site::STATUS_NEW,
                    'withdraw.site.status.crawling' => Site::STATUS_CRAWLING,
                    'withdraw.site.status.done' => Site::STATUS_DONE,
                ),
                'multiple' => true,
            ));
        return $form->getForm();
    }
}