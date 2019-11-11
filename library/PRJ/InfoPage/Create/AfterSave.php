<?php

/**
 * L8M
 *
 *
 * @filesource library/PRJ/InfoPage/Create/AfterSave.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: AfterSave.php 504 2016-07-21 10:31:54Z nm $
 */


/**
 *
 *
 * PRJ_InfoPage_Create_AfterSave
 *
 *
 */
class PRJ_InfoPage_Create_AfterSave
{
	private $_goOn = FALSE;
	private $_exception = NULL;

	/**
	 * After Save
	 *
	 * @param integer $ID
	 * @param string $modelName
	 * @param array $formValues
	 */
	public function afterSave($ID, $modelName, $formValues)
	{
		$controller = L8M_Library::getUsableUrlStringOnly($formValues['name']);

		$resource = 'default.' . $controller . '.index';

		/**
		 * get guest role model
		 */
		$guestRoleModel = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->addWhere('r.short = ?', array('guest'))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * get default module model
		 */
		$moduleDefaultModel = Doctrine_Query::create()
			->from('Default_Model_Module m')
			->addWhere('m.name = ?', array('default'))
			->limit(1)
			->execute()
			->getFirst()
		;


		/**
		 * create action model
		 */
		$actionModel = new Default_Model_Action();
		$actionModel->merge(array(
			'name'=>'index',
			'role_id'=>$guestRoleModel->id,
			'resource'=>$resource,
			'is_action_method'=>TRUE,
			'content-partial'=>NULL,
			'is_allowed'=>TRUE,
			'Controller'=>array(
				'module_id'=>$moduleDefaultModel->id,
				'name'=>$controller,
			),
		));

		$dataTempArray = array(
			'headline',
			'subheadline',
			'title',
			'keywords',
			'description',
			'content',
		);
		foreach (L8M_Locale::getSupported() as $locale) {
			if (isset($formValues['Translation'][$locale])) {
				foreach ($dataTempArray as $dataTemp) {
					if (array_key_exists($dataTemp, $formValues['Translation'][$locale])) {
						$actionModel->Translation[$locale][$dataTemp] = $formValues['Translation'][$locale][$dataTemp];
					}
				}
			}
		}

		/**
		 * try save action model, catch exception
		 */
		try {
			$actionModel->save();
			$this->_goOn = TRUE;
		} catch (Exception $exception) {
			$this->_exception = $exception;
			$this->_goOn = FALSE;
		}

		if ($this->_goOn) {

			/**
			 * set m2n images
			 */
			$mediaImageM2nInfoPageCollection = Doctrine_Query::create()
				->from('Default_Model_MediaImageM2nInfoPage m')
				->addWhere('m.info_page_id = ?', array($ID))
				->execute()
			;

			foreach ($mediaImageM2nInfoPageCollection as $mediaImageM2nInfoPageModel) {
				if ($this->_goOn) {
					$mediaImageM2nActionModel = new Default_Model_MediaImageM2nAction();
					$mediaImageM2nActionModel->merge(array(
						'media_image_id'=>$mediaImageM2nInfoPageModel->media_image_id,
						'action_id'=>$actionModel->id,
						'position'=>$mediaImageM2nInfoPageModel->position,
					));

					try {
						$mediaImageM2nActionModel->save();
					} catch(Exception $exception) {
						$this->_exception = $exception;
						$this->_goOn = FALSE;
					}
				}
			}
		}
	}

	/**
	 * Flags whether to go on or not.
	 *
	 * @return boolean
	 */
	public function goOn()
	{
		return $this->_goOn;
	}

	/**
	 * Returns internal error.
	 *
	 * @return Exception
	 */
	public function getException()
	{
		return $this->_exception;
	}
}