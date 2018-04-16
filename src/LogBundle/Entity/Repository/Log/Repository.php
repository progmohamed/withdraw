<?php

namespace LogBundle\Entity\Repository\Log;

use Doctrine\ORM\EntityRepository;
use LogBundle\Service\LogService\LogService;

class Repository extends EntityRepository
{

    private static $dataGrid;

    public function getDataGrid()
    {
        if (!self::$dataGrid) {
            self::$dataGrid = new DataGrid($this->getEntityManager());
        }
        return self::$dataGrid;
    }

    public function getDeleteRestrectionsByIds(LogService $service, $ids)
    {
        $restrictions = [];
        $delets = [];
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $entity = $this->find($id);
                if ($entity) {
                    $totalCount = 0;
                    foreach ($service->getRelatedServices() as $relatedService) {
                        $count = $relatedService->getLocale()->getLogRestrictions($entity->getId());
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