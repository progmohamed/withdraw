<?php

namespace WithdrawBundle\Entity\Repository\Site;

use Doctrine\ORM\EntityRepository;
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