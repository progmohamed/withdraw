<?php

namespace WithdrawBundle\Entity\Repository\Site;

use CommonBundle\Service\CommonService;
use Doctrine\ORM\EntityRepository;
use TaskManagerBundle\Service\TaskManagerService;
use WithdrawBundle\Entity\Site;
use WithdrawBundle\Service\WithdrawService\WithdrawService;

class Repository extends EntityRepository
{

    private static $dataGrid;

    // singleton to separate the grid methods from the rest of repository (SOLID)
    public function getDataGrid()
    {
        if (!self::$dataGrid) {
            self::$dataGrid = new DataGrid($this->getEntityManager());
        }
        return self::$dataGrid;
    }

    public function add($urls, $user, CommonService $commonService, $serviceName, TaskManagerService $taskmanagerService)
    {
        $em = $this->getEntityManager();
        $data = [];
        foreach ($urls as $url) {
            $entity = new Site();
            $entity->setUrl($url)
                ->setUser($user);
            $em->persist($entity);
            $em->flush();
            $data[] = [
                'id'        => $entity->getId(),
                'url'       => $entity->getUrl(),
                'createdAt' => $entity->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
            // record adding log
            $commonService->log($serviceName, 'withdraw.log.add_site', ['%url%' => $url], $user->getId());
            // add scraping task in queue
            $taskmanagerService->addTaskQueued(
                'withdraw:scraper',
                ['id' => $entity->getId()],
                'scraping site'
            );
        }
        return $data;
    }

    public function edit(Site $entity, $user, CommonService $commonService, $serviceName, TaskManagerService $taskmanagerService)
    {
        $em = $this->getEntityManager();
        $em->getRepository('WithdrawBundle:SiteMetric')->deleteSiteMetrics($entity);
        $entity->setUser($user);
        $entity->setStatus(Site::STATUS_NEW);
        $em->flush();
        // record modify log
        $commonService->log($serviceName, 'withdraw.log.edit_site', ['%entity_id%' => $entity->getId()], $user->getId());
        // add scraping task in queue
        $taskmanagerService->addTaskQueued(
            'withdraw:scraper',
            ['id' => $entity->getId()],
            'scraping site'
        );
    }

    public function delete(Site $entity, $user, CommonService $commonService, $serviceName)
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $commonService->log($serviceName, 'withdraw.log.delete_site', ['%url%' => $entity], $user->getId());
    }


    public function getChanges($ids)
    {
        $em = $this->getEntityManager();
        $parameters = [];
        $dql = "SELECT s, sm
        FROM WithdrawBundle:Site s
        LEFT JOIN s.metrics sm
        INDEX BY sm.metric
        WHERE s.id in(:ids) ";

        $parameters['ids'] = $ids;
        $query = $em->createQuery($dql);
        if (sizeof($parameters)) {
            $query->setParameters($parameters);
        }

        return $query->getResult();

    }

    public function getDeleteRestrectionsByIds(WithdrawService $service, $ids)
    {
        // get any delete restrections whene delete any row (SOA)
        $restrictions = [];
        $delets = [];
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $entity = $this->find($id);
                if ($entity) {
                    $totalCount = 0;
                    foreach ($service->getRelatedServices() as $relatedService) {
                        $count = $relatedService->getLocale()->getDialectRestrictions($entity);
                        if ($count) {
                            $restrictions[] = [
                                'entity'      => $entity,
                                'serviceName' => $relatedService->getName(),
                                'count'       => $count
                            ];
                        }
                        $totalCount += $count;
                    }
                    if (0 == $totalCount) {
                        $delets[] = $entity;
                    }
                }
            }
        }
        return [
            'restrictions' => $restrictions,
            'delets'       => $delets,
        ];
    }
}