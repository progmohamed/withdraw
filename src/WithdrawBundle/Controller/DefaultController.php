<?php

namespace WithdrawBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WithdrawBundle:Default:index.html.twig');
    }
}
