<?php

namespace WithdrawBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WithdrawBundle\Entity\Site;

class ScraperCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('withdraw:scraper')
            ->setDescription('scraping site')
            ->addArgument('id', InputArgument::REQUIRED, 'Site ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        if ($id) {
            $em = $this->getContainer()->get('doctrine')->getManager();
            $repository = $em->getRepository('WithdrawBundle:Site');
            $site = $repository->find($id);
            if ($site) {
                $repository->updateStatus($site, Site::STATUS_CRAWLING);
                try {
                    $metrics = $this->getContainer()->get('withdraw.service')->getScraper($site->getUrl())->getMetrics();
                    $em->getRepository('WithdrawBundle:SiteMetric')->addMetrics($site, $metrics);
                    $repository->updateStatus($site, Site::STATUS_DONE);
                } catch (\Exception $e) {
                    $repository->updateStatus($site, Site::STATUS_FAILED);
                }
            }
        }
    }

}