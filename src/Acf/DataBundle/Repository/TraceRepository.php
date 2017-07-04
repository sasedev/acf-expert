<?php
namespace Acf\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Acf\DataBundle\Entity\User;
use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Entity\Trace;
use Acf\DataBundle\Entity\Job;
use Acf\DataBundle\Entity\CompanyType;
use Acf\DataBundle\Entity\Sector;

/**
 * TraceRepository
 * This class was generated by the Doctrine ORM.
 * Add your own custom
 * repository methods below.
 */
class TraceRepository extends EntityRepository
{

    /**
     * Get Query for All Entities
     *
     * @param \DateTime $minDtCrea
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery(\DateTime $minDtCrea = null)
    {
        if (null == $minDtCrea) {
            return $this->createQueryBuilder('t')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->getQuery();
        } else {
            return $this->createQueryBuilder('t')->where('t.dtCrea >= :minDate')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('minDate', $minDtCrea)->getQuery();
        }
    }

    /**
     * Get All Entities
     *
     * @param \DateTime $minDtCrea
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAll(\DateTime $minDtCrea = null)
    {
        return $this->getAllQuery($minDtCrea)->execute();
    }

    /**
     * Get Query for All Entities
     *
     * @param User $user
     *
     * @param \DateTime $minDtCrea
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByAdminQuery(User $user, \DateTime $minDtCrea = null)
    {
        $companies = $user->getAdmCompanies();
        if (null == $companies || \count($companies) == 0) {
            if (null == $minDtCrea) {
                return $this->createQueryBuilder('t')->where('t.companyId IN (:companyIds)')->andWhere('t.actionEntity NOT IN (:entities)')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('companyIds', null)->setParameter('entities', array(
                    Trace::AE_DOCGROUPSYST,
                    Trace::AE_DOCGROUPAUDIT,
                    Trace::AE_SHAREHOLDER,
                    Trace::AE_PILOT,
                    Trace::AE_CUSER,
                    Trace::AE_CADMIN
                ))->getQuery();
            } else {
                return $this->createQueryBuilder('t')->where('t.companyId IN (:companyIds)')->andWhere('t.actionEntity NOT IN (:entities)')->andWhere('t.dtCrea >= :minDate')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('companyIds', null)->setParameter('entities', array(
                    Trace::AE_DOCGROUPSYST,
                    Trace::AE_DOCGROUPAUDIT,
                    Trace::AE_SHAREHOLDER,
                    Trace::AE_PILOT,
                    Trace::AE_CUSER,
                    Trace::AE_CADMIN
                ))->setParameter('minDate', $minDtCrea)->getQuery();
            }
        } else {
            $cIds = array();
            foreach ($companies as $company) {
                $cIds[] = $company->getId();
            }
            if (null == $minDtCrea) {
                return $this->createQueryBuilder('t')->where('t.companyId IN (:companyIds)')->andWhere('t.actionEntity NOT IN (:entities)')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('companyIds', $cIds)->setParameter('entities', array(
                    Trace::AE_DOCGROUPSYST,
                    Trace::AE_DOCGROUPAUDIT,
                    Trace::AE_SHAREHOLDER,
                    Trace::AE_PILOT,
                    Trace::AE_CUSER,
                    Trace::AE_CADMIN
                ))->getQuery();
            } else {
                return $this->createQueryBuilder('t')->where('t.companyId IN (:companyIds)')->andWhere('t.actionEntity NOT IN (:entities)')->andWhere('t.dtCrea >= :minDate')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('companyIds', $cIds)->setParameter('entities', array(
                    Trace::AE_DOCGROUPSYST,
                    Trace::AE_DOCGROUPAUDIT,
                    Trace::AE_SHAREHOLDER,
                    Trace::AE_PILOT,
                    Trace::AE_CUSER,
                    Trace::AE_CADMIN
                ))->setParameter('minDate', $minDtCrea)->getQuery();
            }
        }
    }

    /**
     * Get All Entities
     *
     * @param User $user
     *
     * @param \DateTime $minDtCrea
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByAdmin(User $user, \DateTime $minDtCrea = null)
    {
        return $this->getAllByAdminQuery($user, $minDtCrea)->execute();
    }

    /**
     * Get Query for All Entities
     *
     * @param User $entity
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByUserQuery(User $entity)
    {
        return $this->createQueryBuilder('t')->where('t.actionEntity = :entityType')->andWhere('t.actionId = :aId')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('entityType', Trace::AE_USER)->setParameter('aId', $entity->getId())->getQuery();
    }

    /**
     * Get All Entities
     *
     * @param User $entity
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByUser(User $entity)
    {
        return $this->getAllByUserQuery($entity)->execute();
    }

    /**
     * Get Query for All Entities
     *
     * @param Job $entity
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByJobQuery(Job $entity)
    {
        return $this->createQueryBuilder('t')->where('t.actionEntity = :entityType')->andWhere('t.actionId = :aId')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('entityType', Trace::AE_JOB)->setParameter('aId', $entity->getId())->getQuery();
    }

    /**
     * Get All Entities
     *
     * @param Job $entity
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByJob(Job $entity)
    {
        return $this->getAllByJobQuery($entity)->execute();
    }

    /**
     * Get Query for All Entities
     *
     * @param CompanyType $entity
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByCompanyTypeQuery(CompanyType $entity)
    {
        return $this->createQueryBuilder('t')->where('t.actionEntity = :entityType')->andWhere('t.actionId = :aId')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('entityType', Trace::AE_TYPE)->setParameter('aId', $entity->getId())->getQuery();
    }

    /**
     * Get All Entities
     *
     * @param CompanyType $entity
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByCompanyType(CompanyType $entity)
    {
        return $this->getAllByCompanyTypeQuery($entity)->execute();
    }

    /**
     * Get Query for All Entities
     *
     * @param Sector $entity
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllBySectorQuery(Sector $entity)
    {
        return $this->createQueryBuilder('t')->where('t.actionEntity = :entityType')->andWhere('t.actionId = :aId')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('entityType', Trace::AE_SECTOR)->setParameter('aId', $entity->getId())->getQuery();
    }

    /**
     * Get All Entities
     *
     * @param Sector $entity
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllBySector(Sector $entity)
    {
        return $this->getAllBySectorQuery($entity)->execute();
    }

    /**
     * Get Query for All Entities
     *
     * @param Company $company
     * @param boolean $showall
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByCompanyQuery(Company $company, $showall = true)
    {
        $qb = $this->createQueryBuilder('t')->where('t.companyId = :companyId')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('companyId', $company->getId());
        if ($showall == false) {
            $qb->andWhere('t.actionEntity NOT IN (:entities)');
            $qb->setParameter('entities', array(
                Trace::AE_DOCGROUPSYST,
                Trace::AE_DOCGROUPAUDIT,
                Trace::AE_SHAREHOLDER,
                Trace::AE_PILOT,
                Trace::AE_CUSER,
                Trace::AE_CADMIN
            ));
        }

        return $qb->getQuery();
    }

    /**
     * Get All Entities
     *
     * @param Company $company
     * @param boolean $showall
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByCompany(Company $company, $showall = true)
    {
        return $this->getAllByCompanyQuery($company, $showall)->execute();
    }

    /**
     * Get Query for All Entities
     *
     * @param string $entityId
     *
     * @param string $entityType
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByEntityIdQuery($entityId, $entityType)
    {
        return $this->createQueryBuilder('t')->where('t.actionEntity = :entityType OR t.actionEntity2 = :entityType OR t.actionEntity3 = :entityType OR t.actionEntity4 = :entityType')->andWhere('t.actionId = :aId OR t.actionId2 = :aId OR t.actionId3 = :aId OR t.actionId4 = :aId')->orderBy('t.dtCrea', 'ASC')->addOrderBy('t.actionType', 'ASC')->addOrderBy('t.actionEntity', 'ASC')->setParameter('entityType', $entityType)->setParameter('aId', $entityId)->getQuery();
    }

    /**
     * Get All Entities
     *
     * @param string $entityId
     *
     * @param string $entityType
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByEntityId($entityId, $entityType)
    {
        return $this->getAllByEntityIdQuery($entityId, $entityType)->execute();
    }
}
