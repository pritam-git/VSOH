<?php

/**
 * L8M
 *
 *
 * @filesource library/PRJ/Action/Edit/AfterSave.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: AfterSave.php 528 2017-03-30 13:25:47Z nm $
 */


/**
 *
 *
 * PRJ_Action_Edit_AfterSave
 *
 *
 */
class PRJ_Action_Edit_AfterSave
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

		/**
		 * retrieve action model
		 */
		$actionModel = Doctrine_Query::create()
			->from('Default_Model_Action m')
			->addWhere('m.id = ?', array($ID))
			->limit(1)
			->execute()
			->getFirst()
		;

		if ($actionModel) {

			/**
			 * retrieve info-page model
			 */
			$infoPageModel = Doctrine_Query::create()
				->from('Default_Model_InfoPage m')
				->addWhere('m.short = ?', array($actionModel->Controller->name))
				->limit(1)
				->execute()
				->getFirst()
			;

			if ($infoPageModel) {
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
								$infoPageModel->Translation[$locale][$dataTemp] = $formValues['Translation'][$locale][$dataTemp];
							}
						}
					}
				}

				try {
					$infoPageModel->save();
					$this->_goOn = TRUE;
				} catch (Exception $exception) {
					$this->_exception = $exception;
					$this->_goOn = FALSE;
				}

				if ($this->_goOn) {

					/**
					 * delete info-page m2n images
					 */
					$mediaImageM2nInfoPageCollection = Doctrine_Query::create()
						->from('Default_Model_MediaImageM2nInfoPage m')
						->addWhere('m.info_page_id = ?', array($infoPageModel->id))
						->execute()
					;

					foreach ($mediaImageM2nInfoPageCollection as $mediaImageM2nInfoPageModel) {
						if ($this->_goOn) {
							try {
								$mediaImageM2nInfoPageModel->hardDelete();
							} catch(Exception $exception) {
								$this->_exception = $exception;
								$this->_goOn = FALSE;
							}
						}
					}

					if ($this->_goOn) {

						/**
						 * set m2n images
						 */
						$mediaImageM2nActionCollection = Doctrine_Query::create()
							->from('Default_Model_MediaImageM2nAction m')
							->addWhere('m.action_id = ?', array($actionModel->id))
							->execute()
						;

						foreach ($mediaImageM2nActionCollection as $mediaImageM2nActionModel) {
								if ($this->_goOn) {
									$mediaImageM2nInfoPageModel = new Default_Model_MediaImageM2nInfoPage();
									$mediaImageM2nInfoPageModel->merge(array(
										'media_image_id'=>$mediaImageM2nActionModel->media_image_id,
										'info_page_id'=>$infoPageModel->id,
										'position'=>$mediaImageM2nActionModel->position,
									));

									try {
										$mediaImageM2nInfoPageModel->save();
									} catch(Exception $exception) {
										$this->_exception = $exception;
										$this->_goOn = FALSE;
									}
							}
						}
					}
				}
			} else {
				$this->_goOn = TRUE;
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