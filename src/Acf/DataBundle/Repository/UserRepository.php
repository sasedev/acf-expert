<?php
namespace Acf\DataBundle\Repository;

use Acf\DataBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UserRepository extends EntityRepository implements UserProviderInterface, UserLoaderInterface
{

    /**
     * Used for Authentification Security
     *
     * {@inheritdoc}
     * @see \Symfony\Component\Security\Core\User\UserProviderInterface::loadUserByUsername()
     *
     * @throws UsernameNotFoundException
     *
     * @return UserInterface
     */
    public function loadUserByUsername($username)
    {
        $qb = $this->createQueryBuilder('u')->where('u.username = :username')->andWhere('u.lockout = :lockout')->setParameter('username', $username)->setParameter('lockout', User::LOCKOUT_UNLOCKED);
        $query = $qb->getQuery();

        try {
            $user = $query->getSingleResult();
        } catch (NoResultException $e) {
            $exp = new UsernameNotFoundException(sprintf('Unable to find an active User identified by "%s".', $username), 0, $e);
            $exp->setUsername($username);
            throw $exp;
        }

        return $user;
    }

    /**
     * Used for Authentification Security
     *
     * {@inheritdoc} @see UserProviderInterface::refreshUser()
     * @param UserInterface $user
     *
     * @return User
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Check if is a sublass of the Entity
     *
     * {@inheritdoc} @see UserProviderInterface::supportsClass()
     * @param string $class
     *
     * @return boolean
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    /**
     * Count All
     *
     * @return mixed
     */
    public function count()
    {
        $qb = $this->createQueryBuilder('u')->select('count(u)');
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery()
    {
        $qb = $this->createQueryBuilder('u')->orderBy('u.username', 'ASC');
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAll()
    {
        return $this->getAllQuery()->execute();
    }

    /**
     * Count All
     *
     * @param string $q
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function countSearch($q)
    {
        $qb = $this->createQueryBuilder('u')->select('count(u)')->distinct()->where('LOWER(u.username) LIKE :key')->orWhere('LOWER(u.email) LIKE :key')->orWhere('LOWER(u.firstName) LIKE :key')->orWhere('LOWER(u.lastName) LIKE :key')->orWhere('LOWER(u.streetNum) LIKE :key')->orWhere('LOWER(u.address) LIKE :key')->orWhere('LOWER(u.address2) LIKE :key')->orWhere('LOWER(u.town) LIKE :key')->orWhere('LOWER(u.zipCode) LIKE :key')->orWhere('LOWER(u.mobile) LIKE :key')->orWhere('LOWER(u.phone) LIKE :key')->setParameter('key', '%' . strtolower($q) . '%');
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities
     *
     * @param string $q
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllSearchQuery($q)
    {
        $qb = $this->createQueryBuilder('u')->distinct()->where('LOWER(u.username) LIKE :key')->orWhere('LOWER(u.email) LIKE :key')->orWhere('LOWER(u.firstName) LIKE :key')->orWhere('LOWER(u.lastName) LIKE :key')->orWhere('LOWER(u.streetNum) LIKE :key')->orWhere('LOWER(u.address) LIKE :key')->orWhere('LOWER(u.address2) LIKE :key')->orWhere('LOWER(u.town) LIKE :key')->orWhere('LOWER(u.zipCode) LIKE :key')->orWhere('LOWER(u.mobile) LIKE :key')->orWhere('LOWER(u.phone) LIKE :key')->orderBy('u.username', 'ASC')->setParameter('key', '%' . strtolower($q) . '%');
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities
     *
     * @param string $q
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllSearch($q)
    {
        return $this->getAllSearchQuery($q)->execute();
    }

    /**
     * Count All that are Active 1 minute ago
     *
     * @param string $strtotime
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function countAllActiveNow($strtotime = null)
    {
        if (null == $strtotime || trim($strtotime) == '') {
            $strtotime = '1 minutes ago';
        }

        $delay = new \DateTime();
        $delay->setTimestamp(strtotime($strtotime));

        $qb = $this->createQueryBuilder('u')->select('count(u)')->where('u.lastActivity > :delay')->orderBy('u.lastActivity', 'ASC')->setParameter('delay', $delay);
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities that are Active 1 minute ago
     *
     * @param string $strtotime
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllActiveNowQuery($strtotime = null)
    {
        if (null == $strtotime || trim($strtotime) == '') {
            $strtotime = '1 minutes ago';
        }

        $delay = new \DateTime();
        $delay->setTimestamp(strtotime($strtotime));

        $qb = $this->createQueryBuilder('u')->where('u.lastActivity > :delay')->setParameter('delay', $delay)->orderBy('u.lastActivity', 'DESC');
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities that are Active 1 minute ago
     *
     * @param string $strtotime
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllActiveNow($strtotime = null)
    {
        return $this->getAllActiveNowQuery($strtotime)->execute();
    }

    /**
     * Count All Unlocked
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function countAllUnlocked()
    {
        $qb = $this->createQueryBuilder('u')->select('count(u)')->where('u.lockout = :lockout')->setParameter('lockout', User::LOCKOUT_UNLOCKED);
        $query = $qb->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * Get Query for All Entities where lockout is unlocked
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllUnlockedQuery()
    {
        $qb = $this->createQueryBuilder('u')->where('u.lockout = :lockout')->orderBy('u.username', 'ASC')->setParameter('lockout', User::LOCKOUT_UNLOCKED);
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities where lockout is unlocked
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllUnlocked()
    {
        return $this->getAllUnlockedQuery()->execute();
    }
}
