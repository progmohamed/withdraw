<?php

namespace WithdrawBundle\Service\WithdrawService;

use CommonBundle\Classes\PublicService;

class WithdrawService extends PublicService
{

    public $scraper;

    public function getName()
    {
        return "withdraw";
    }

    public function getScraper($url)
    {
        if (!$this->scraper) {
            $this->scraper = new Scraper($url);
        }
        return $this->scraper;
    }
}