<?php

namespace WithdrawBundle\Service\WithdrawService;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Scraper
{
    protected $crawler;
    protected $siteUrl;

    public function __construct($siteUrl, $method = 'GET')
    {
        $client = new Client();
        $this->crawler = $client->request($method, $siteUrl);
        $this->siteUrl = $siteUrl;
    }

    public function getMetrics()
    {
        return [
            'title' => $this->getTitle(),
            'ex_links_count' => $this->getExLinksCount()
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


    private function isExternal($ongoingUrl)
    {
        $ongoingUrl = parse_url(str_replace('://www.', '://', $ongoingUrl));
        $siteUrl = parse_url(str_replace('://www.', '://', $this->siteUrl));

        // if $ongoingUrl host == $siteUrl of u$ongoingUrl look like '/depth.php'
        if (empty($ongoingUrl['host']) || strcasecmp($ongoingUrl['host'], $siteUrl['host']) === 0) return false;
        //if the $ongoingUrl host is subdomain
        return strrpos(strtolower($ongoingUrl['host']), '.' . $siteUrl['host']) !== strlen($ongoingUrl['host']) - strlen('.' . $siteUrl['host']);

        //if we decided consider subdomain as external links
        //return !empty($ongoingUrl['host']) && strcasecmp($ongoingUrl['host'], $siteUrl['host']);
    }
}