<?php

namespace LogBundle\Controller;

use LogBundle\Entity\log;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/log")
 */
class LogController extends Controller
{
    /**
     * @Route("/", name="log_log_list")
     * @Security("has_role('ROLE_LOG_LOG_LIST')")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('LogBundle:Log');
        $dataGrid = $repository->getDataGrid();

        $form = $dataGrid->getFilterForm(
            $this->container->get('form.factory'),
            $this->generateUrl('log_log_list')
        );

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            return $this->redirectToRoute('log_log_list', [
                'filter' => $dataGrid->getEncodedFilterArray($form)
            ]);
        }

        $filter = $request->query->get('filter');
        if ($filter) {
            $formData = $dataGrid->decodeFilterArray($filter);
            $form = $dataGrid->setFormFilterData($form, $formData);
        }

        $entities = $dataGrid->getGrid(
            $this->get('knp_paginator'),
            $request->query->getInt('page', 1)
        );

        return $this->render('LogBundle:Log:index.html.twig', [
            'entities'   => $entities,
            'formFilter' => $form->createView(),
        ]);
    }


    /**
     * @ParamConverter("entity", class="LogBundle:Log")
     * @Route("/show/{id}", name="log_log_show")
     * @Security("has_role('ROLE_LOG_LOG_SHOW')")
     */
    public function showAction(Log $entity)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('LogBundle:Log:show.html.twig', [
            'entity' => $entity,
        ]);
    }


    /**
     * @Route("/delete", name="log_log_delete")
     * @Security("has_role('ROLE_LOG_LOG_DELETE')")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $single = $request->query->get('single', false);
        $id = $request->query->get('id');
        $encodedRedirect = $request->query->get('redirect');
        $redirect = base64_decode($encodedRedirect);
        if ($single) {
            return $this->redirectToRoute('log_log_delete', [
                'id'       => base64_encode(serialize([$id])),
                'redirect' => $encodedRedirect
            ]);
        } else {
            $repository = $entity = $em->getRepository('LogBundle:Log');
            $bundleService = $this->get('log.service');
            $ids = unserialize(base64_decode($id));
            if (!is_array($ids) || empty($ids)) {
                throw $this->createNotFoundException($this->get('translator')->trans('admin.titles.error_happened'));
            }
            if ('POST' == $request->getMethod()) {
                foreach ($ids as $id) {
                    try {
                        $language = $repository->find($id);
                        if ($language) {
                            $em->remove($language);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add(
                                'success', $this->get('translator')->trans('admin.messages.the_entry_has_been_deleted') . ' ' . $language
                            );
                        }
                    } catch (\Exception $e) {
                        $this->get('session')->getFlashBag()->add(
                            'danger', $e->getMessage()
                        );
                    }
                }
                return $this->redirectToRoute('log_log_list');
            } else {
                $report = $repository->getDeleteRestrectionsByIds(
                    $bundleService,
                    $ids
                );
                return $this->render('LogBundle:Log:delete.html.twig', [
                    'report'   => $report,
                    'redirect' => $redirect,
                ]);
            }
        }
    }


    /**
     * @Route("/batch", name="log_log_batch")
     * @Security("has_role('ROLE_LOG_LOG_DELETE')")
     */
    public function batchAction(Request $request)
    {
        $ids = $request->query->get('ids');
        $idx = explode(',', $ids);
        $action = $request->query->get('action');
        if ('delete' == $action) {
            return $this->redirectToRoute('log_log_delete', [
                'id' => base64_encode(serialize($idx))
            ]);
        }
    }

}
