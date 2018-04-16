<?php

namespace WithdrawBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SiteControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/en/admin/login');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
