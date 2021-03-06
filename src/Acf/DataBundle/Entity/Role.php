<?php
namespace Acf\DataBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\Role as BaseRole;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Role
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_roles")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\RoleRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"name"}, errorPath="name", groups={"name"})
 */
class Role extends BaseRole implements \Serializable
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="bigint", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="SEQUENCE")
     *      @ORM\SequenceGenerator(sequenceName="acf_roles_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     *
     * @var string @ORM\Column(name="name", type="string", length=250, nullable=false)
     *      @Assert\Length(max=100, groups={"name"})
     *      @Assert\Regex(pattern="/^ROLE\_[A-Z](([A-Z0-9\_]+[A-Z0-9])|[A-Z0-9])$/", groups={"name"})
     */
    protected $name;

    /**
     *
     * @var string @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     *
     * @var \DateTime @ORM\Column(name="created_at", type="datetimetz", nullable=true)
     */
    protected $dtCrea;

    /**
     *
     * @var \DateTime @ORM\Column(name="updated_at", type="datetimetz", nullable=true)
     *      @Gedmo\Timestampable(on="update")
     */
    protected $dtUpdate;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Role", inversedBy="childs", cascade={"persist"})
     *      @ORM\JoinTable(
     *      name="acf_role_parents",
     *      joinColumns={@ORM\JoinColumn(name="child", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parent", referencedColumnName="id")}
     *      )
     *      @ORM\OrderBy({"name" = "ASC"})
     */
    protected $parents;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Role", mappedBy="parents")
     *      @ORM\JoinTable(
     *      name="acf_role_parents",
     *      joinColumns={@ORM\JoinColumn(name="parent", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="child", referencedColumnName="id")}
     *      )
     *      @ORM\OrderBy({"name" = "ASC"})
     */
    protected $childs;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="User", mappedBy="userRoles")
     *      @ORM\JoinTable(name="acf_users_roles",
     *      joinColumns={
     *      @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dtCrea = new \DateTime('now');

        $this->parents = new ArrayCollection();
        $this->childs = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * Get $id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get $name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set $name
     *
     * @param string $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = strtoupper(trim($name));

        return $this;
    }

    /**
     * Get $description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set $description
     *
     * @param string $description
     *
     * @return Role
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get $dtCrea
     *
     * @return \DateTime
     */
    public function getDtCrea()
    {
        return $this->dtCrea;
    }

    /**
     * Set $dtCrea
     *
     * @param \DateTime $dtCrea
     *
     * @return Role
     */
    public function setDtCrea(\DateTime $dtCrea = null)
    {
        $this->dtCrea = $dtCrea;

        return $this;
    }

    /**
     * Get $dtUpdate
     *
     * @return \DateTime
     */
    public function getDtUpdate()
    {
        return $this->dtUpdate;
    }

    /**
     * Set $dtUpdate
     *
     * @param \DateTime $dtUpdate
     *
     * @return Role $this
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Add parent
     *
     * @param Role $parent
     *
     * @return Role
     */
    public function addParent(Role $parent)
    {
        $this->parents[] = $parent;

        return $this;
    }

    /**
     * Remove parent
     *
     * @param Role $parent
     *
     * @return Role
     */
    public function removeParent(Role $parent)
    {
        $this->parents->removeElement($parent);

        return $this;
    }

    /**
     * Get $parents
     *
     * @return ArrayCollection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Set parents
     *
     * @param Collection $parents
     *
     * @return Role $this
     */
    public function setParents(Collection $parents)
    {
        $this->parents = $parents;

        return $this;
    }

    /**
     * Add child
     *
     * @param Role $child
     *
     * @return Role
     */
    public function addChild(Role $child)
    {
        $this->childs[] = $child;
        $child->addParent($this);

        return $this;
    }

    /**
     * Remove child
     *
     * @param Role $child
     *
     * @return Role $this
     */
    public function removeChild(Role $child)
    {
        $this->childs->removeElement($child);
        $child->removeParent($this);

        return $this;
    }

    /**
     * Get childs
     *
     * @return ArrayCollection
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Set childs
     *
     * @param Collection $childs
     *
     * @return Role
     */
    public function setChilds(Collection $childs)
    {
        $this->childs = $childs;

        return $this;
    }

    /**
     * Add user
     *
     * @param User $user
     *
     * @return Role $this
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
        $user->addUserRole($this);

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     *
     * @return Role $this
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
        $user->removeUserRole($this);

        return $this;
    }

    /**
     * Get $users
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set $users
     *
     * @param Collection $users
     *
     * @return Role $this
     */
    public function setUsers(Collection $users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isCanDelete()
    {
        if (count($this->getUsers()) != 0) {
            return false;
        }

        return true;
    }

    /**
     *
     * {@inheritdoc} @see \Symfony\Component\Security\Core\Role\Role::getRole()
     * @return string
     */
    public function getRole()
    {
        return $this->getName();
    }

    /**
     * Serializes the User.
     * The serialized data have to contain the fields used by the equals method
     * and the username.
     *
     * {@inheritdoc} @see Serializable::serialize()
     * @return string
     */
    public function serialize()
    {
        return \serialize(array(
            $this->description,
            $this->name,
            $this->id
        ));
    }

    /**
     * Unserializes the User.
     *
     * {@inheritdoc} @see Serializable::unserialize()
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = \unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough
        // keys when
        // unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        list ($this->description, $this->name, $this->id) = $data;
    }

    /**
     * string representation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     */
    public function __clone()
    {}
}
