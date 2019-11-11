<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/InfoPage/Delete/BeforeDelete.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: BeforeDelete.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_InfoPage_Delete_BeforeDelete
 *
 *
 */
class PRJ_InfoPage_Delete_BeforeDelete
{

	private $_goOn = FALSE;
	private $_exception = NULL;

	/**
	 * BeforeDelete
	 *
	 * @param Doctrine_Record $model
	 */
	public function beforeDelete($model)
	{

		/**
		 * get default module model
		 */
		$defaultModuleModel = Doctrine_Query::create()
			->from('Default_Model_Module m')
			->where('m.name = ?', array('default'))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * get controller name
		 */
		$controller = L8M_Library::getUsableUrlStringOnly($model->name);

		/**
		 * get name of action resource
		 */
		$resource = 'default.' . $controller . '.index';

		/**
		 * get action model
		 */
		$actionModel = Doctrine_Query::create()
			->from('Default_Model_Action a')
			->addWhere('a.resource = ?', array($resource))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * get controller model
		 */
		$controllerModel = Doctrine_Query::create()
			->from('Default_Model_Controller c')
			->addWhere('c.name = ?', array($controller))
			->andWhere('c.module_id = ?', array($defaultModuleModel->id))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * get media image m2n info page collection
		 */
		$mediaImageM2nInfoPageCollection = Doctrine_Query::create()
			->from('Default_Model_MediaImageM2nInfoPage m')
			->addWhere('m.info_page_id = ?', array($model->id))
			->execute()
		;

		/**
		 * if action model and controller model exists, delete them
		 */
		if ($actionModel != FALSE &&
			$controllerModel != FALSE) {

			$this->_goOn = TRUE;

			/**
			 * get media image m2n action collection
			 */
			$mediaImageM2nActionCollection = Doctrine_Query::create()
				->from('Default_Model_MediaImageM2nAction m')
				->addWhere('m.action_id = ?', array($actionModel->id))
				->execute()
			;

			/**
			 * delete all media image m2n action model
			 */
			foreach ($mediaImageM2nActionCollection as $mediaImageM2nActionModel) {
				if ($this->_goOn) {
					try {
						$mediaImageM2nActionModel->hardDelete();
					} catch (Exception $exception) {
						$this->_exception = $exception;
						$this->_goOn = FALSE;
					}
				}
			}

			if ($this->_goOn) {

				/**
				 * delete action model
				 */
				try {
					$actionModel->hardDelete();
				} catch (Exception $exception) {
					$this->_exception = $exception;
					$this->_goOn = FALSE;
				}

				if ($this->_goOn) {

					/**
					 * delete controller model
					 */
					try {
						$controllerModel->hardDelete();
					} catch (Exception $exception) {
						$this->_exception = $exception;
						$this->_goOn = FALSE;
					}

					/**
					 * delete all media image m2n info page model
					 */
					foreach ($mediaImageM2nInfoPageCollection as $mediaImageM2nInfoPageModel) {
						if ($this->_goOn) {
							try {
								$mediaImageM2nInfoPageModel->hardDelete();
							} catch (Exception $exception) {
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