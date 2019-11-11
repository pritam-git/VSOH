<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/Email.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: Email.php 16 2013-10-15 13:27:56Z sl $
 */

/**
 *
 *
 * PRJ_Email
 *
 *
 */

class PRJ_Email
{
	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * Send a emails to user
	 *
	 * @param string $emailName
	 * @param array $entityModel
	 * @param array $dynamicVarArray
	 * @param bool|array $attachmentFiles
	 * @return bool
	 */
	public static function send($emailName, $entityModel, $dynamicVarArray = array(),$attachmentFiles = false,$subject = NULL) {
		/**
		 * view from MVC
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		$spoken_language = strtolower($entityModel->spoken_language);

		/**
		 * prepare the name
		 */
		$receiverName = $entityModel->firstname . ' ' . $entityModel->lastname;

		/**
		 * email
		 */
		$email = L8M_MailV2::factory($emailName,$spoken_language);
		$email
			->setFrom(L8M_Config::getOption('resources.mail.defaultFrom.email'), L8M_Config::getOption('resources.mail.defaultFrom.name'))
//			->addTo('k.patel@hahn-media.ch', $receiverName)
			->addTo($entityModel->email, $receiverName)
		;

		if($subject) {
			$email
				->setSubject($subject)
			;
		}

		if ($entityModel->salutation_id == 2) {
			$salutation = $viewFromMVC->translate()->getTranslator()->translate('Sehr geehrter Herr', 'de', $spoken_language);
		} else {
			$salutation = $viewFromMVC->translate()->getTranslator()->translate('Sehr geehrte Frau', 'de', $spoken_language);
		}

		/**
		 * header
		 */
		$content = L8M_MailV2_Part::factory('header', $email);
		$content
			->setDynamicVar('HEADER', $viewFromMVC->prjEmailHeader())
			->setDynamicVar('FIRSTNAME', $entityModel->firstname)
			->setDynamicVar('LASTNAME', $entityModel->lastname)
			->setDynamicVar('SALUTATION', $salutation)
		;
		$email->addPart($content);

		/**
		 * content
		 */
		$content = L8M_MailV2_Part::factory($emailName, $email);
		foreach ($dynamicVarArray as $key => $value) {
			$content
				->setDynamicVar(strtoupper($key), $value)
			;
		}
		$email->addPart($content);

		/**
		 * footer
		 */
		$AGBLink = L8M_Library::getSchemeAndHttpHost().$viewFromMVC->url(array('module'=>'default', 'controller'=>'terms-and-condition', 'action'=>'index', 'lang' => $spoken_language), NULL, TRUE);
		$PrivacyLink = L8M_Library::getSchemeAndHttpHost().$viewFromMVC->url(array('module'=>'default', 'controller'=>'privacy-policy', 'action'=>'index', 'lang' => $spoken_language), NULL, TRUE);
		$content = L8M_MailV2_Part::factory('footer', $email);
		$content
			->setDynamicVar('AGB_LINK', $AGBLink)
			->setDynamicVar('PRIVACY_LINK', $PrivacyLink)
		;
		$email->addPart($content);

		/**
		 * attachment
		 */
		if (!empty($attachmentFiles)) {
			foreach ($attachmentFiles as $attachmentFile) {
				if (file_exists($attachmentFile)) {
					$emailAttachment = L8M_MailV2_Part_Attachment::factory($emailName . '_' . 'attachment', $email);
					$emailAttachment
						->addItem($attachmentFile)
					;

					$email->addPart($emailAttachment);
				}
			}
		}

		/**
		 * send email
		 */
		try {
			$email->send();
			$returnValue = TRUE;
		} catch (L8M_Mail_Exception $exception) {
			$returnValue = FALSE;
		}

		return $returnValue;
	}
}