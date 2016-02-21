<?php

namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Buy;
use Acf\DataBundle\Entity\Sale;
use Acf\DataBundle\Entity\SecondaryVat;
use Acf\DataBundle\Entity\CompanyNature;

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

	public function indexAction($month = null, $year = null)
	{
		$sc = $this->getSecurityTokenStorage();
		$user = $sc->getToken()->getUser();
		$em = $this->getEntityManager();

		$now = new \DateTime('now');
		$current_day = $now->format('d');
		$current_month = $now->format('m');
		$current_monthsz = $now->format('n');
		$current_year = $now->format('Y');
		$prev_year = $current_year -1;

		if (null == $month) {
			$month = $current_monthsz;
		}
		if (null == $year) {
			$year = $current_year;
		}
		if ($year > $current_year || ($year >= $current_year && $month > $current_month)) {
			$year = $current_year;
			$month = $current_monthsz;
		}
		$lnk_next_month = null;
		$lnk_next_year = null;
		$lnk_prev_month = $month -1;
		$lnk_prev_year = $year;

		if ($year < $current_year || $month < $current_month) {
			$lnk_next_month = $month+1;
			if ($lnk_next_month > 12) {
				$lnk_next_month = 1;
				$lnk_next_year = $year +1;
			} else {
				$lnk_next_year = $year;
			}
		}

		if ($lnk_prev_month <= 0) {
			$lnk_prev_month = 12;
			$lnk_prev_year = $year -1;
		}

		$this->gvars['lnk_next_month'] = $lnk_next_month;
		$this->gvars['lnk_next_year'] = $lnk_next_year;
		$this->gvars['lnk_prev_month'] = $lnk_prev_month;
		$this->gvars['lnk_prev_year'] = $lnk_prev_year;
		$this->gvars['year'] = $year;
		if ($month < 10) {
			$this->gvars['month'] = '0'.$month;
			$test_date = $year."-0".$month."-01";
			$last_day =  date("t", strtotime($test_date));
			$this->gvars['last_day'] = $last_day;

		} else {
			$this->gvars['month'] = $month;
			$test_date = $year."-".$month."-01";
			$last_day =  date("t", strtotime($test_date));
			$this->gvars['last_day'] = $last_day;
		}



		$this->gvars['current_day'] = $current_day;
		$this->gvars['current_month'] = $current_month;
		$this->gvars['current_monthsz'] = $current_monthsz;
		$this->gvars['current_year'] = $current_year;
		$this->gvars['prev_year'] = $prev_year;

		$companies = $user->getCompanies();
		$render_companies = array();

		//$logger = $this->getLogger();

		foreach ($companies as $company) {
			$render_company = array();
			$render_company['company'] = $company;

			// chiffre d'affaire ht total du mois en cours
			$monthly_ca_ht = $em->getRepository('AcfDataBundle:Sale')->caHtTotalByCompanyInYearMonth($company, $year, $month);
			$monthly_ca_ht += $em->getRepository('AcfDataBundle:SecondaryVat')->caHtTotalByCompanyInYearMonth($company, $year, $month);
			$render_company['monthly_ca_ht'] = $monthly_ca_ht;

			// chiffre d'affaire encaissé du mois en cours
			$monthly_ca_enc_net = $em->getRepository('AcfDataBundle:Sale')->caEncByCompanyInYearMonth($company, $year, $month);
			$monthly_ca_enc_net += $em->getRepository('AcfDataBundle:SecondaryVat')->caEncByCompanyInYearMonth($company, $year, $month);
			$render_company['monthly_ca_enc_net'] = $monthly_ca_enc_net;

			// chiffre d'affaire non-encaissé du mois en cours
			$monthly_ca_not_enc_net = $em->getRepository('AcfDataBundle:Sale')->caNotEncByCompanyInYearMonth($company, $year, $month);
			$monthly_ca_not_enc_net += $em->getRepository('AcfDataBundle:SecondaryVat')->caNotEncByCompanyInYearMonth($company, $year, $month);
			$render_company['monthly_ca_not_enc_net'] = $monthly_ca_not_enc_net;

			// achat ht du mois en cours
			$monthly_achat_ht = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyInYearMonth($company, $year, $month);
			$render_company['monthly_achat_ht'] = $monthly_achat_ht;

			// verification nature d'achat marchandise
			$nature_achat_marchandise = $em->getRepository('AcfDataBundle:CompanyNature')->findOneBy(array('company' => $company, 'label' => 'ACHATS DE MARCHANDISES'));
			if (null == $nature_achat_marchandise) {
				$nature_achat_marchandise = new CompanyNature();
				$nature_achat_marchandise->setCompany($company);
				$nature_achat_marchandise->setLabel('ACHATS DE MARCHANDISES');
				$em->persist($nature_achat_marchandise);
				$em->flush();
			}
			$em->getRepository('AcfDataBundle:Buy')->updateCompanyNatureNullByCompany($company, $nature_achat_marchandise);
			$em->flush();

			// achat ht du mois en cours
			$monthly_achat_marchandise_ht = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyNatureInYearMonth($nature_achat_marchandise, $year, $month);
			$render_company['monthly_achat_marchandise_ht'] = $monthly_achat_marchandise_ht;

			// achat payés
			$monthly_achat_payed = $em->getRepository('AcfDataBundle:Buy')->achatPayedByCompanyInYearMonth($company, $year, $month);
			$render_company['monthly_achat_payed'] = $monthly_achat_payed;

			// achat payés
			$monthly_achat_not_payed = $em->getRepository('AcfDataBundle:Buy')->achatNotPayedByCompanyInYearMonth($company, $year, $month);
			$render_company['monthly_achat_not_payed'] = $monthly_achat_not_payed;


			// stockage des chiffre d'affaire ht
			$render_cahts = array();
			$render_cahts['year_prev'] = array();
			$render_cahts['year'] = array();


			$render_achats_hts = array();
			$render_achats_hts['year_prev'] = array();
			$render_achats_hts['year'] = array();

			// cumulé année précédente
			$year_prev = $year - 1;
			$this->gvars['year_prev'] = $year_prev;

			$year_prev_ca_ht = 0;
			$year_prev_ca_enc_net = 0;
			$year_prev_ca_not_enc_net = 0;
			$year_prev_achat_ht = 0;
			$year_prev_achat_marchandise_ht = 0;
			$year_prev_achat_payed = 0;
			$year_prev_achat_not_payed = 0;

			$logger = $this->getLogger();




			for ($i =1; $i<=12; $i++) {
				// chiffre d'affaire ht total du mois en cours
				$ca_ht = $em->getRepository('AcfDataBundle:Sale')->caHtTotalByCompanyInYearMonth($company, $year_prev, $i);
				$ca_ht += $em->getRepository('AcfDataBundle:SecondaryVat')->caHtTotalByCompanyInYearMonth($company, $year_prev, $i);
				$year_prev_ca_ht += $ca_ht;
				$render_cahts['year_prev']["m".$i] = $ca_ht;

				// chiffre d'affaire encaissé du mois en cours
				$ca_enc_net = $em->getRepository('AcfDataBundle:Sale')->caEncByCompanyInYearMonth($company, $year_prev, $i);
				$ca_enc_net += $em->getRepository('AcfDataBundle:SecondaryVat')->caEncByCompanyInYearMonth($company, $year_prev, $i);
				$year_prev_ca_enc_net += $ca_enc_net;

				// chiffre d'affaire non-encaissé du mois en cours
				$ca_not_enc_net = $em->getRepository('AcfDataBundle:Sale')->caNotEncByCompanyInYearMonth($company, $year_prev, $i);
				$ca_not_enc_net += $em->getRepository('AcfDataBundle:SecondaryVat')->caNotEncByCompanyInYearMonth($company, $year_prev, $i);
				$year_prev_ca_not_enc_net += $ca_not_enc_net;

				// achat ht du mois en cours
				$achat_ht = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyInYearMonth($company, $year_prev, $i);
				$year_prev_achat_ht += $achat_ht;
				$render_achats_hts['year_prev']["m".$i] = $achat_ht;

				$logger->addError('year_prev.m'.$i.' : '.$achat_ht);

				// achat ht du mois en cours
				$achat_marchandise_ht = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyNatureInYearMonth($nature_achat_marchandise, $year_prev, $i);
				$year_prev_achat_marchandise_ht += $achat_marchandise_ht;

				// achat payés
				$achat_payed = $em->getRepository('AcfDataBundle:Buy')->achatPayedByCompanyInYearMonth($company, $year_prev, $i);
				$year_prev_achat_payed += $achat_payed;

				// achat payés
				$achat_not_payed = $em->getRepository('AcfDataBundle:Buy')->achatNotPayedByCompanyInYearMonth($company, $year_prev, $i);
				$year_prev_achat_not_payed += $achat_not_payed;

			}
			$render_company['year_prev_ca_ht'] = $year_prev_ca_ht;
			$render_company['year_prev_ca_enc_net'] = $year_prev_ca_enc_net;
			$render_company['year_prev_ca_not_enc_net'] = $year_prev_ca_not_enc_net;
			$render_company['year_prev_achat_ht'] = $year_prev_achat_ht;
			$render_company['year_prev_achat_marchandise_ht'] = $year_prev_achat_marchandise_ht;
			$render_company['year_prev_achat_payed'] = $year_prev_achat_payed;
			$render_company['year_prev_achat_not_payed'] = $year_prev_achat_not_payed;

			$year_ca_ht = 0;
			$year_ca_enc_net = 0;
			$year_ca_not_enc_net = 0;
			$year_achat_ht = 0;
			$year_achat_marchandise_ht = 0;
			$year_achat_payed = 0;
			$year_achat_not_payed = 0;
			for ($i =1; $i<=12; $i++) {
				if ($i <= $month) {
					// chiffre d'affaire ht total du mois en cours
					$ca_ht = $em->getRepository('AcfDataBundle:Sale')->caHtTotalByCompanyInYearMonth($company, $year, $i);
					$ca_ht += $em->getRepository('AcfDataBundle:SecondaryVat')->caHtTotalByCompanyInYearMonth($company, $year, $i);
					$year_ca_ht += $ca_ht;
					$render_cahts['year']["m".$i] = $ca_ht;

					// chiffre d'affaire encaissé du mois en cours
					$ca_enc_net = $em->getRepository('AcfDataBundle:Sale')->caEncByCompanyInYearMonth($company, $year, $i);
					$ca_enc_net += $em->getRepository('AcfDataBundle:SecondaryVat')->caEncByCompanyInYearMonth($company, $year, $i);
					$year_ca_enc_net += $ca_enc_net;

					// chiffre d'affaire non-encaissé du mois en cours
					$ca_not_enc_net = $em->getRepository('AcfDataBundle:Sale')->caNotEncByCompanyInYearMonth($company, $year, $i);
					$ca_not_enc_net += $em->getRepository('AcfDataBundle:SecondaryVat')->caNotEncByCompanyInYearMonth($company, $year, $i);
					$year_ca_not_enc_net += $ca_not_enc_net;

					// achat ht du mois en cours
					$achat_ht = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyInYearMonth($company, $year, $i);
					$year_achat_ht += $achat_ht;
					$render_achats_hts['year']["m".$i] = $achat_ht;

					// achat ht du mois en cours
					$achat_marchandise_ht = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyNatureInYearMonth($nature_achat_marchandise, $year, $i);
					$year_achat_marchandise_ht += $achat_marchandise_ht;

					// achat payés
					$achat_payed = $em->getRepository('AcfDataBundle:Buy')->achatPayedByCompanyInYearMonth($company, $year, $i);
					$year_achat_payed += $achat_payed;

					// achat payés
					$achat_not_payed = $em->getRepository('AcfDataBundle:Buy')->achatNotPayedByCompanyInYearMonth($company, $year, $i);
					$year_achat_not_payed += $achat_not_payed;
				} else {
					$render_cahts['year']["m".$i] = 0;
					$render_achats_hts['year']["m".$i] = 0;
				}
			}
			$render_company['year_ca_ht'] = $year_ca_ht;
			$render_company['year_ca_enc_net'] = $year_ca_enc_net;
			$render_company['year_ca_not_enc_net'] = $year_ca_not_enc_net;
			$render_company['year_achat_ht'] = $year_achat_ht;
			$render_company['year_achat_marchandise_ht'] = $year_achat_marchandise_ht;
			$render_company['year_achat_payed'] = $year_achat_payed;
			$render_company['year_achat_not_payed'] = $year_achat_not_payed;


			$render_company['cahts'] = $render_cahts;
			$render_company['achathts'] = $render_achats_hts;


			$render_natures = array();
			$companyNatures = $em->getRepository('AcfDataBundle:CompanyNature')->getAllByCompany($company);
			foreach ($companyNatures as $companyNature) {
				$render_nature = array();
				$val = $em->getRepository('AcfDataBundle:Buy')->achatHtByCompanyNatureInYear($companyNature, $year);
				if (null != $val && $val != 0) {
					$render_nature['nature'] = $companyNature;
					$render_nature['val'] = $val;
					$render_natures[] = $render_nature;
				}
			}
			$render_company['nature_list'] = $render_natures;

			$render_companies[] = $render_company;
		}

		$this->gvars['companies'] = $render_companies;

		return $this->renderResponse('AcfClientBundle:Default:index.html.twig', $this->gvars);
	}
}
