<?php

namespace WithdrawBundle\Entity\Repository\Site;

use CommonBundle\Service\CommonService;
use Doctrine\ORM\EntityRepository;
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

    public function add($urls, $user, CommonService $commonService, $serviceName)
    {
        $em = $this->getEntityManager();
        foreach ($urls as $url) {
            $entity = new Site();
            $entity->setUrl($url)
                ->setUser($user);
            $em->persist($entity);
            $commonService->log($serviceName, 'withdraw.log.add_site', ['%url%' => $url], $user->getId());
        }
        $em->flush();
    }

    public function edit(Site $entity, $user, CommonService $commonService, $serviceName)
    {
        $em = $this->getEntityManager();
        $entity->setUser($user);
        $em->flush();
        $commonService->log($serviceName, 'withdraw.log.edit_site', ['%entity_id%' => $entity->getId()], $user->getId());
    }

    public function delete(Site $entity, $user, CommonService $commonService, $serviceName)
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
        $commonService->log($serviceName, 'withdraw.log.delete_site', ['%url%' => $entity], $user->getId());
    }
    
    public function getDeleteRestrectionsByIds(WithdrawService $service, $ids)
    {
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
                                'entity' => $entity,
                                'serviceName' => $relatedService->getName(),
                                'count' => $count
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
            'delets' => $delets,
        ];
    }
}