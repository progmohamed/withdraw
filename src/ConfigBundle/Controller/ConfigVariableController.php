<?php

namespace ConfigBundle\Controller;

use ConfigBundle\Entity\ConfigUserVariable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/config_variable")
 */
class ConfigVariableController extends Controller
{
    /**
     * @Route("/", name="config_config_variable_admin")
     * @Security("has_role('ROLE_CONFIG_CONFIG_VARIABLE_ADMIN')")
     */
    public function adminAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ConfigBundle:ConfigVariable');
        $sectionsAndVariables = $repository->getGlobalSectionsAndVariables();
        if ('POST' == $request->getMethod()) {

            foreach ($sectionsAndVariables as $variables) {
                foreach ($variables as $entity) {
                    if (null !== $request->request->get($entity->getVariable())) {
                        $entity->setValue($request->request->get($entity->getVariable()));
                    } else {
                        $entity->setValue(0);
                    }
                }
            }
            $em->flush();
        }


        return $this->render('ConfigBundle:ConfigVariable:admin_config.html.twig', [
            'sectionsAndVariables' => $sectionsAndVariables,
        ]);
    }

    /**
     * @Route("/user", name="config_config_variable_user")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function userAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getId();
        $configUserVariableRepository = $em->getRepository('ConfigBundle:ConfigUserVariable');
        $sectionsAndVariables = $configUserVariableRepository->getUserSectionsAndVariables($userId);
        if ('POST' == $request->getMethod()) {

            foreach ($sectionsAndVariables as $variables) {
                foreach ($variables as $entity) {
                    $vaule = 0;
                    if (null !== $request->request->get($entity->getVariable())) {
                        $vaule = $request->request->get($entity->getVariable());
                    }

                    if ($userVariable = $configUserVariableRepository->findOneBy(['user' => $userId, 'configVariable' => $entity->getId()])) {
                        $userVariable->setValue($vaule);
                    } else {
                        $newUserVariable = new ConfigUserVariable();
                        $newUserVariable->setValue($vaule)
                            ->setUser($userId)
                            ->setConfigVariable($entity);
                        $em->persist($newUserVariable);
                    }
                }
            }
            $em->flush();
        }

        return $this->render('ConfigBundle:ConfigVariable:user_config.html.twig', [
            'sectionsAndVariables' => $sectionsAndVariables,
        ]);
    }

}
