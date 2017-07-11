<?php
namespace Acf\DataBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Role\Role;

// use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * RoleManagerInterface
 *
 * @author sasedev <seif.salah@gmail.com>
 */
interface RoleManagerInterface
{

    /**
     *
     * @return array
     */
    public function getRoles();

    /**
     *
     * @return string
     */
    public function getClass();

    /**
     *
     * @return ObjectManager
     */
    public function getEntityManager();

    /**
     *
     * @return ObjectRepository
     */
    public function getEntityRepository();

    /**
     *
     * @return Role
     */
    public function createRole();

    /**
     *
     * @param Role $role
     *
     * @return self
     */
    public function saveRole(Role $role);
}
