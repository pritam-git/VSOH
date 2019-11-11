<?php

/**
 * L8M
 *
 *
 * @filesource library/PRJ/InfoPage/Edit/AfterSave.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: AfterSave.php 504 2016-07-21 10:31:54Z nm $
 */


/**
 *
 *
 * PRJ_InfoPage_Edit_AfterSave
 *
 *
 */
class PRJ_InfoPage_Edit_AfterSave
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
		 * get guest role model
		 */
		$guestRoleModel = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->addWhere('r.short = ?', array('guest'))
			->limit(1)
			->execute()
			->getFirst()
		;

		$this->_goOn = TRUE;
		$controller = L8M_Library::getUsableUrlStringOnly($formValues['name']);

		if (Zend_Registry::isRegistered('PRJ_InfoPage_Edit_OldControllerName')) {
			$oldController = Zend_Registry::get('PRJ_InfoPage_Edit_OldControllerName');
		} else {
			$oldController = $controller;
		}
		$oldResource = 'default.'. $oldController . '.index';


		$resource = 'default.' . $controller . '.index';

		if ($controller == $oldController) {

			$controllerModel = Doctrine_Query::create()
				->from('Default_Model_Controller c')
				->addWhere('c.name = ?', array($controller))
				->andWhere('c.module_id = ?', array($moduleDefaultModel->id))
				->limit(1)
				->execute()
				->getFirst()
			;

			$actionModel = Doctrine_Query::create()
				->from('Default_Model_Action a')
				->addWhere('a.resource = ?', array($resource))
				->limit(1)
				->execute()
				->getFirst()
			;

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

			try {
				$actionModel->save();
			} catch (L8M_Model_Exception $exception) {
				$this->_exception = $exception;
				$this->_goOn = FALSE;
			}

			if ($this->_goOn) {

				/**
				 * delete action m2n images
				 */
				$mediaImageM2nActionCollection = Doctrine_Query::create()
					->from('Default_Model_MediaImageM2nAction m')
					->addWhere('m.action_id = ?', array($actionModel->id))
					->execute()
				;

				foreach ($mediaImageM2nActionCollection as $mediaImageM2nActionModel) {
					if ($this->_goOn) {
						try {
							$mediaImageM2nActionModel->hardDelete();
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

		} else {

			$this->_goOn = TRUE;

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
						if (isset($formValues['Translation'][$locale][$dataTemp])) {
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
			} catch (L8M_Model_Exception $exception) {
				$this->_exception = $exception;
				$this->_goOn = FALSE;
			}

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

			/**
			 * reorganize & delete old stuff
			 */
			if ($this->_goOn) {
				$oldActionModel = Doctrine_Query::create()
					->from('Default_Model_Action a')
					->addWhere('a.resource = ?', array($oldResource))
					->limit(1)
					->execute()
					->getFirst()
				;

				/**
				 * reorganize actions
				 */
				$relations = $oldActionModel->getTable()->getRelations();
				foreach ($relations as $relation) {
					if ($relation instanceof Doctrine_Relation_ForeignKey &&
						$relation->getAlias() !== 'Translation') {

						$foreignColumnName = $relation->getForeignColumnName();
						$localColumnName = $relation->getLocalColumnName();
						$tmpClassCollection = Doctrine_Query::create()
							->from($relation->getClass() . ' m')
							->addWhere('m.' . $foreignColumnName . ' = ?', array($oldActionModel->$localColumnName))
							->execute()
						;
						foreach ($tmpClassCollection as $tmpClassModel) {
							$tmpClassModel->$foreignColumnName = $actionModel->$localColumnName;
							$tmpClassModel->save();
						}
					}
				}

				/**
				 * delete old stuff
				 */
				if ($oldActionModel) {

					/**
					 * delete action m2n images
					 */
					$mediaImageM2nActionCollection = Doctrine_Query::create()
						->from('Default_Model_MediaImageM2nAction m')
						->addWhere('m.action_id = ?', array($oldActionModel->id))
						->execute()
					;

					foreach ($mediaImageM2nActionCollection as $mediaImageM2nActionModel) {
						if ($this->_goOn) {
							try {
								$mediaImageM2nActionModel->hardDelete();
							} catch(Exception $exception) {
								$this->_exception = $exception;
								$this->_goOn = FALSE;
							}
						}
					}

					if ($this->_goOn) {

						/**
						 * delete old action model
						 */
						try {
							$oldActionModel->hardDelete();
						} catch (L8M_Model_Exception $exception) {
							$this->_exception = $exception;
							$this->_goOn = FALSE;
						}

						if ($this->_goOn) {
							$oldControllerModel = Doctrine_Query::create()
								->from('Default_Model_Controller c')
								->addWhere('c.name = ?', array($oldController))
								->andWhere('c.module_id = ?', array($moduleDefaultModel->id))
								->limit(1)
								->execute()
								->getFirst()
							;

							/**
							 * delete old controller model
							 */
							try {
								$oldControllerModel->hardDelete();
							} catch (L8M_Model_Exception $exception) {
								$this->_exception = $exception;
								$this->_goOn = FALSE;
							}
						}
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