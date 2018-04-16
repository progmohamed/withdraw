<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/")
 */
class MainController extends Controller
{

    /**
     * @Route("/", name="admin_main_home")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function homeAction()
    {
        return $this->render('AdminBundle:Main:home.html.twig');
    }
}
