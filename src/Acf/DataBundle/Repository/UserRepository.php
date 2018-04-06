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
        $now = new \DateTime();
        $now->setTimestamp(strtotime('now'));

        $qb = $this->createQueryBuilder('u');
        $qb->where('u.username = :username');
        $qb->andWhere('u.lockout = :lockout');
        $qb->andWhere($qb->expr()
            ->orX($qb->expr()
            ->isNull('u.lastValidity'), $qb->expr()
            ->gt('u.lastValidity', ':now')));
        $qb->setParameter('username', $username);
        $qb->setParameter('lockout', User::LOCKOUT_UNLOCKED);
        $qb->setParameter('now', $now);
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
     * Get Query for All Entities
     *
     * @param string $q
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllSearchQuery($q)
    {
        $qb = $this->createQueryBuilder('u')
            ->distinct()
            ->where('LOWER(u.username) LIKE :key')
            ->orWhere('LOWER(u.email) LIKE :key')
            ->orWhere('LOWER(u.firstName) LIKE :key')
            ->orWhere('LOWER(u.lastName) LIKE :key')
            ->orWhere('LOWER(u.streetNum) LIKE :key')
            ->orWhere('LOWER(u.address) LIKE :key')
            ->orWhere('LOWER(u.address2) LIKE :key')
            ->orWhere('LOWER(u.town) LIKE :key')
            ->orWhere('LOWER(u.zipCode) LIKE :key')
            ->orWhere('LOWER(u.mobile) LIKE :key')
            ->orWhere('LOWER(u.phone) LIKE :key')
            ->orderBy('u.username', 'ASC')
            ->setParameter('key', '%' . strtolower($q) . '%');
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

        $qb = $this->createQueryBuilder('u')
            ->where('u.lastActivity > :delay')
            ->setParameter('delay', $delay)
            ->orderBy('u.lastActivity', 'DESC');
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
     * Get Query for All Entities for last validity date
     *
     * @param \DateTime $lastValidity
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllByLastValidityQuery(\DateTime $lastValidity)
    {
        $lastValidityP1 = new \DateTime();
        $lastValidityP1->setTimestamp($lastValidity->getTimestamp() + 3600 * 24);

        $qb = $this->createQueryBuilder('u')
            ->where('u.lastValidity >= :lastValidity')
            ->andWhere('u.lastValidity <= :lastValidityp1')
            ->setParameter('lastValidity', $lastValidity)
            ->setParameter('lastValidityp1', $lastValidityP1)
            ->orderBy('u.username', 'ASC');
        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities for last validity date
     *
     * @param \DateTime $lastValidity
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllByLastValidity(\DateTime $lastValidity)
    {
        return $this->getAllByLastValidityQuery($lastValidity)->execute();
    }

    /**
     * Get Query for All Entities where lockout is unlocked
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllUnlockedQuery()
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.lockout = :lockout')
            ->orderBy('u.username', 'ASC')
            ->setParameter('lockout', User::LOCKOUT_UNLOCKED);
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

    /**
     * Get Query for All Entities where lockout is unlocked
     *
     * @return \Doctrine\ORM\Query
     */
    public function getAllAoNewsletterQuery()
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.userRoles', 'r')
            ->where('u.lockout = :uLockout')
            ->andWhere('r.name = :rName')
            ->orderBy('u.username', 'ASC')
            ->setParameter('uLockout', User::LOCKOUT_UNLOCKED)
            ->setParameter('rName', 'ROLE_CLIENT10');

        $query = $qb->getQuery();

        return $query;
    }

    /**
     * Get All Entities where lockout is unlocked
     *
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function getAllAoNewsletter()
    {
        return $this->getAllAoNewsletterQuery()->execute();
    }
}
