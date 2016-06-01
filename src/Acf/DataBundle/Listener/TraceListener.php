<?php
namespace Acf\DataBundle\Listener;

use Acf\DataBundle\Entity\Trace;
use Acf\DataBundle\Entity\User;
use Acf\DataBundle\Entity\Job;
use Acf\DataBundle\Entity\CompanyType;
use Acf\DataBundle\Entity\Sector;
use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Entity\Address;
use Acf\DataBundle\Entity\Phone;
use Acf\DataBundle\Entity\CompanyFrame;
use Acf\DataBundle\Entity\CompanyNature;
use Acf\DataBundle\Entity\CompanyLabel;
use Acf\DataBundle\Entity\Customer;
use Acf\DataBundle\Entity\Supplier;
use Acf\DataBundle\Entity\Bank;
use Acf\DataBundle\Entity\Fund;
use Acf\DataBundle\Entity\Sale;
use Acf\DataBundle\Entity\Withholding;
use Acf\DataBundle\Entity\Buy;
use Acf\DataBundle\Entity\SecondaryVat;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\Docgroupbank;
use Acf\DataBundle\Entity\Docgroupcomptable;
use Acf\DataBundle\Entity\Docgroup;
use Acf\DataBundle\Entity\Docgroupfiscal;
use Acf\DataBundle\Entity\Docgroupperso;
use Acf\DataBundle\Entity\Docgroupsyst;
use Acf\DataBundle\Entity\Docgroupaudit;
use Acf\DataBundle\Entity\MBSale;
use Acf\DataBundle\Entity\MBPurchase;
use Acf\DataBundle\Entity\Shareholder;
use Acf\DataBundle\Entity\Pilot;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\CompanyAdmin;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Acf\DataBundle\Entity\Stock;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class TraceListener implements EventSubscriber
{

    /**
     *
     * @var Container
     */
    private $container;

    /**
     *
     * @var User
     */
    private $user;

    /**
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postRemove'
        );
    }

    /**
     *
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        $trans = $this->container->get('translator');

        $trace = $this->initTrace();
        $trace->setActionType(Trace::AT_CREATE);
        $trace->setActionId($entity->getId());

        if ($entity instanceof User) {
            $trace->setActionEntity(Trace::AE_USER);
            $trace->setMsg($trans->trans('User.traceNew', array(
                '%user%' => $entity->getFullName()
            )));
            $this->persist($trace, $em);
        } elseif ($entity instanceof Job) {
            $trace->setActionEntity(Trace::AE_JOB);
            $trace->setMsg($trans->trans('Job.traceNew', array(
                '%job%' => $entity->getLabel()
            )));
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyType) {
            $trace->setActionEntity(Trace::AE_TYPE);
            $trace->setMsg($trans->trans('CompanyType.traceNew', array(
                '%companyType%' => $entity->getLabel()
            )));
            $this->persist($trace, $em);
        } elseif ($entity instanceof Sector) {
            $trace->setActionEntity(Trace::AE_SECTOR);
            $trace->setMsg($trans->trans('Sector.traceNew', array(
                '%sector%' => $entity->getLabel()
            )));
            $this->persist($trace, $em);
        } elseif ($entity instanceof Company) {
            $trace->setActionEntity(Trace::AE_COMPANY);
            $trace->setMsg($trans->trans('Company.traceNew', array(
                '%company%' => $entity->getCorporateName()
            )));
            $this->persist($trace, $em);
        } elseif ($entity instanceof Stock) {
            $trace->setActionEntity(Trace::AE_STOCK);
            $trace->setMsg($trans->trans('Stock.traceNew', array(
                '%stock%' => $entity->getYear(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Address) {
            $trace->setActionEntity(Trace::AE_ADDRESS);
            $trace->setMsg($trans->trans('Address.traceNew', array(
                '%address%' => $entity->getFullAddress(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Phone) {
            $trace->setActionEntity(Trace::AE_PHONE);
            $trace->setMsg($trans->trans('Phone.traceNew', array(
                '%phone%' => $entity->getPhone(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyFrame) {
            $trace->setActionEntity(Trace::AE_FRAME);
            $trace->setMsg($trans->trans('CompanyFrame.traceNew', array(
                '%companyFrame%' => $entity->getFullName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyNature) {
            $trace->setActionEntity(Trace::AE_NATURE);
            $trace->setMsg($trans->trans('CompanyNature.traceNew', array(
                '%companyNature%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyLabel) {
            $trace->setActionEntity(Trace::AE_LABEL);
            $trace->setMsg($trans->trans('CompanyLabel.traceNew', array(
                '%companyLabel%' => $entity->getName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Customer) {
            $trace->setActionEntity(Trace::AE_CUSTOMER);
            $trace->setMsg($trans->trans('Customer.traceNew', array(
                '%customer%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Supplier) {
            $trace->setActionEntity(Trace::AE_SUPPLIER);
            $trace->setMsg($trans->trans('Supplier.traceNew', array(
                '%supplier%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Bank) {
            $trace->setActionEntity(Trace::AE_BANK);
            $trace->setMsg($trans->trans('Bank.traceNew', array(
                '%bank%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Fund) {
            $trace->setActionEntity(Trace::AE_FUND);
            $trace->setMsg($trans->trans('Fund.traceNew', array(
                '%fund%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Withholding) {
            $trace->setActionEntity(Trace::AE_WHITHHOLDING);
            $trace->setMsg($trans->trans('Withholding.traceNew', array(
                '%withholding%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof MBSale) {
            $trace->setActionEntity(Trace::AE_MBSALE);
            $trace->setMsg($trans->trans('MBSale.traceNew', array(
                '%mbsale%' => $entity->getRef(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof MBPurchase) {
            $trace->setActionEntity(Trace::AE_MBPURCHASE);
            $trace->setMsg($trans->trans('MBPurchase.traceNew', array(
                '%mbpurchase%' => $entity->getRef(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Sale) {
            $trace->setActionEntity(Trace::AE_SALE);
            $trace->setMsg($trans->trans('Sale.traceNew', array(
                '%sale%' => $entity->getLabel(),
                '%mbsale%' => $entity->getMonthlyBalance()
                    ->getRef(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $trace->setActionEntity3(Trace::AE_MBSALE);
            $trace->setActionId3($entity->getMonthlyBalance()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Buy) {
            $trace->setActionEntity(Trace::AE_BUY);
            $trace->setMsg($trans->trans('Buy.traceNew', array(
                '%buy%' => $entity->getLabel(),
                '%mbpurchase%' => $entity->getMonthlyBalance()
                    ->getRef(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $trace->setActionEntity3(Trace::AE_MBPURCHASE);
            $trace->setActionId3($entity->getMonthlyBalance()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof SecondaryVat) {
            $trace->setActionEntity(Trace::AE_SECONDARYVAT);
            $trace->setMsg($trans->trans('SecondaryVat.traceNew', array(
                '%secondaryVat%' => $trans->trans('SecondaryVat.vatInfo.' . $entity->getVatInfo()),
                '%sale%' => $entity->getSale()
                    ->getLabel(),
                '%mbsale%' => $entity->getSale()
                    ->getMonthlyBalance()
                    ->getRef(),
                '%company%' => $entity->getSale()
                    ->getMonthlyBalance()
                    ->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $trace->setActionEntity3(Trace::AE_MBSALE);
            $trace->setActionId3($entity->getMonthlyBalance()->getId());
            $trace->setActionEntity4(Trace::AE_SALE);
            $trace->setActionId4($entity->getSale()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Shareholder) {
            $trace->setActionEntity(Trace::AE_SHAREHOLDER);
            $trace->setMsg($trans->trans('Shareholder.traceNew', array(
                '%shareholder%' => $entity->getName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Pilot) {
            $trace->setActionEntity(Trace::AE_PILOT);
            $trace->setMsg($trans->trans('Pilot.traceNew', array(
                '%pilot%' => $entity->getRef(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyUser) {
            $trace->setActionEntity(Trace::AE_CUSER);
            $trace->setMsg($trans->trans('CompanyUser.traceNew', array(
                '%user%' => $entity->getUser()
                    ->getFullName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyAdmin) {
            $trace->setActionEntity(Trace::AE_CADMIN);
            $trace->setMsg($trans->trans('CompanyAdmin.traceNew', array(
                '%user%' => $entity->getUser()
                    ->getFullName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Doc) {
            $trace->setActionEntity(Trace::AE_DOC);
            $trace->setMsg($trans->trans('Doc.traceNew', array(
                '%doc%' => $entity->getOriginalName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupcomptable) {
            $trace->setActionEntity(Trace::AE_DOCGROUPCOMPTABLE);
            $trace->setMsg($trans->trans('Docgroupcomptable.traceNew', array(
                '%docgroupcomptable%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupbank) {
            $trace->setActionEntity(Trace::AE_DOCGROUPBANK);
            $trace->setMsg($trans->trans('Docgroupbank.traceNew', array(
                '%docgroupbank%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroup) {
            $trace->setActionEntity(Trace::AE_DOCGROUP);
            $trace->setMsg($trans->trans('Docgroup.traceNew', array(
                '%docgroup%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupfiscal) {
            $trace->setActionEntity(Trace::AE_DOCGROUPFISCAL);
            $trace->setMsg($trans->trans('Docgroupfiscal.traceNew', array(
                '%docgroupfiscal%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupperso) {
            $trace->setActionEntity(Trace::AE_DOCGROUPPERSO);
            $trace->setMsg($trans->trans('Docgroupperso.traceNew', array(
                '%docgroupperso%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupsyst) {
            $trace->setActionEntity(Trace::AE_DOCGROUPSYST);
            $trace->setMsg($trans->trans('Docgroupsyst.traceNew', array(
                '%docgroupsyst%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupaudit) {
            $trace->setActionEntity(Trace::AE_DOCGROUPAUDIT);
            $trace->setMsg($trans->trans('Docgroupaudit.traceNew', array(
                '%docgroupaudit%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        }
    }

    /**
     *
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        $trans = $this->container->get('translator');

        $trace = $this->initTrace();
        $trace->setActionType(Trace::AT_DELETE);

        if ($entity instanceof User) {
            $trace->setActionEntity(Trace::AE_USER);
            $trace->setMsg($trans->trans('User.traceDel', array(
                '%user%' => $entity->getFullName()
            )));
            $this->persist($trace, $em);
        } elseif ($entity instanceof Job) {
            $trace->setActionEntity(Trace::AE_JOB);
            $trace->setMsg($trans->trans('Job.traceDel', array(
                '%job%' => $entity->getLabel()
            )));
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyType) {
            $trace->setActionEntity(Trace::AE_TYPE);
            $trace->setMsg($trans->trans('CompanyType.traceDel', array(
                '%companyType%' => $entity->getLabel()
            )));
            $this->persist($trace, $em);
        } elseif ($entity instanceof Sector) {
            $trace->setActionEntity(Trace::AE_SECTOR);
            $trace->setMsg($trans->trans('Sector.traceDel', array(
                '%sector%' => $entity->getLabel()
            )));
            $this->persist($trace, $em);
        } elseif ($entity instanceof Company) {
            $trace->setActionEntity(Trace::AE_COMPANY);
            $trace->setMsg($trans->trans('Company.traceDel', array(
                '%company%' => $entity->getCorporateName()
            )));
            $this->persist($trace, $em);
        } elseif ($entity instanceof Stock) {
            $trace->setActionEntity(Trace::AE_STOCK);
            $trace->setMsg($trans->trans('Stock.traceDel', array(
                '%stock%' => $entity->getYear(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Address) {
            $trace->setActionEntity(Trace::AE_ADDRESS);
            $trace->setMsg($trans->trans('Address.traceDel', array(
                '%address%' => $entity->getFullAddress(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Phone) {
            $trace->setActionEntity(Trace::AE_PHONE);
            $trace->setMsg($trans->trans('Phone.traceDel', array(
                '%phone%' => $entity->getPhone(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyFrame) {
            $trace->setActionEntity(Trace::AE_FRAME);
            $trace->setMsg($trans->trans('CompanyFrame.traceDel', array(
                '%companyFrame%' => $entity->getFullName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyNature) {
            $trace->setActionEntity(Trace::AE_NATURE);
            $trace->setMsg($trans->trans('CompanyNature.traceDel', array(
                '%companyNature%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyLabel) {
            $trace->setActionEntity(Trace::AE_LABEL);
            $trace->setMsg($trans->trans('CompanyLabel.traceDel', array(
                '%companyLabel%' => $entity->getName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Customer) {
            $trace->setActionEntity(Trace::AE_CUSTOMER);
            $trace->setMsg($trans->trans('Customer.traceDel', array(
                '%customer%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Supplier) {
            $trace->setActionEntity(Trace::AE_SUPPLIER);
            $trace->setMsg($trans->trans('Supplier.traceDel', array(
                '%supplier%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Bank) {
            $trace->setActionEntity(Trace::AE_BANK);
            $trace->setMsg($trans->trans('Bank.traceDel', array(
                '%bank%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Fund) {
            $trace->setActionEntity(Trace::AE_FUND);
            $trace->setMsg($trans->trans('Fund.traceDel', array(
                '%fund%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Withholding) {
            $trace->setActionEntity(Trace::AE_WHITHHOLDING);
            $trace->setMsg($trans->trans('Withholding.traceDel', array(
                '%withholding%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof MBSale) {
            $trace->setActionEntity(Trace::AE_MBSALE);
            $trace->setMsg($trans->trans('MBSale.traceDel', array(
                '%mbsale%' => $entity->getRef(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof MBPurchase) {
            $trace->setActionEntity(Trace::AE_MBPURCHASE);
            $trace->setMsg($trans->trans('MBPurchase.traceDel', array(
                '%mbpurchase%' => $entity->getRef(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Sale) {
            $trace->setActionEntity(Trace::AE_SALE);
            $trace->setMsg($trans->trans('Sale.traceDel', array(
                '%sale%' => $entity->getLabel(),
                '%mbsale%' => $entity->getMonthlyBalance()
                    ->getRef(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $trace->setActionEntity3(Trace::AE_MBSALE);
            $trace->setActionId3($entity->getMonthlyBalance()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Buy) {
            $trace->setActionEntity(Trace::AE_BUY);
            $trace->setMsg($trans->trans('Buy.traceDel', array(
                '%buy%' => $entity->getLabel(),
                '%mbpurchase%' => $entity->getMonthlyBalance()
                    ->getRef(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $trace->setActionEntity3(Trace::AE_MBPURCHASE);
            $trace->setActionId3($entity->getMonthlyBalance()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof SecondaryVat) {
            $trace->setActionEntity(Trace::AE_SECONDARYVAT);
            $trace->setMsg($trans->trans('SecondaryVat.traceDel', array(
                '%secondaryVat%' => $trans->trans('SecondaryVat.vatInfo.' . $entity->getVatInfo()),
                '%sale%' => $entity->getSale()
                    ->getLabel(),
                '%mbsale%' => $entity->getSale()
                    ->getMonthlyBalance()
                    ->getRef(),
                '%company%' => $entity->getSale()
                    ->getMonthlyBalance()
                    ->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $trace->setActionEntity3(Trace::AE_MBSALE);
            $trace->setActionId3($entity->getMonthlyBalance()->getId());
            $trace->setActionEntity4(Trace::AE_SALE);
            $trace->setActionId4($entity->getSale()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Shareholder) {
            $trace->setActionEntity(Trace::AE_SHAREHOLDER);
            $trace->setMsg($trans->trans('Shareholder.traceDel', array(
                '%shareholder%' => $entity->getName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Pilot) {
            $trace->setActionEntity(Trace::AE_PILOT);
            $trace->setMsg($trans->trans('Pilot.traceDel', array(
                '%pilot%' => $entity->getRef(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyUser) {
            $trace->setActionEntity(Trace::AE_CUSER);
            $trace->setMsg($trans->trans('CompanyUser.traceDel', array(
                '%user%' => $entity->getUser()
                    ->getFullName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof CompanyAdmin) {
            $trace->setActionEntity(Trace::AE_CADMIN);
            $trace->setMsg($trans->trans('CompanyAdmin.traceDel', array(
                '%user%' => $entity->getUser()
                    ->getFullName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Doc) {
            $trace->setActionEntity(Trace::AE_DOC);
            $trace->setMsg($trans->trans('Doc.traceDel', array(
                '%doc%' => $entity->getOriginalName(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupcomptable) {
            $trace->setActionEntity(Trace::AE_DOCGROUPCOMPTABLE);
            $trace->setMsg($trans->trans('Docgroupcomptable.traceDel', array(
                '%docgroupcomptable%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupbank) {
            $trace->setActionEntity(Trace::AE_DOCGROUPBANK);
            $trace->setMsg($trans->trans('Docgroupbank.traceDel', array(
                '%docgroupbank%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroup) {
            $trace->setActionEntity(Trace::AE_DOCGROUP);
            $trace->setMsg($trans->trans('Docgroup.traceDel', array(
                '%docgroup%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupfiscal) {
            $trace->setActionEntity(Trace::AE_DOCGROUPFISCAL);
            $trace->setMsg($trans->trans('Docgroupfiscal.traceDel', array(
                '%docgroupfiscal%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupperso) {
            $trace->setActionEntity(Trace::AE_DOCGROUPPERSO);
            $trace->setMsg($trans->trans('Docgroupperso.traceDel', array(
                '%docgroupperso%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupsyst) {
            $trace->setActionEntity(Trace::AE_DOCGROUPSYST);
            $trace->setMsg($trans->trans('Docgroupsyst.traceDel', array(
                '%docgroupsyst%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        } elseif ($entity instanceof Docgroupaudit) {
            $trace->setActionEntity(Trace::AE_DOCGROUPAUDIT);
            $trace->setMsg($trans->trans('Docgroupaudit.traceDel', array(
                '%docgroupaudit%' => $entity->getLabel(),
                '%company%' => $entity->getCompany()
                    ->getCorporateName()
            )));
            $trace->setCompanyId($entity->getCompany()->getId());
            $trace->setActionEntity2(Trace::AE_COMPANY);
            $trace->setActionId2($entity->getCompany()->getId());
            $this->persist($trace, $em);
        }
    }

    /**
     *
     * @return Trace
     */
    private function initTrace()
    {
        $trace = new Trace();

        $tokenStorage = $this->container->get('security.token_storage');
        $authChecker = $this->container->get('security.authorization_checker');

        $this->user = $tokenStorage->getToken()->getUser();
        if ($this->user != null && $this->user instanceof User) {
            $trace->setUserId($this->user->getId());
            if ($authChecker->isGranted('ROLE_SUPERADMIN', $this->user)) {
                $trace->setUserType(Trace::UT_SUPERADMIN);
            } elseif ($authChecker->isGranted('ROLE_ADMIN', $this->user)) {
                $trace->setUserType(Trace::UT_ADMIN);
            } else {
                $trace->setUserType(Trace::UT_CLIENT);
            }
            $trace->setUserFullname($this->user->getFullName());
        } else {
            $trace->setUserType(Trace::UT_ANONYMOUS);
            $trace->setUserFullname('????????');
        }

        return $trace;
    }

    /**
     *
     * @param mixed $entity
     * @param EntityManager $em
     */
    private function persist($entity, EntityManager $em)
    {
        $em->persist($entity);
        $em->flush();
    }
}
