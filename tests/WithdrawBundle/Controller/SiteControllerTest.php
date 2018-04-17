<?php

namespace WithdrawBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SiteControllerTest extends KernelTestCase
{
    private $em;
    private $client;
    private $randUrl;
    private $router;

    public function setUp()
    {
        self::bootKernel();
        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $this->router = self::$kernel->getContainer()->get('router');

        $this->client = self::$kernel->getContainer()->get('test.client');
        $this->client->setServerParameters(['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'admin']);
        $this->randUrl = "test_url_x4x0x9x4x";
    }


    public function testIndex()
    {
        $this->client->request('GET', $this->router->generate('withdraw_site_list', ['_locale' => 'en']));
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }


    public function testNew()
    {
        $this->client->request('POST', $this->router->generate('withdraw_site_new', ['_locale' => 'en']), ['url' => [$this->randUrl]]);
        $this->assertTrue($this->client->getResponse()->isRedirect($this->router->generate('withdraw_site_list', ['_locale' => 'en'])));

        $urlEntity = $this->em->getRepository('WithdrawBundle:Site')->findOneBy(['url' => $this->randUrl]);
        $this->assertTrue(!empty($urlEntity));
    }


    public function testEdit()
    {

        $urlEntity = $this->em->getRepository('WithdrawBundle:Site')->findOneBy(['url' => $this->randUrl]);
        $crawler = $this->client->request('GET', $this->router->generate('withdraw_site_edit', ['id' => $urlEntity->getId(), '_locale' => 'en']));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $newUrl = $this->randUrl . '_edited';
        $form = $crawler->selectButton('submit')->form([
            'site[url]' => $newUrl,
        ]);
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect($this->router->generate('withdraw_site_show', ['id' => $urlEntity->getId(), '_locale' => 'en'])));

        $newUrlEntity = $this->em->getRepository('WithdrawBundle:Site')->findOneBy(['url' => $newUrl]);
        $this->assertTrue(!empty($newUrlEntity));


        $crawler = $this->client->request('GET', $this->router->generate('withdraw_site_edit', ['id' => $newUrlEntity->getId(), '_locale' => 'en']));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('submit')->form([
            'site[url]' => $this->randUrl,
        ]);
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect($this->router->generate('withdraw_site_show', ['id' => $newUrlEntity->getId(), '_locale' => 'en'])));

        $rolBackurlEntity = $this->em->getRepository('WithdrawBundle:Site')->findOneBy(['url' => $this->randUrl]);
        $this->assertTrue(!empty($rolBackurlEntity));
    }


    public function testShow()
    {
        $urlEntity = $this->em->getRepository('WithdrawBundle:Site')->findOneBy(['url' => $this->randUrl]);
        $this->client->request('GET', $this->router->generate('withdraw_site_show', ['id' => $urlEntity->getId(), '_locale' => 'en']));
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testDelete()
    {
        $urlEntity = $this->em->getRepository('WithdrawBundle:Site')->findOneBy(['url' => $this->randUrl]);
        $this->client->request('POST', $this->router->generate('withdraw_site_delete', ['id' => base64_encode(serialize([$urlEntity->getId()])), '_locale' => 'en']));
        $this->assertTrue($this->client->getResponse()->isRedirect($this->router->generate('withdraw_site_list', ['_locale' => 'en'])));

        $urlAfterDeleteEntity = $this->em->getRepository('WithdrawBundle:Site')->findOneBy(['url' => $this->randUrl]);
        $this->assertTrue(empty($urlAfterDeleteEntity));
    }


}
