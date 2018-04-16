<?php

namespace WithdrawBundle\Service\WithdrawService;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;

class Scraper
{
    protected $crawler;
    protected $siteUrl;
    protected $container;

    public function __construct(ContainerInterface $container, $siteUrl, $method = 'GET')
    {
        $this->container = $container;
        // add http:// if url is not contain's http:// or https://
        $siteUrl = (preg_match('/^(https?:\/\/)/i', $siteUrl)) ? $siteUrl : 'http://' . $siteUrl;
        $client = new Client();
        $guzzleClient = new GuzzleClient([
            'verify' => false,
        ]);
        $client->setClient($guzzleClient);
        $client->setHeader('user-agent', $this->container->get('config.service')->getGlobalConfigValue('userAgent', null));
        $this->crawler = $client->request($method, $siteUrl);
        if ($client->getResponse()->getStatus() !== 200) {
            throw new \Exception('Faild', $client->getResponse()->getStatus());
        }
        $this->siteUrl = $siteUrl;
    }

    public function getMetrics()
    {
        return [
            'title'          => $this->getTitle(),
            'ex_links_count' => $this->getExLinksCount(),
            'ga_is_exist'    => $this->gaIsExist()
        ];
    }

    protected function getTitle()
    {
        return $this->crawler->filterXPath('//head//title')->eq(0)->text();
    }

    protected function getExLinksCount()
    {
        $exLinksCount = 0;
        $this->crawler->filter('a')->each(function (Crawler $node) use (&$exLinksCount) {
            $url = trim($node->attr('href'));
            if ($url) {
                if ($this->isExternal($url)) $exLinksCount++;
            }
        });
        return $exLinksCount;
    }

    protected function gaIsExist()
    {
        return ($this->crawler->filterXPath('//script[contains(text(),"www.google-analytics.com/analytics.js") or contains(text(),".google-analytics.com/ga.js") or contains(text(),"www.googletagmanager.com/gtm.js?id=") or contains(@src, "https://www.googletagmanager.com/gtag/js?id=")]')->count()) ? 1 : 0;
    }

    private function isExternal($ongoingUrl)
    {
        $ongoingUrl = parse_url(str_replace('://www.', '://', $ongoingUrl));
        $siteUrl = parse_url(str_replace('://www.', '://', $this->siteUrl));

        $subDomainAsInternal = (bool)$this->container->get('config.service')->getGlobalConfigValue('subDomainAsInternal', true);
        if (true == $subDomainAsInternal) {
            // if $ongoingUrl host == $siteUrl of u$ongoingUrl look like '/depth.php'
            if (empty($ongoingUrl['host']) || strcasecmp($ongoingUrl['host'], $siteUrl['host']) === 0) return false;
            //if the $ongoingUrl host is subdomain
            return strrpos(strtolower($ongoingUrl['host']), '.' . $siteUrl['host']) !== strlen($ongoingUrl['host']) - strlen('.' . $siteUrl['host']);
        } else {
            //if we decided consider subdomain as external links
            return !empty($ongoingUrl['host']) && strcasecmp($ongoingUrl['host'], $siteUrl['host']);
        }
    }
}