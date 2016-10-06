<?php
namespace Acf\DataBundle\Security;

use Acf\DataBundle\Model\RoleManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class RoleManager implements RoleManagerInterface
{

    protected $class;

    protected $entityManager;

    protected $entityRepository;

    /**
     *
     * @param ManagerRegistry $managerRegistry
     * @param unknown $class
     */
    public function __construct(ManagerRegistry $managerRegistry, $class)
    {
        $this->class = $class;
        $this->entityManager = $managerRegistry->getManagerForClass($class);
        $this->entityRepository = $this->entityManager->getRepository($class);
    }

    /**
     *
     * {@inheritdoc} @see RoleManagerInterface::getEntityManager()
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * {@inheritdoc} @see RoleManagerInterface::getEntityRepository()
     */
    public function getEntityRepository()
    {
        return $this->entityRepository;
    }

    /**
     *
     * {@inheritdoc} @see RoleManagerInterface::getRoles()
     */
    public function getRoles()
    {
        return $this->getEntityRepository()->findAll();
    }

    /**
     *
     * {@inheritdoc} @see RoleManagerInterface::getClass()
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     *
     * {@inheritdoc} @see RoleManagerInterface::createRole()
     */
    public function createRole()
    {
        $class = $this->getClass();

        return new $class();
    }

    /**
     *
     * {@inheritdoc} @see RoleManagerInterface::saveRole()
     */
    public function saveRole(RoleInterface $role)
    {
        $this->getEntityManager()->persist($role);
        $this->getEntityManager()->flush();

        return $this;
    }
}
