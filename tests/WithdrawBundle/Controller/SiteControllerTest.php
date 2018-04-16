<?php

namespace WithdrawBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SiteControllerTest extends WebTestCase
{
    private $em;
    private $client;

    public function setUp()
    {
        self::bootKernel();
        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $this->client = static::createClient();
        $this->client->setServerParameters(['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'admin']);
    }


    public function testIndex()
    {
        $this->client->request('GET', '/en/admin/withdraw/site/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }


    public function testNew()
    {
        $this->client->request('POST', '/en/admin/withdraw/site/new', array('url' => ['sss', 'mmm']));
        $this->assertTrue($this->client->getResponse()->isRedirect('/en/admin/withdraw/site/'));
    }


    public function testEdit()
    {

        $crawler = $this->client->request('GET', '/en/admin/withdraw/site/edit/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());


        $form = $crawler->selectButton('submit')->form(array(
            'site[url]' => 'testtesttest',
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/en/admin/withdraw/site/show/1'));
    }


    public function testShow()
    {
        $this->client->request('GET', '/en/admin/withdraw/site/show/1');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testDelete()
    {
        $this->client->request('POST', '/en/admin/withdraw/site/delete?id=' . base64_encode(serialize([2])));
        $this->assertTrue($this->client->getResponse()->isRedirect('/en/admin/withdraw/site/'));
    }


}
