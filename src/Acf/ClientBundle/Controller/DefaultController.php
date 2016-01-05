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

	public function indexAction()
	{
		$sc = $this->getSecurityTokenStorage();
		$user = $sc->getToken()->getUser();
		$em = $this->getEntityManager();

		$now = new \DateTime('now');
		$current_day = $now->format('d');
		$current_month = $now->format('m');
		$current_year = $now->format('Y');
		$prev_year = $current_year -1;
		$this->gvars['current_day'] = $current_day;
		$this->gvars['current_month'] = $current_month;
		$this->gvars['current_year'] = $current_year;
		$this->gvars['prev_year'] = $prev_year;

		$companies = $user->getCompanies();
		$render_companies = array();

		//$logger = $this->getLogger();

		foreach ($companies as $company) {
			$render_company = array();
			$render_company['company'] = $company;

			$achat_march_nature = $em->getRepository('AcfDataBundle:CompanyNature')->findOneBy(array('company' => $company, 'label' => 'ACHATS DE MARCHANDISES'));
			if (null == $achat_march_nature) {
				$achat_march_nature = new CompanyNature();
				$achat_march_nature->setCompany($company);
				$achat_march_nature->setLabel('ACHATS DE MARCHANDISES');
				$render_company['am'] = 0;
				$em->persist($achat_march_nature);
				$em->flush();
			} else {
				$am = $em->getRepository('AcfDataBundle:Buy')->sumBalanceHtByCompanyNatureInYearMonth($achat_march_nature, $current_year, $current_month);
				$render_company['am'] = $am;
			}
			$em->getRepository('AcfDataBundle:Buy')->updateCompanyNatureNullByCompany($company, $achat_march_nature);
			$em->flush();

			$render_natures = array();
			$companyNatures = $em->getRepository('AcfDataBundle:CompanyNature')->getAllByCompany($company);
			foreach ($companyNatures as $companyNature) {
				$render_nature = array();
				$val = $em->getRepository('AcfDataBundle:Buy')->sumBalanceHtByCompanyNatureInYear($companyNature, $current_year);
				if (null != $val && $val != 0) {
					$render_nature['nature'] = $companyNature;
					$render_nature['val'] = $val;
					$render_natures[] = $render_nature;
				}
			}
			$render_company['natures'] = $render_natures;

			$ca = $em->getRepository('AcfDataBundle:Sale')->sumBalanceHtByCompanyInYearMonth($company, $current_year, $current_month);
			$ca += $em->getRepository('AcfDataBundle:SecondaryVat')->sumBalanceHtByCompanyInYearMonth($company, $current_year, $current_month);
			$render_company['ca'] = $ca;

			$cacumul = 0;

			$render_cas = array();
			$render_cas['prev_year'] = array();
			$render_cas['current_year'] = array();
			for ($i =1; $i<=12; $i++) {
				$ca = $em->getRepository('AcfDataBundle:Sale')->sumBalanceHtByCompanyInYearMonth($company, $prev_year, $i);
				$ca += $em->getRepository('AcfDataBundle:SecondaryVat')->sumBalanceHtByCompanyInYearMonth($company, $prev_year, $i);
				$render_cas['prev_year']["m".$i] = $ca;
			}
			for ($i =1; $i<=12; $i++) {
				$ca = 0;
				if ($i <= $current_month) {
					$ca = $em->getRepository('AcfDataBundle:Sale')->sumBalanceHtByCompanyInYearMonth($company, $current_year, $i);
					$ca += $em->getRepository('AcfDataBundle:SecondaryVat')->sumBalanceHtByCompanyInYearMonth($company, $current_year, $i);
					$render_cas['current_year']["m".$i] = $ca;
					$cacumul += $ca;
				} else {
					$render_cas['current_year']["m".$i] = $ca;
				}

				//$logger->addDebug('ca m'.$i.' : '.$ca);
			}
			$render_company['cas'] = $render_cas;
			$render_company['cacumul'] = $cacumul;

			$render_decs = array();
			$render_decs['prev_year'] = array();
			$render_decs['current_year'] = array();
			for ($i =1; $i<=12; $i++) {
				$dec = $em->getRepository('AcfDataBundle:Buy')->sumBalanceNetByCompanyInYearMonth($company, $prev_year, $i);
				if (null != $dec) {
					$render_decs['prev_year']["m".$i] = $dec;
				} else {
					$render_decs['prev_year']["m".$i] = 0;
				}
			}
			for ($i =1; $i<=12; $i++) {
				if ($i <= $current_month) {
					$dec = $em->getRepository('AcfDataBundle:Buy')->sumBalanceNetByCompanyInYearMonth($company, $current_year, $i);
					if (null != $dec) {
						$render_decs['current_year']["m".$i] = $dec;
					} else {
						$render_decs['current_year']["m".$i] = 0;
					}
				} else {
					$render_decs['current_year']["m".$i] = 0;
				}
			}
			$render_company['decs'] = $render_decs;


			$enc = 0;
			$dec = 0;

			$banks = $company->getBanks();
			$render_banks = array();
			foreach ($banks as $bank) {
				$render_bank = array();
				$render_bank['bank'] = $bank;
				$bank_decaiss = $em->getRepository('AcfDataBundle:Buy')->sumBalanceNetByAccountInYearMonth($bank, $current_year, $current_month);
				$bank_encaiss = $em->getRepository('AcfDataBundle:Sale')->sumBalanceNetByAccountInYearMonth($bank, $current_year, $current_month);
				$bank_encaiss += $em->getRepository('AcfDataBundle:SecondaryVat')->sumBalanceNetByAccountInYearMonth($bank, $current_year, $current_month);
				$render_bank['dec'] = $bank_decaiss;
				$render_bank['enc'] = $bank_encaiss;
				$dec += $bank_decaiss;
				$enc += $bank_encaiss;
				$render_banks[] = $render_bank;
			}
			$render_company['banks'] = $render_banks;

			$funds = $company->getFunds();
			$render_funds = array();
			foreach ($funds as $fund) {
				$render_fund = array();
				$render_fund['fund'] = $fund;
				$fund_decaiss = $em->getRepository('AcfDataBundle:Buy')->sumBalanceNetByAccountInYearMonth($fund, $current_year, $current_month);
				$fund_encaiss = $em->getRepository('AcfDataBundle:Sale')->sumBalanceNetByAccountInYearMonth($fund, $current_year, $current_month);
				$fund_encaiss += $em->getRepository('AcfDataBundle:SecondaryVat')->sumBalanceNetByAccountInYearMonth($fund, $current_year, $current_month);
				$render_fund['dec'] = $fund_decaiss;
				$render_fund['enc'] = $fund_encaiss;
				$dec += $fund_decaiss;
				$enc += $fund_encaiss;
				$render_funds[] = $render_fund;
			}
			$render_company['funds'] = $render_funds;

			$render_company['enc'] = $enc;
			$render_company['dec'] = $dec;

			$render_companies[] = $render_company;
		}

		$this->gvars['companies'] = $render_companies;

		return $this->renderResponse('AcfClientBundle:Default:index.html.twig', $this->gvars);
	}
}
