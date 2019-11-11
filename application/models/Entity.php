<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Entity.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Entity.php 399 2015-09-02 09:23:38Z nm $
 */

/**
 *
 *
 * Default_Model_Entity
 *
 *
 */
class Default_Model_Entity extends Default_Model_Base_Entity
{
	public function logMessage($messages = NULL, $latitude = NULL, $longitude = NULL, $accuracy = NULL, $altitude = NULL, $altitudeAccuracy = NULL, $heading = NULL, $speed = NULL) {
		if ($this->id) {
			if (!is_array($messages)) {
				$messages = array($messages);
			}
			foreach ($messages as $message) {
				$entityLogMessageModel = Doctrine_Query::create()
					->from('Default_Model_EntityLogMessage m')
					->addWhere('m.short = ? ', array(md5($message)))
					->limit(1)
					->execute()
					->getFirst()
				;

				if (!$entityLogMessageModel) {
					$entityLogMessageModel = new Default_Model_EntityLogMessage();
					$entityLogMessageModel->merge(array(
						'short'=>md5($message),
						'name'=>$message,
					));
					$entityLogMessageModel->save();
				}

				if (isset($_SERVER['REMOTE_ADDR'])) {
					$remoteAddress = $_SERVER['REMOTE_ADDR'];
				} else {
					$remoteAddress = NULL;
				}

				$entityLogModel = new Default_Model_EntityLog();
				$entityLogModel->merge(array(
					'entity_id'=>$this->id,
					'entity_log_message_id'=>$entityLogMessageModel->id,
					'remote_ip'=>$remoteAddress,
					'latitude'=>$latitude,
					'longitude'=>$longitude,
					'accuracy'=>$accuracy,
					'altitude'=>$altitude,
					'altitude_accuracy'=>$altitudeAccuracy,
					'heading'=>$heading,
					'speed'=>$speed,
				));
				$entityLogModel->save();
			}
		}
	}

	public function disableBecauseOfSecurityReasons() {
		$this->disabled = TRUE;
		$this->disabled_reset_hash = md5(L8M_Library::generatePassword());
		$this->save();

		/**
		 * view from MVC
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		//create dynamic variable array for email template.
		$dynamicVars = array(
			'SALUTATION' => $this->Salutation->name,
			'ENABLE_ACCOUNT' => L8M_Library::getSchemeAndHttpHost() . $viewFromMVC->url(array('action'=>'enable-account', 'controller'=>'user', 'module'=>'default', 'lang'=>$this->spoken_language,'login'=>$this->login, 'hash'=>$this->disabled_reset_hash), NULL, TRUE)
		);

		//send email.
		$returnValue = PRJ_Email::send('account_disabled', $this, $dynamicVars);

		return $returnValue;
	}

	/**
	 * applies the changes made to this object into database
	 * this method is smart enough to know if any changes are made
	 * and whether to use INSERT or UPDATE statement
	 *
	 * this method also saves the related components
	 *
	 * @param Doctrine_Connection $conn	 optional connection parameter
	 * @throws Exception					if record is not valid and validation is active
	 * @return void
	 */
	public function save(Doctrine_Connection $conn = null)
	{
		if (!$this->ch_code) {
			$this->ch_code = md5($this->login);
		}

		if ($this->id &&
			$this->hasRelation('Translation')) {

			$this->_removeTranslationFromCache();
		}
		$this->_removeFieldNameFromCache();

		if (Zend_Auth::getInstance()->hasIdentity()) {
			$entityModel = Zend_Auth::getInstance()->getIdentity();
			if ($entityModel instanceof Default_Model_Entity &&
				$entityModel->id == $this->id &&
				!$this->disabled) {

				$adapter = new L8M_Auth_Adapter_EntityReLogin($entityModel);
				$authResult = Zend_Auth::getInstance()->authenticate($adapter);
			}
		}

		parent::save();
	}

	/**
	 * deletes this data access object and all the related composites
	 * this operation is isolated by a transaction
	 *
	 * this event can be listened by the onPreDelete and onDelete listeners
	 *
	 * @return boolean	  true if successful
	 */
	public function delete(Doctrine_Connection $conn = null)
	{
		$logCollection = Default_Model_EntityLog::createQuery()
			->addWhere('m.entity_id = ? ', array($this->id))
			->execute()
		;
		foreach ($logCollection as $logModel) {
			$logModel->hardDelete();
		}

		if ($this->id &&
			$this->hasRelation('Translation')) {

			$this->_removeTranslationFromCache();
		}
		$this->_removeFieldNameFromCache();

		if ($this->id &&
			class_exists('Default_Model_ModelListImageFolder', TRUE)) {

			$modelListImageFolderCollection = Doctrine_Query::create()
				->from('Default_Model_ModelListImageFolder m')
				->addWhere('m.entity_id = ?', array($this->id))
				->execute()
			;
			foreach ($modelListImageFolderCollection as $modelListImageFolderModel) {
				$modelListImageFolderModel->hardDelete();
			}
		}

		return parent::delete($conn);
	}
}