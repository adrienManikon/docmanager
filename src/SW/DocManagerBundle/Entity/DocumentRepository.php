<?php

namespace SW\DocManagerBundle\Entity;

/**
 * DocumentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DocumentRepository extends \Doctrine\ORM\EntityRepository
{
    const LIMIT_SEARCH = 10;


    public function getByCode($code) {
        $queryBuilder = $this->createQueryBuilder('d');    
        
        $queryBuilder
                ->where('d.code LIKE :code')
                ->andWhere('d.disabled = 1')
                ->setParameter('code', '%' . $code . '%')
                ->setMaxResults(self::LIMIT_SEARCH)
                ->getQuery();
        
        return $queryBuilder
                ->getQuery()
                ->getResult();
    }
    
    public function getOneByName($name) {
        $queryBuilder = $this->createQueryBuilder('d');    
        
        $queryBuilder
                ->where('d.name = :name')
                ->andWhere('d.disabled = 1')
                ->setParameter('name', $name)
                ->getQuery();
        
        return $queryBuilder
                ->getQuery()
                ->getOneOrNullResult();
    }
    
    public function search($nameCode, $dateStart, $dateEnd, $code, $initials) {
        
        $queryBuilder = $this->createQueryBuilder('d');
   
        $queryBuilder
                ->distinct()
                ->where('d.disabled = 1');
        
        if ($nameCode != null) {
            $queryBuilder
                ->andWhere('d.code LIKE :nameCode')
                ->orWhere('d.name LIKE :nameCode')
                ->setParameter('nameCode', '%' . $nameCode . '%');
        }
        
        if ($code != null) {
            $queryBuilder
                ->andWhere('d.code LIKE :code')
                ->setParameter('code', '%' . $code . '%');
        }
        
        if ($dateStart != null && $dateEnd != null) {
            
            $queryBuilder
                ->andWhere('d.date BETWEEN :dateStart AND :dateEnd')
                ->setParameter('dateStart', $dateStart)
                ->setParameter('dateEnd', $dateEnd);
            
        } else if ($dateStart != null) {
            
            $queryBuilder
                ->andWhere('d.date > :dateStart')
                ->setParameter('dateStart', $dateStart);

            
        }  else if ($dateEnd != null) {
            
            $queryBuilder
                ->andWhere('d.date > :dateStart')
                ->setParameter('dateStart', $dateStart);
            
        }
        
        if ($initials != null) {
            
            $queryBuilder
                ->andWhere('d.initials = :initials')
                ->setParameter('initials', $initials); 
            
        }
        
        $queryBuilder->setMaxResults(self::LIMIT_SEARCH);

        return $queryBuilder
                ->getQuery()
                ->getResult();
        
    }
}
