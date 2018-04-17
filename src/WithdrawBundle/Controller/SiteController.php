<?php

namespace WithdrawBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WithdrawBundle\Entity\Site;
use WithdrawBundle\Form\SiteType;

/**
 * @Route("/site")
 */
class SiteController extends Controller
{
    /**
     * @Route("/", name="withdraw_site_list")
     * @Security("has_role('ROLE_WITHDRAW_SITE_LIST')")
     */
    public function indexAction(Request $request)
    {
        // filtering form
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('WithdrawBundle:Site');
        $dataGrid = $repository->getDataGrid();
        $form = $dataGrid->getFilterForm(
            $this->container->get('form.factory'),
            $this->generateUrl('withdraw_site_list')
        );

        // filtering process
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            return $this->redirectToRoute('withdraw_site_list', [
                'filter' => $dataGrid->getEncodedFilterArray($form)
            ]);
        }

        // set data in filter form
        $filter = $request->query->get('filter');
        if ($filter) {
            $formData = $dataGrid->decodeFilterArray($filter);
            $form = $dataGrid->setFormFilterData($form, $formData);
        }

        $entities = $dataGrid->getGrid(
            $this->get('knp_paginator'),
            $request->query->getInt('page', 1)
        );

        return $this->render('WithdrawBundle:Site:index.html.twig', [
            'entities'   => $entities,
            'formFilter' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="withdraw_site_new", options = { "expose" = true })
     * @Security("has_role('ROLE_WITHDRAW_SITE_NEW')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if ('POST' == $request->getMethod()) {
            $urls = $request->request->get('url');
            $em = $this->getDoctrine()->getManager();
            $sites = $em->getRepository("WithdrawBundle:Site")
                ->add($urls, $this->getUser(),
                    $this->get('common.service'),
                    $this->get('withdraw.service')->getName(),
                    $this->get('taskmanager.service')
                );

            // if Ajax or no
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse($sites[0]);
            } else {
                $this->get('session')->getFlashBag()->add(
                    'success', $this->get('translator')->trans('admin.messages.the_entry_has_been_added')
                );
                return $this->redirectToRoute('withdraw_site_list');
            }
        }
        return $this->render('WithdrawBundle:Site:new.html.twig', [
        ]);
    }


    /**
     * @ParamConverter("entity", class="WithdrawBundle:Site")
     * @Route("/edit/{id}", name="withdraw_site_edit")
     * @Security("has_role('ROLE_WITHDRAW_SITE_EDIT')")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Site $entity)
    {

        $editForm = $this->createForm(SiteType::class, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getRepository("WithdrawBundle:Site")
                ->edit($entity, $this->getUser(),
                    $this->get('common.service'),
                    $this->get('withdraw.service')->getName(),
                    $this->get('taskmanager.service')
                );

            $this->get('session')->getFlashBag()->add(
                'success', $this->get('translator')->trans('admin.messages.the_entry_has_been_updated')
            );

            return $this->redirectToRoute('withdraw_site_show', ['id' => $entity->getId()]);
        }

        return $this->render('WithdrawBundle:Site:edit.html.twig', [
            'entity'    => $entity,
            'edit_form' => $editForm->createView(),
        ]);
    }


    /**
     * @ParamConverter("entity", class="WithdrawBundle:Site")
     * @Route("/show/{id}", name="withdraw_site_show")
     * @Security("has_role('ROLE_WITHDRAW_SITE_SHOW')")
     */
    public function showAction(Site $entity)
    {
        return $this->render('WithdrawBundle:Site:show.html.twig', [
            'entity' => $entity,
        ]);
    }


    /**
     * @Route("/delete", name="withdraw_site_delete")
     * @Security("has_role('ROLE_WITHDRAW_SITE_DELETE')")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $translator = $this->get('translator');
        $single = $request->query->get('single', false);
        $id = $request->query->get('id');
        $encodedRedirect = $request->query->get('redirect');
        $redirect = base64_decode($encodedRedirect);
        if ($single) {
            // put ID in array and serialize it and send it again to delete
            return $this->redirectToRoute('withdraw_site_delete', [
                'id'       => base64_encode(serialize([$id])),
                'redirect' => $encodedRedirect
            ]);
        } else {
            // if request from batch action
            $repository = $em->getRepository('WithdrawBundle:Site');
            $bundleService = $this->get('withdraw.service');
            $ids = unserialize(base64_decode($id));
            if (!is_array($ids) || empty($ids)) {
                throw $this->createNotFoundException($translator->trans('admin.titles.error_happened'));
            }
            // delete process
            if ('POST' == $request->getMethod()) {
                foreach ($ids as $id) {
                    try {
                        $site = $repository->find($id);
                        if ($site) {
                            $em->getRepository("WithdrawBundle:Site")
                                ->delete($site, $this->getUser(),
                                    $this->get('common.service'),
                                    $this->get('withdraw.service')->getName()
                                );
                            $this->get('session')->getFlashBag()->add(
                                'success', $translator->trans('withdraw.messages.the_entry_has_been_deleted', ['%entity%' => $site])
                            );
                        }
                    } catch (\Exception $e) {
                        $this->get('session')->getFlashBag()->add(
                            'danger', $e->getMessage()
                        );
                    }
                }
                return $this->redirectToRoute('withdraw_site_list');
            } else {
                $report = $repository->getDeleteRestrectionsByIds(
                    $bundleService,
                    $ids
                );
                return $this->render('WithdrawBundle:Site:delete.html.twig', [
                    'report'   => $report,
                    'redirect' => $redirect,
                ]);
            }
        }
    }


    /**
     * @Route("/batch", name="withdraw_site_batch")
     * @Security("has_role('ROLE_WITHDRAW_SITE_DELETE')")
     */
    public function batchAction(Request $request)
    {
        $ids = $request->query->get('ids');
        $idx = explode(',', $ids);
        $action = $request->query->get('action');
        if ('delete' == $action) {
            return $this->redirectToRoute('withdraw_site_delete', [
                'id' => base64_encode(serialize($idx))
            ]);
        }
    }


    /**
     * @Route("/get_changes", name="withdraw_site_get_changes", options = { "expose" = true })
     * @Security("has_role('ROLE_WITHDRAW_SITE_LIST')")
     */
    public function getChangesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $siteRepository = $em->getRepository('WithdrawBundle:Site');
        $changes = $siteRepository->getChanges($request->request->get('ids'));
        $results = [];
        foreach ($changes as $site) {
            $results[] = [
                'id'             => $site->getId(),
                'url'            => $site->getUrl(),
                'status'         => $this->get('translator')->trans($site->getStatusTransKey()),
                'title'          => (isset($site->getMetrics()['title'])) ? $site->getMetrics()['title']->getHumanValue() : "n/a",
                'ex_links_count' => (isset($site->getMetrics()['ex_links_count'])) ? $site->getMetrics()['ex_links_count']->getHumanValue() : "n/a",
                'ga_is_exist'    => (isset($site->getMetrics()['ga_is_exist'])) ? $site->getMetrics()['ga_is_exist']->getHumanValue() : "n/a",
            ];
        }
        return new JsonResponse($results);
    }

}
