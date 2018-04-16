<?php

namespace WithdrawBundle\Entity\Repository\SiteMetric;

use Doctrine\ORM\EntityRepository;
use WithdrawBundle\Entity\Site;
use WithdrawBundle\Entity\SiteMetric;

class Repository extends EntityRepository
{
    public function addMetrics(Site $site, $metrics)
    {
        $em = $this->getEntityManager();
        // delete old metrics
        $this->deleteSiteMetrics($site);
        // add new metrics
        foreach ($metrics as $key => $value) {
            $entity = new SiteMetric();
            $entity->setSite($site)
                ->setMetric($key)
                ->setValue($value);
            $em->persist($entity);
        }
        $em->flush();
    }

    public function deleteSiteMetrics(Site $site)
    {
        $em = $this->getEntityManager();
        $dql = " DELETE FROM WithdrawBundle:SiteMetric sm WHERE sm.site = :site ";
        $query = $em->createQuery($dql);
        $query->setParameter('site', $site);
        $query->execute();
    }

}