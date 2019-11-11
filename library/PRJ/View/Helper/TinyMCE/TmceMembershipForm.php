<?php

/**
 * L8M
 *
 *
 * @filesource /\tion/views/helpers/TmceMembershipForm.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: TmceMembershipForm.php 16 2019-01-10 13:20:38Z dp $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceMembershipForm
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceMembershipForm extends L8M_View_Helper
{

	private $_file;
	private $_postedData;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a contentBoxes.
	 *
	 * @return string
	 */
	public function tmceMembershipForm()
	{
		$content = '<div class="content">';

		$form = new Default_Form_Membership_Form();
		$form
			->addDecorator(new L8M_Form_Decorator_FormHasRequiredElements())
			->setAction($this->view->url(array('module'=>'default', 'controller'=>'membership', 'action'=>'index')))
		;

		$postedData = Zend_Controller_Front::getInstance()->getRequest()->getPost();

		if ($form->isSubmitted() &&
			$form->isValid($postedData)) {

			$this->createData($postedData);
			$this->createPdf();
			$this->sendEmail();
			$this->downloadPdf();

			$content .= '<div class="alert alert-success" role="alert">' . L8M_Translate::string('You have successfully applied for membership.', L8M_Locale::getLang()) . '</div>';
		}

		$content .= $form;
		$content .= '</div>';

		return $content;

	}

	/**
	 * Creates data for pdf
	 *
	 * @return boolean
	 */
	private function createData($data)
	{
		$this->_postedData = $data;
		unset($this->_postedData['formMembershipSubmit']);

		if(isset($data['salutation_id']) && ($data['salutation_id'] != '')) {
			$salutationTranslationModel = Doctrine_Query::create()
				->from('Default_Model_SalutationTranslation st')
				->select('st.name')
				->addWhere('st.id = ? AND st.lang = ?', array($data['salutation_id'], L8M_Locale::getLang()))
				->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
				->limit(1)
				->execute()
			;

			$salutation = $salutationTranslationModel[0]['st_name'];
			$this->_postedData['salutation'] = $salutation;
			unset($this->_postedData['salutation_id']);
		}

		/* if(isset($data['kanton_id']) && ($data['kanton_id'] != '')) {
			$kantonModel = Doctrine_Query::create()
				->from('Default_Model_Kanton k')
				->select('k.name')
				->addWhere('k.id = ?', array($data['kanton_id']))
				->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
				->limit(1)
				->execute()
			;

			$kanton = $kantonModel[0]['k_name'];
			$this->_postedData['kanton'] = $kanton;
			unset($this->_postedData['kanton_id']);
		} */

		/* if(isset($data['contract_type_id']) && ($data['contract_type_id'] != '')) {
			$contractTypeModel = Doctrine_Query::create()
				->from('Default_Model_ContractType ct')
				->select('ct.name')
				->addWhere('ct.id = ?', array($data['contract_type_id']))
				->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
				->limit(1)
				->execute()
			;

			$contractType = $contractTypeModel[0]['ct_name'];
			$this->_postedData['contract_type'] = $contractType;
			unset($this->_postedData['contract_type_id']);
		} */

		if(isset($data['country_id']) && ($data['country_id'] != '')) {
			$countryTranslationModel = Doctrine_Query::create()
				->from('Default_Model_CountryTranslation ct')
				->select('ct.name')
				->addWhere('ct.id = ? AND ct.lang = ?', array($data['country_id'], L8M_Locale::getLang()))
				->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
				->execute()
			;

			$country = $countryTranslationModel[0]['ct_name'];
			$this->_postedData['country'] = $country;
			unset($this->_postedData['country_id']);
		}
	}

	/**
	 * Creates a pdf
	 *
	 * @return boolean
	 */
	private function createPdf()
	{
		/*
		* create membership-form pdf
		*/
		$membershipFormPDF = new PRJ_MembershipFormPdf();
		$this->_file = $membershipFormPDF
			->setData($this->_postedData)
			->createPdf('F')
		;
	}

	/**
	 * Sends mail with pdf
	 */
	private function sendEmail()
	{
		//get all supervisors.
		$supervisorModel = Doctrine_Query::create()
			->from('Default_Model_EntitySupervisor')
			->orderBy('id ASC')
		;

		if ($supervisorModel->count() > 0) {
			//send email to all supervisor.
			foreach ($supervisorModel->execute() as $supervisorValue) {
				//create dynamic variable array for email template.
				$dynamicVars = array(
					'DATE_TIME' => date('d/m/Y H:i:s'),
					'USER_NAME' => $this->_postedData['firstname'] . ' ' . $this->_postedData['lastname'],
					'USER_EMAIL' => $this->_postedData['email']
				);

				//send email.
				PRJ_Email::send('membership_request', $supervisorValue, $dynamicVars, array($this->_file));
			}
		}
	}

	/**
	 * Force pdf download
	 *
	 * @return boolean
	 */
	public function downloadPdf()
	{
		if(file_exists($this->_file)) {
			$fileName = explode('/', $this->_file);
			$fileName = $fileName[count($fileName) - 1];
            header("Content-Disposition: attachment; filename=" . $fileName);
            header("Content-Length: " . filesize($this->_file));
            header("Content-Type: application/pdf;");
            readfile($this->_file);
        }
	}

}