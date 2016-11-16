<?php
namespace Acf\PayrollBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\MPaye;

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
		$this->gvars['menu_active'] = 'payrollhome';
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

			$countEmployees = 0;
			$totalSalariesNet = 0;
			$totalSalariesBrut = 0;

			$renderEmployee = array();
			$renderEmployee['year_prev'] = array();
			$renderEmployee['year'] = array();

			$renderSalariesNet = array();
			$renderSalariesNet['year_prev'] = array();
			$renderSalariesNet['year'] = array();

			$renderSalariesBrut = array();
			$renderSalariesBrut['year_prev'] = array();
			$renderSalariesBrut['year'] = array();

			for ($i = 1; $i <= 12; $i++) {
				$countMEmployees = 0;
				$totalMSalariesNet = 0;
				$totalMSalariesBrut = 0;

				$mpaye = $em->getRepository('AcfDataBundle:MPaye')->findOneBy(array(
					'company' => $company,
					'year' => $prevYear,
					'month' => $i
				));

				if (null != $mpaye) {
					foreach ($mpaye->getSalaries() as $msalary) {
						$countMEmployees++;
						if (\is_numeric($msalary->getSalaryNet())) {
							$totalMSalariesNet += $msalary->getSalaryNet();
						}
						if (\is_numeric($msalary->getSalaryNet())) {
							$totalMSalariesBrut += $msalary->getSalaryBrut();
						}
					}
				}
				$renderEmployee['year_prev']['m' . $i] = $countMEmployees;
				$renderSalariesNet['year_prev']['m' . $i] = $totalMSalariesNet;
				$renderSalariesBrut['year_prev']['m' . $i] = $totalMSalariesBrut;
			}

			for ($i = 1; $i <= 12; $i++) {
				$countMEmployees = 0;
				$totalMSalariesNet = 0;
				$totalMSalariesBrut = 0;

				if ($i <= $month) {

					$mpaye = $em->getRepository('AcfDataBundle:MPaye')->findOneBy(array(
						'company' => $company,
						'year' => $year,
						'month' => $i
					));

					if (null != $mpaye) {
						foreach ($mpaye->getSalaries() as $msalary) {
							$countMEmployees++;
							if (\is_numeric($msalary->getSalaryNet())) {
								$totalMSalariesNet += $msalary->getSalaryNet();
							}
							if (\is_numeric($msalary->getSalaryNet())) {
								$totalMSalariesBrut += $msalary->getSalaryBrut();
							}
						}
					}
				}
				$renderEmployee['year']['m' . $i] = $countMEmployees;
				$renderSalariesNet['year']['m' . $i] = $totalMSalariesNet;
				$renderSalariesBrut['year']['m' . $i] = $totalMSalariesBrut;
			}
			$renderCompany['employees'] = $renderEmployee;
			$renderCompany['salariesNet'] = $renderSalariesNet;
			$renderCompany['salariesBrut'] = $renderSalariesBrut;

			$mpaye = $em->getRepository('AcfDataBundle:MPaye')->findOneBy(array(
				'company' => $company,
				'year' => $year,
				'month' => $month
			));

			if (null != $mpaye) {
				foreach ($mpaye->getSalaries() as $msalary) {
					$countEmployees++;
					if (\is_numeric($msalary->getSalaryNet())) {
						$totalSalariesNet += $msalary->getSalaryNet();
					}
					if (\is_numeric($msalary->getSalaryNet())) {
						$totalSalariesBrut += $msalary->getSalaryBrut();
					}
				}
			}

			$renderCompany['countEmployees'] = $countEmployees;
			$renderCompany['totalSalariesNet'] = $totalSalariesNet;
			$renderCompany['totalSalariesBrut'] = $totalSalariesBrut;

			$renderCompanies[] = $renderCompany;
		}

		$this->gvars['companies'] = $renderCompanies;

		return $this->renderResponse('AcfPayrollBundle:Default:index.html.twig', $this->gvars);
	}
}
