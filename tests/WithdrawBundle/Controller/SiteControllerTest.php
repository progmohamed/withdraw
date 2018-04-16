<?php

namespace WithdrawBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SiteControllerTest extends WebTestCase
{


    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/en/admin/withdraw/site/show/2', array(), array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        $this->assertTrue($client->getResponse()->isSuccessful());

    }
}
