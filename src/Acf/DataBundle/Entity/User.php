<?php
namespace Acf\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Sasedev\Commons\SharedBundle\Validator as ExtraAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *         @ORM\Table(name="acf_users")
 *         @ORM\Entity(repositoryClass="Acf\DataBundle\Repository\UserRepository")
 *         @ORM\HasLifecycleCallbacks
 *         @UniqueEntity(fields={"username"}, errorPath="username", groups={"username"})
 */
class User implements UserInterface, \Serializable
{

    /**
     *
     * @var integer
     */
    const SEXE_MISS = 1;

    /**
     *
     * @var integer
     */
    const SEXE_MRS = 2;

    /**
     *
     * @var integer
     */
    const SEXE_MR = 3;

    /**
     *
     * @var integer
     */
    const LOCKOUT_UNLOCKED = 1;

    /**
     *
     * @var integer
     */
    const LOCKOUT_LOCKED = 2;

    /**
     *
     * @var integer @ORM\Column(name="id", type="bigint", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="SEQUENCE")
     *      @ORM\SequenceGenerator(sequenceName="acf_users_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     *
     * @var string @ORM\Column(name="username", type="text", nullable=false, unique=true)
     *      @Assert\Length(min = "3", max = "100", groups={"username"})
     *      @Assert\Regex(pattern="/^[a-z0-9][a-z0-9_]+$/", groups={"username"})
     */
    protected $username;

    /**
     *
     * @var string @ORM\Column(name="email", type="text", nullable=true)
     *      @Assert\Email(checkMX=true, checkHost=true, groups={"email"})
     */
    protected $email;

    /**
     *
     * @var string @ORM\Column(name="salt", type="text", nullable=true)
     */
    protected $salt;

    /**
     *
     * @var string @ORM\Column(name="clearpassword", type="text", nullable=true)
     *      @Assert\Length(min="8", max="200", groups={"clearPassword"})
     */
    protected $clearPassword;

    /**
     *
     * @var string @ORM\Column(name="passwd", type="text", nullable=true)
     */
    protected $password;

    /**
     *
     * @var string @ORM\Column(name="recoverycode", type="text", nullable=true)
     */
    protected $recoveryCode;

    /**
     *
     * @var \DateTime @ORM\Column(name="recoveryexpiration", type="datetimetz", nullable=true)
     */
    protected $recoveryExpiration;

    /**
     *
     * @var integer @ORM\Column(name="lockout", type="integer", nullable=false)
     *      @Assert\Choice(callback="choiceLockoutCallback", groups={"lockout"})
     */
    protected $lockout;

    /**
     *
     * @var integer @ORM\Column(name="logins", type="bigint", nullable=false)
     */
    protected $logins;

    /**
     *
     * @var \DateTime @ORM\Column(name="lastlogin", type="datetimetz", nullable=true)
     */
    protected $lastLogin;

    /**
     *
     * @var \DateTime @ORM\Column(name="lastactivity", type="datetimetz", nullable=true)
     */
    protected $lastActivity;

    /**
     *
     * @var string @ORM\Column(name="lastname", type="text", nullable=true)
     */
    protected $lastName;

    /**
     *
     * @var string @ORM\Column(name="firstname", type="text", nullable=true)
     */
    protected $firstName;

    /**
     *
     * @var integer @ORM\Column(name="sexe", type="integer", nullable=true)
     *      @Assert\Choice(callback="choiceSexeCallback", groups={"sexe"})
     */
    protected $sexe;

    /**
     *
     * @var \DateTime @ORM\Column(name="birthday", type="date", nullable=true)
     *      @Assert\Date(groups={"birthday"})
     */
    protected $birthday;

    /**
     *
     * @var string @ORM\Column(name="strnum", type="text", nullable=true)
     *      @Assert\Length(max="15", groups={"streetNum"})
     */
    protected $streetNum;

    /**
     *
     * @var string @ORM\Column(name="address", type="text", nullable=true)
     */
    protected $address;

    /**
     *
     * @var string @ORM\Column(name="address2", type="text", nullable=true)
     */
    protected $address2;

    /**
     *
     * @var string @ORM\Column(name="town", type="text", nullable=true)
     *      @Assert\Length(max="120", groups={"town"})
     */
    protected $town;

    /**
     *
     * @var string @ORM\Column(name="zipcode", type="text", nullable=true)
     *      @Assert\Length(max="15", groups={"zipCode"})
     */
    protected $zipCode;

    /**
     *
     * @var string @ORM\Column(name="country", type="text", nullable=true)
     *      @Assert\Country(groups={"country"})
     */
    protected $country;

    /**
     *
     * @var string @ORM\Column(name="phone", type="text", nullable=true)
     *      @ExtraAssert\Phone(groups={"phone"})
     */
    protected $phone;

    /**
     *
     * @var string @ORM\Column(name="mobile", type="text", nullable=true)
     *      @ExtraAssert\Phone(groups={"mobile"})
     */
    protected $mobile;

    /**
     *
     * @var string @ORM\Column(name="avatar", type="text", nullable=true)
     */
    protected $avatar;

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
     * @ORM\ManyToOne(targetEntity="Lang", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumns({@ORM\JoinColumn(name="lang_id", referencedColumnName="id")})
     *
     * @var Lang $preferedLang
     */
    protected $preferedLang;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Role", inversedBy="users", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_users_roles",
     *      joinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $userRoles;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Company", mappedBy="users")
     *      @ORM\JoinTable(name="acf_company_users",
     *      joinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $companies;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="CompanyUser", mappedBy="user", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"dtCrea" = "ASC"})
     */
    protected $companyUsers;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Company", mappedBy="admins", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_company_admins",
     *      joinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $admCompanies;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="CompanyAdmin", mappedBy="user", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"dtCrea" = "ASC"})
     */
    protected $companyAdmins;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Agenda", mappedBy="user", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"dtStart" = "ASC"})
     */
    protected $events;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Agenda", mappedBy="users", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_agenda_shares",
     *      joinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="evt_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $sharedEvents;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="Notification", mappedBy="user", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"dtCrea" = "ASC"})
     */
    protected $notifs;

    /**
     *
     * @var Collection @ORM\ManyToMany(targetEntity="Notification", mappedBy="users", cascade={"persist"})
     *      @ORM\JoinTable(name="acf_notif_shares",
     *      joinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="notif_id", referencedColumnName="id")
     *      }
     *      )
     */
    protected $sharedNotifs;

    /**
     *
     * @var Collection @ORM\OneToMany(targetEntity="OnlineOrder", mappedBy="user", cascade={"persist", "remove"})
     *      @ORM\OrderBy({"dtCrea" = "ASC"})
     */
    protected $orders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setSalt(md5(uniqid(null, true)));
        $this->setClearPassword(self::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
        $this->lockout = self::LOCKOUT_UNLOCKED;
        $this->logins = 0;
        $this->sexe = self::SEXE_MR;
        $this->dtCrea = new \DateTime('now');
        $this->userRoles = new ArrayCollection();
        $this->companies = new ArrayCollection();
        $this->companyUsers = new ArrayCollection();
        $this->admCompanies = new ArrayCollection();
        $this->companyAdmins = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->sharedEvents = new ArrayCollection();
        $this->notifs = new ArrayCollection();
        $this->sharedNotifs = new ArrayCollection();
        $this->orders = new ArrayCollection();
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
     * Get $username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set $username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = strtolower(str_replace(' ', '.', trim($username)));

        return $this;
    }

    /**
     * Get $email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set $email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = strtolower(trim($email));

        return $this;
    }

    /**
     * Get $salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set $salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get $clearPassword
     *
     * @return string
     */
    public function getClearPassword()
    {
        return $this->clearPassword;
    }

    /**
     * Set $clearPassword
     *
     * @param string $clearPassword
     *
     * @return User
     */
    public function setClearPassword($clearPassword)
    {
        $this->clearPassword = $clearPassword;

        return $this->setPassword($clearPassword);
    }

    /**
     * Get $password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set $password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $encoder = new Pbkdf2PasswordEncoder('sha512', true, 1000);
        $this->password = $encoder->encodePassword($password, $this->getSalt());

        return $this;
    }

    /**
     * Get $recoveryCode
     *
     * @return string
     */
    public function getRecoveryCode()
    {
        return $this->recoveryCode;
    }

    /**
     * Set $recoveryCode
     *
     * @param string $recoveryCode
     *
     * @return User
     */
    public function setRecoveryCode($recoveryCode)
    {
        $this->recoveryCode = $recoveryCode;

        return $this;
    }

    /**
     * Get $recoveryExpiration
     *
     * @return \DateTime
     */
    public function getRecoveryExpiration()
    {
        return $this->recoveryExpiration;
    }

    /**
     * Set $recoveryExpiration
     *
     * @param \DateTime $recoveryExpiration
     *
     * @return User
     */
    public function setRecoveryExpiration(\DateTime $recoveryExpiration = null)
    {
        $this->recoveryExpiration = $recoveryExpiration;

        return $this;
    }

    /**
     * Get $lockout
     *
     * @return integer
     */
    public function getLockout()
    {
        return $this->lockout;
    }

    /**
     * Set $lockout
     *
     * @param integer $lockout
     *
     * @return User
     */
    public function setLockout($lockout)
    {
        $this->lockout = $lockout;

        return $this;
    }

    /**
     * Get $logins
     *
     * @return integer
     */
    public function getLogins()
    {
        return $this->logins;
    }

    /**
     * Set $logins
     *
     * @param integer $logins
     *
     * @return User
     */
    public function setLogins($logins)
    {
        $this->logins = $logins;

        return $this;
    }

    /**
     * Get $lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set $lastLogin
     *
     * @param \DateTime $lastLogin
     *
     * @return User
     */
    public function setLastLogin(\DateTime $lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get $lastActivity
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Set $lastActivity
     *
     * @param \DateTime $lastActivity
     *
     * @return User
     */
    public function setLastActivity(\DateTime $lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get $lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set $lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get $firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set $firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get $sexe
     *
     * @return integer
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set $sexe
     *
     * @param integer $sexe
     *
     * @return User
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get $birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set $birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday(\DateTime $birthday = null)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get $streetNum
     *
     * @return string
     */
    public function getStreetNum()
    {
        return $this->streetNum;
    }

    /**
     * Set $streetNum
     *
     * @param string $streetNum
     *
     * @return User
     */
    public function setStreetNum($streetNum)
    {
        $this->streetNum = $streetNum;

        return $this;
    }

    /**
     * Get $address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set $address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get $address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set $address2
     *
     * @param string $address2
     *
     * @return User
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get $town
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set $town
     *
     * @param string $town
     *
     * @return User
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get $zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set $zipCode
     *
     * @param string $zipCode
     *
     * @return User
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get $country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set $country
     *
     * @param string $country
     *
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get $phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set $phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get $mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set $mobile
     *
     * @param string $mobile
     *
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get $avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set $avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

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
     * @return User
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
     * @return User
     */
    public function setDtUpdate(\DateTime $dtUpdate = null)
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }

    /**
     * Get $preferedLang
     *
     * @return Lang
     */
    public function getPreferedLang()
    {
        return $this->preferedLang;
    }

    /**
     * Set $preferedLang
     *
     * @param Lang $preferedLang
     *
     * @return User
     */
    public function setPreferedLang(Lang $preferedLang = null)
    {
        $this->preferedLang = $preferedLang;

        return $this;
    }

    /**
     * Add $role
     *
     * @param Role $role
     *
     * @return User
     */
    public function addUserRole(Role $role)
    {
        $this->userRoles[] = $role;

        return $this;
    }

    /**
     * Remove $role
     *
     * @param Role $role
     *
     * @return User
     */
    public function removeUserRole(Role $role)
    {
        $this->userRoles->removeElement($role);

        return $this;
    }

    /**
     * Get $userRoles
     *
     * @return ArrayCollection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Set $userRoles
     *
     * @param Collection $userRoles
     *
     * @return User
     */
    public function setUserRoles(Collection $userRoles)
    {
        $this->userRoles = $userRoles;

        return $this;
    }

    /**
     * Add company
     *
     * @param Company $company
     *
     * @return User
     */
    public function addCompany(Company $company)
    {
        $this->companies[] = $company;
        $company->addUser($this);

        return $this;
    }

    /**
     * Remove company
     *
     * @param Company $company
     *
     * @return User
     */
    public function removeCompany(Company $company)
    {
        $this->companies->removeElement($company);
        $company->removeUser($this);

        return $this;
    }

    /**
     * Get companies
     *
     * @return ArrayCollection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     *
     * @param Collection $companies
     *
     * @return User
     */
    public function setCompanies(Collection $companies)
    {
        $this->companies = $companies;

        return $this;
    }

    /**
     * Add companyUser
     *
     * @param CompanyUser $companyUser
     *
     * @return User
     */
    public function addCompanyUser(CompanyUser $companyUser)
    {
        $this->companyUsers[] = $companyUser;

        return $this;
    }

    /**
     * Remove companyUser
     *
     * @param CompanyUser $companyUser
     *
     * @return User
     */
    public function removeCompanyUser(CompanyUser $companyUser)
    {
        $this->companyUsers->removeElement($companyUser);

        return $this;
    }

    /**
     * Get companyUsers
     *
     * @return ArrayCollection
     */
    public function getCompanyUsers()
    {
        return $this->companyUsers;
    }

    /**
     *
     * @param Collection $companyUsers
     *
     * @return User
     */
    public function setCompanyUsers(Collection $companyUsers)
    {
        $this->companyUsers = $companyUsers;

        return $this;
    }

    /**
     * Add company
     *
     * @param Company $company
     *
     * @return User
     */
    public function addAdmCompany(Company $company)
    {
        $this->admCompanies[] = $company;
        $company->addAdmin($this);

        return $this;
    }

    /**
     * Remove company
     *
     * @param Company $company
     *
     * @return User
     */
    public function removeAdmCompany(Company $company)
    {
        $this->admCompanies->removeElement($company);
        $company->removeAdmin($this);

        return $this;
    }

    /**
     * Get companies
     *
     * @return ArrayCollection
     */
    public function getAdmCompanies()
    {
        return $this->admCompanies;
    }

    /**
     *
     * @param Collection $admCompanies
     *
     * @return User
     */
    public function setAdmCompanies(Collection $admCompanies)
    {
        $this->admCompanies = $admCompanies;

        return $this;
    }

    /**
     * Add companyAdmin
     *
     * @param CompanyAdmin $companyAdmin
     *
     * @return User
     */
    public function addCompanyAdmin(CompanyAdmin $companyAdmin)
    {
        $this->companyAdmins[] = $companyAdmin;

        return $this;
    }

    /**
     * Remove companyAdmin
     *
     * @param CompanyAdmin $companyAdmin
     *
     * @return User
     */
    public function removeCompanyAdmin(CompanyAdmin $companyAdmin)
    {
        $this->companyAdmins->removeElement($companyAdmin);

        return $this;
    }

    /**
     * Get companyAdmins
     *
     * @return ArrayCollection
     */
    public function getCompanyAdmins()
    {
        return $this->companyAdmins;
    }

    /**
     *
     * @param Collection $companyAdmins
     *
     * @return User
     */
    public function setCompanyAdmins(Collection $companyAdmins)
    {
        $this->companyAdmins = $companyAdmins;

        return $this;
    }

    /**
     * Add event
     *
     * @param Agenda $event
     *
     * @return User
     */
    public function addEvent(Agenda $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param Agenda $event
     *
     * @return User
     */
    public function removeEvent(Agenda $event)
    {
        $this->events->removeElement($event);

        return $this;
    }

    /**
     * Get events
     *
     * @return ArrayCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     *
     * @param Collection $events
     *
     * @return User
     */
    public function setEvents(Collection $events)
    {
        $this->events = $events;

        return $this;
    }

    /**
     * Add event
     *
     * @param Agenda $event
     *
     * @return User
     */
    public function addSharedEvent(Agenda $event)
    {
        $this->sharedEvents[] = $event;
        $event->addUser($this);

        return $this;
    }

    /**
     * Remove event
     *
     * @param Agenda $event
     *
     * @return User
     */
    public function removeSharedEvent(Agenda $event)
    {
        $this->sharedEvents->removeElement($event);
        $event->removeUser($this);

        return $this;
    }

    /**
     * Get events
     *
     * @return ArrayCollection
     */
    public function getSharedEvents()
    {
        return $this->sharedEvents;
    }

    /**
     *
     * @param Collection $sharedEvents
     *
     * @return User
     */
    public function setSharedEvents(Collection $sharedEvents)
    {
        $this->sharedEvents = $sharedEvents;

        return $this;
    }

    /**
     * Add notif
     *
     * @param Notification $notif
     *
     * @return User
     */
    public function addNotif(Notification $notif)
    {
        $this->notifs[] = $notif;

        return $this;
    }

    /**
     * Remove notif
     *
     * @param Notification $notif
     *
     * @return User
     */
    public function removeNotif(Notification $notif)
    {
        $this->notifs->removeElement($notif);

        return $this;
    }

    /**
     * Get notifs
     *
     * @return ArrayCollection
     */
    public function getNotifs()
    {
        return $this->notifs;
    }

    /**
     *
     * @param Collection $notifs
     *
     * @return User
     */
    public function setNotifs(Collection $notifs)
    {
        $this->notifs = $notifs;

        return $this;
    }

    /**
     * Add notif
     *
     * @param Notification $notif
     *
     * @return User
     */
    public function addSharedNotif(Notification $notif)
    {
        $this->sharedNotifs[] = $notif;
        $notif->addUser($this);

        return $this;
    }

    /**
     * Remove notif
     *
     * @param Notification $notif
     *
     * @return User
     */
    public function removeSharedNotif(Notification $notif)
    {
        $this->sharedNotifs->removeElement($notif);
        $notif->removeUser($this);

        return $this;
    }

    /**
     * Get notifs
     *
     * @return ArrayCollection
     */
    public function getSharedNotifs()
    {
        return $this->sharedNotifs;
    }

    /**
     *
     * @param Collection $sharedNotifs
     *
     * @return User
     */
    public function setSharedNotifs(Collection $sharedNotifs)
    {
        $this->sharedNotifs = $sharedNotifs;

        return $this;
    }

    /**
     * Add order
     *
     * @param OnlineOrder $order
     *
     * @return User
     */
    public function addOrder(OnlineOrder $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param OnlineOrder $order
     *
     * @return User
     */
    public function removeOrder(OnlineOrder $order)
    {
        $this->orders->removeElement($order);

        return $this;
    }

    /**
     * Get orders
     *
     * @return ArrayCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     *
     * @param Collection $orders
     *
     * @return User
     */
    public function setOrders(Collection $orders)
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     *
     * @param Role $role
     *
     * @return array
     */
    private function getRolesParentsNames(Role $role)
    {
        $roles = array();
        if ($role->getParents()) {
            foreach ($role->getParents() as $parent) {
                $roles = \array_merge($roles, $this->getRolesParentsNames($parent));
            }
        }
        $roles[] = $role->getName();

        return $roles;
    }

    /**
     *
     * {@inheritdoc} @see UserInterface::getRoles()
     */
    public function getRoles()
    {
        $roles = array();
        foreach ($this->userRoles as $role) {
            $roles[] = $role->getName();
            if ($role->getParents()) {
                foreach ($role->getParents() as $parent) {
                    $roles = \array_merge($roles, $this->getRolesParentsNames($parent));
                }
            }
        }

        return $roles;
        // return $this
        // ->userRoles
        // ->toArray()
    }

    /**
     * Get calculated fullName From username, firstName and lastName
     *
     * @return string
     */
    public function getFullName()
    {
        if (null == $this->getFirstName() && null == $this->getLastName()) {
            return $this->getUsername();
        } elseif (null != $this->getFirstName() && null != $this->getLastName()) {
            return $this->getFirstName() . ' ' . $this->getLastName();
        } else {
            if (null != $this->getLastName()) {
                return $this->getLastName();
            }
            if (null != $this->getFirstName()) {
                return $this->getFirstName();
            }
        }
    }

    /**
     * Set the lastActivity to now
     *
     * @return User
     */
    public function isActiveNow()
    {
        return $this->setLastActivity(new \DateTime());
    }

    /**
     * Erases the user credentials.
     *
     * {@inheritdoc} @see UserInterface::eraseCredentials()
     */
    public function eraseCredentials()
    {
        // $this->clearPassword = null;
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
            $this->password,
            $this->salt,
            $this->username,
            $this->email,
            $this->lockout,
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

        list ($this->password, $this->salt, $this->username, $this->email, $this->lockout, $this->id) = $data;
    }

    /**
     * Choice Form lockout
     *
     * @return multitype:string
     */
    public static function choiceLockout()
    {
        return array(
            'User.lockout.choice.' . self::LOCKOUT_UNLOCKED => self::LOCKOUT_UNLOCKED,
            'User.lockout.choice.' . self::LOCKOUT_LOCKED => self::LOCKOUT_LOCKED
        );
    }

    /**
     * Choice Validator lockout
     *
     * @return multitype:string
     */
    public static function choiceLockoutCallback()
    {
        return array(
            self::LOCKOUT_UNLOCKED,
            self::LOCKOUT_LOCKED
        );
    }

    /**
     * Choice Form sexe
     *
     * @return multitype:string
     */
    public static function choiceSexe()
    {
        return array(
            'User.sexe.choice.' . self::SEXE_MISS => self::SEXE_MISS,
            'User.sexe.choice.' . self::SEXE_MRS => self::SEXE_MRS,
            'User.sexe.choice.' . self::SEXE_MR => self::SEXE_MR
        );
    }

    /**
     * Choice Validator sexe
     *
     * @return multitype:integer
     */
    public static function choiceSexeCallback()
    {
        return array(
            self::SEXE_MISS,
            self::SEXE_MRS,
            self::SEXE_MR
        );
    }

    /**
     * Get a random char (for generated password)
     *
     * @param integer $length
     * @param string $charset
     *
     * @return string
     */
    public static function generateRandomChar($length, $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789#@!?+=*/-')
    {
        $str = '';
        $count = strlen($charset);
        while ($length--) {
            $str .= $charset[mt_rand(0, $count - 1)];
        }

        return $str;
    }

    /**
     * string representation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getFullName();
    }

    /**
     */
    public function __clone()
    {
        if ($this->id) {
            $roles = $this->getUserRoles();
            $this->userRoles = new ArrayCollection();
            foreach ($roles as $role) {
                $cloneRole = clone $role;
                $this->userRoles->add($cloneRole);
            }
        }
    }
}
