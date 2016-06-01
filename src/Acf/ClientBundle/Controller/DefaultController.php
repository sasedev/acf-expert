<?php
namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Buy;
use Acf\DataBundle\Entity\Sale;
use Acf\DataBundle\Entity\SecondaryVat;
use Acf\DataBundle\Entity\CompanyNature;
use Acf\DataBundle\Entity\Stock;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DefaultController extends BaseController
{

    /**
     *
     * @var array
     */
    protected $gvars = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gvars['menu_active'] = 'clienthome';
    }

    /**
     *
     * @param integer $month
     * @param integer $year
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($month = null, $year = null)
    {
        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();
        $em = $this->getEntityManager();

        $now = new \DateTime('now');
        $currentDay = $now->format('d');
        $currentMonth = $now->format('m');
        $currentMonthsz = $now->format('n');
        $currentYear = $now->format('Y');
        $prevYear = $currentYear - 1;
        $prevPrevYear = $prevYear - 1;

        if (null == $month) {
            $month = $currentMonthsz;
        }
        if (null == $year) {
            $year = $currentYear;
        }
        if ($year > $currentYear || ($year >= $currentYear && $month > $currentMonth)) {
            $year = $currentYear;
            $month = $currentMonthsz;
        }
        $lnkNextMonth = null;
        $lnkNextYear = null;
        $lnkPrevMonth = $month - 1;
        $lnkPrevYear = $year;

        if ($year < $currentYear || $month < $currentMonth) {
            $lnkNextMonth = $month + 1;
            if ($lnkNextMonth > 12) {
                $lnkNextMonth = 1;
                $lnkNextYear = $year + 1;
            } else {
                $lnkNextYear = $year;
            }
        }

        if ($lnkPrevMonth <= 0) {
            $lnkPrevMonth = 12;
            $lnkPrevYear = $year - 1;
        }

        $this->gvars['lnk_next_month'] = $lnkNextMonth;
        $this->gvars['lnk_next_year'] = $lnkNextYear;
        $this->gvars['lnk_prev_month'] = $lnkPrevMonth;
        $this->gvars['lnk_prev_year'] = $lnkPrevYear;
        $this->gvars['year'] = $year;
        if ($month < 10) {
            $this->gvars['month'] = '0' . $month;
            $testDate = $year . '-0' . $month . '-01';
            $lastDay = date('t', strtotime($testDate));
            $this->gvars['last_day'] = $lastDay;
        } else {
            $this->gvars['month'] = $month;
            $testDate = $year . '-' . $month . '-01';
            $lastDay = date('t', strtotime($testDate));
            $this->gvars['last_day'] = $lastDay;
        }

        $this->gvars['current_day'] = $currentDay;
        $this->gvars['current_month'] = $currentMonth;
        $this->gvars['current_monthsz'] = $currentMonthsz;
        $this->gvars['current_year'] = $currentYear;
        $this->gvars['prev_year'] = $prevYear;
        $this->gvars['prev_prev_year'] = $prevPrevYear;

        $companies = $user->getCompanies();
        $renderCompanies = array();

        foreach ($companies as $company) {
            $renderCompany = array();
            $renderCompany['company'] = $company;

            // stock de l'année en cours
            $yearStock = $em->getRepository('AcfDataBundle:Stock')->findOneBy(array(
                'company' => $company,
                'year' => $year
            ));
            if (null == $yearStock) {
                $yearStock = new Stock();
                $yearStock->setCompany($company);
                $yearStock->setYear($year);
                $yearStock->setValue(0);
                $em->persist($yearStock);
                $em->flush();
            }
            $renderCompany['year_stock'] = $yearStock;

            // stock de l'année - 1
            $yearPrevStock = $em->getRepository('AcfDataBundle:Stock')->findOneBy(array(
                'company' => $company,
                'year' => $prevYear
            ));
            if (null == $yearPrevStock) {
                $yearPrevStock = new Stock();
                $yearPrevStock->setCompany($company);
                $yearPrevStock->setYear($prevYear);
                $yearPrevStock->setValue(0);
                $em->persist($yearPrevStock);
                $em->flush();
            }
            $renderCompany['year_prev_stock'] = $yearPrevStock;

            // stock de l'année - 2
            $yearPrevPrevStock = $em->getRepository('AcfDataBundle:Stock')->findOneBy(array(
                'company' => $company,
                'year' => $prevPrevYear
            ));
            if (null == $yearPrevPrevStock) {
                $yearPrevPrevStock = new Stock();
                $yearPrevPrevStock->setCompany($company);
                $yearPrevPrevStock->setYear($prevPrevYear);
                $yearPrevPrevStock->setValue(0);
                $em->persist($yearPrevPrevStock);
                $em->flush();
            }
            $renderCompany['year_prev_prev_stock'] = $yearPrevPrevStock;

            // chiffre d'affaire ht total du mois en cours
            $monthlyCaHt = $em->getRepository('AcfDataBundle:Sale')->caHtTotalByCompanyInYearMonth($company, $year, $month);
            $monthlyCaHt += $em->getRepository('AcfDataBundle:SecondaryVat')->caHtTotalByCompanyInYearMonth($company, $year, $month);
            $renderCompany['monthly_ca_ht'] = $monthlyCaHt;

            // chiffre d'affaire encaissé du mois en cours
            $monthlyCaEncNet = $em->getRepository('AcfDataBundle:Sale')->caEncByCompanyInYearMonth($company, $year, $month);
            $monthlyCaEncNet += $em->getRepository('AcfDataBundle:SecondaryVat')->caEncByCompanyInYearMonth($company, $year, $month);
            $renderCompany['monthly_ca_enc_net'] = $monthlyCaEncNet;

            // chiffre d'affaire non-encaissé du mois en cours
            $monthlyCaNotEncNet = $em->getRepository('AcfDataBundle:Sale')->caNotEncByCompanyInYearMonth($company, $year, $month);
            $monthlyCaNotEncNet += $em->getRepository('AcfDataBundle:SecondaryVat')->caNotEncByCompanyInYearMonth($company, $year, $month);
            $renderCompany['monthly_ca_not_enc_net'] = $monthlyCaNotEncNet;

            // achat ht du mois en cours
            $monthlyAchatHt = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyInYearMonth($company, $year, $month);
            $renderCompany['monthly_achat_ht'] = $monthlyAchatHt;

            // verification nature d'achat marchandise
            $natureAchatMarchandise = $em->getRepository('AcfDataBundle:CompanyNature')->findOneBy(array(
                'company' => $company,
                'label' => 'ACHATS DE MARCHANDISES'
            ));
            if (null == $natureAchatMarchandise) {
                $natureAchatMarchandise = new CompanyNature();
                $natureAchatMarchandise->setCompany($company);
                $natureAchatMarchandise->setLabel('ACHATS DE MARCHANDISES');
                $em->persist($natureAchatMarchandise);
                $em->flush();
            }
            $em->getRepository('AcfDataBundle:Buy')->updateCompanyNatureNullByCompany($company, $natureAchatMarchandise);
            $em->flush();

            // achat ht du mois en cours
            $monthlyAchatMarchandiseHt = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyNatureInYearMonth($natureAchatMarchandise, $year, $month);
            $renderCompany['monthly_achat_marchandise_ht'] = $monthlyAchatMarchandiseHt;

            // achat payés
            $monthlyAchatPayed = $em->getRepository('AcfDataBundle:Buy')->achatPayedByCompanyInYearMonth($company, $year, $month);
            $renderCompany['monthly_achat_payed'] = $monthlyAchatPayed;

            // achat payés
            $monthlyAchatNotPayed = $em->getRepository('AcfDataBundle:Buy')->achatNotPayedByCompanyInYearMonth($company, $year, $month);
            $renderCompany['monthly_achat_not_payed'] = $monthlyAchatNotPayed;

            // stockage des chiffre d'affaire ht
            $renderCahts = array();
            $renderCahts['year_prev'] = array();
            $renderCahts['year'] = array();

            $renderAchatsHts = array();
            $renderAchatsHts['year_prev'] = array();
            $renderAchatsHts['year'] = array();

            // cumulé année précédente
            $yearPrev = $year - 1;
            $this->gvars['year_prev'] = $yearPrev;
            $yearPrevPrev = $yearPrev - 1;
            $this->gvars['year_prev_prev'] = $yearPrevPrev;

            $yearPrevCaHt = 0;
            $yearPrevCaEncNet = 0;
            $yearPrevCaNotEncNet = 0;
            $yearPrevAchatHt = 0;
            $yearPrevAchatMarchandiseHt = 0;
            $yearPrevAchatPayed = 0;
            $yearPrevAchatNotPayed = 0;

            for ($i = 1; $i <= 12; $i++) {
                // chiffre d'affaire ht total du mois en cours
                $caHt = $em->getRepository('AcfDataBundle:Sale')->caHtTotalByCompanyInYearMonth($company, $yearPrev, $i);
                $caHt += $em->getRepository('AcfDataBundle:SecondaryVat')->caHtTotalByCompanyInYearMonth($company, $yearPrev, $i);
                $yearPrevCaHt += $caHt;
                $renderCahts['year_prev']['m' . $i] = $caHt;

                // chiffre d'affaire encaissé du mois en cours
                $caEncNet = $em->getRepository('AcfDataBundle:Sale')->caEncByCompanyInYearMonth($company, $yearPrev, $i);
                $caEncNet += $em->getRepository('AcfDataBundle:SecondaryVat')->caEncByCompanyInYearMonth($company, $yearPrev, $i);
                $yearPrevCaEncNet += $caEncNet;

                // chiffre d'affaire non-encaissé du mois en cours
                $caNotEncNet = $em->getRepository('AcfDataBundle:Sale')->caNotEncByCompanyInYearMonth($company, $yearPrev, $i);
                $caNotEncNet += $em->getRepository('AcfDataBundle:SecondaryVat')->caNotEncByCompanyInYearMonth($company, $yearPrev, $i);
                $yearPrevCaNotEncNet += $caNotEncNet;

                // achat ht du mois en cours
                $achatHt = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyInYearMonth($company, $yearPrev, $i);
                $yearPrevAchatHt += $achatHt;
                $renderAchatsHts['year_prev']['m' . $i] = $achatHt;

                // achat ht du mois en cours
                $achatMarchandiseHt = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyNatureInYearMonth($natureAchatMarchandise, $yearPrev, $i);
                $yearPrevAchatMarchandiseHt += $achatMarchandiseHt;

                // achat payés
                $achatPayed = $em->getRepository('AcfDataBundle:Buy')->achatPayedByCompanyInYearMonth($company, $yearPrev, $i);
                $yearPrevAchatPayed += $achatPayed;

                // achat payés
                $achatNotPayed = $em->getRepository('AcfDataBundle:Buy')->achatNotPayedByCompanyInYearMonth($company, $yearPrev, $i);
                $yearPrevAchatNotPayed += $achatNotPayed;
            }
            $renderCompany['year_prev_ca_ht'] = $yearPrevCaHt;
            $renderCompany['year_prev_ca_enc_net'] = $yearPrevCaEncNet;
            $renderCompany['year_prev_ca_not_enc_net'] = $yearPrevCaNotEncNet;
            $renderCompany['year_prev_achat_ht'] = $yearPrevAchatHt;
            $renderCompany['year_prev_achat_marchandise_ht'] = $yearPrevAchatMarchandiseHt;
            $renderCompany['year_prev_achat_payed'] = $yearPrevAchatPayed;
            $renderCompany['year_prev_achat_not_payed'] = $yearPrevAchatNotPayed;

            $yearCaHt = 0;
            $yearCaEncNet = 0;
            $yearCaNotEncNet = 0;
            $yearAchatHt = 0;
            $yearAchatMarchandiseHt = 0;
            $yearAchatPayed = 0;
            $yearAchatNotPayed = 0;
            for ($i = 1; $i <= 12; $i++) {
                if ($i <= $month) {
                    // chiffre d'affaire ht total du mois en cours
                    $caHt = $em->getRepository('AcfDataBundle:Sale')->caHtTotalByCompanyInYearMonth($company, $year, $i);
                    $caHt += $em->getRepository('AcfDataBundle:SecondaryVat')->caHtTotalByCompanyInYearMonth($company, $year, $i);
                    $yearCaHt += $caHt;
                    $renderCahts['year']['m' . $i] = $caHt;

                    // chiffre d'affaire encaissé du mois en cours
                    $caEncNet = $em->getRepository('AcfDataBundle:Sale')->caEncByCompanyInYearMonth($company, $year, $i);
                    $caEncNet += $em->getRepository('AcfDataBundle:SecondaryVat')->caEncByCompanyInYearMonth($company, $year, $i);
                    $yearCaEncNet += $caEncNet;

                    // chiffre d'affaire non-encaissé du mois en cours
                    $caNotEncNet = $em->getRepository('AcfDataBundle:Sale')->caNotEncByCompanyInYearMonth($company, $year, $i);
                    $caNotEncNet += $em->getRepository('AcfDataBundle:SecondaryVat')->caNotEncByCompanyInYearMonth($company, $year, $i);
                    $yearCaNotEncNet += $caNotEncNet;

                    // achat ht du mois en cours
                    $achatHt = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyInYearMonth($company, $year, $i);
                    $yearAchatHt += $achatHt;
                    $renderAchatsHts['year']['m' . $i] = $achatHt;

                    // achat ht du mois en cours
                    $achatMarchandiseHt = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyNatureInYearMonth($natureAchatMarchandise, $year, $i);
                    $yearAchatMarchandiseHt += $achatMarchandiseHt;

                    // achat payés
                    $achatPayed = $em->getRepository('AcfDataBundle:Buy')->achatPayedByCompanyInYearMonth($company, $year, $i);
                    $yearAchatPayed += $achatPayed;

                    // achat payés
                    $achatNotPayed = $em->getRepository('AcfDataBundle:Buy')->achatNotPayedByCompanyInYearMonth($company, $year, $i);
                    $yearAchatNotPayed += $achatNotPayed;
                } else {
                    $renderCahts['year']['m' . $i] = 0;
                    $renderAchatsHts['year']['m' . $i] = 0;
                }
            }
            $renderCompany['year_ca_ht'] = $yearCaHt;
            $renderCompany['year_ca_enc_net'] = $yearCaEncNet;
            $renderCompany['year_ca_not_enc_net'] = $yearCaNotEncNet;
            $renderCompany['year_achat_ht'] = $yearAchatHt;
            $renderCompany['year_achat_marchandise_ht'] = $yearAchatMarchandiseHt;
            $renderCompany['year_achat_payed'] = $yearAchatPayed;
            $renderCompany['year_achat_not_payed'] = $yearAchatNotPayed;

            $renderCompany['cahts'] = $renderCahts;
            $renderCompany['achathts'] = $renderAchatsHts;

            $renderNatures = array();
            $companyNatures = $em->getRepository('AcfDataBundle:CompanyNature')->getAllByCompany($company);
            foreach ($companyNatures as $companyNature) {
                $renderNature = array();
                $val = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyNatureInYear($companyNature, $year);
                if (null != $val && $val != 0) {
                    $renderNature['nature'] = $companyNature;
                    $renderNature['val'] = $val;
                    $renderNatures[] = $renderNature;
                }
            }
            $renderCompany['nature_list'] = $renderNatures;

            $renderCompanies[] = $renderCompany;
        }

        $this->gvars['companies'] = $renderCompanies;

        return $this->renderResponse('AcfClientBundle:Default:index.html.twig', $this->gvars);
    }
}
