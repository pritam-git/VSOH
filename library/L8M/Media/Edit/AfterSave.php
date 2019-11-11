<?php

/**
 * L8M
 *
 *
 * @filesource library/L8M/Media/Edit/AfterSave.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: AfterSave.php 7 2014-03-11 16:18:40Z nm $
 */


/**
 *
 *
 * L8M_Media_Edit_AfterSave
 *
 *
 */
class L8M_Media_Edit_AfterSave
{
	private $_goOn = FALSE;

	public function afterSave($ID, $modelName, $formValues)
	{

		$model = Doctrine_Query::create()
			->from('Default_Model_Media m')
			->addWhere('m.id = ?', array($ID))
			->limit(1)
			->execute()
			->getFirst()
		;

		/**
		 * guestRoleID - cause we can't work with short, its not right actual
		 */
		$guestRoleID = Doctrine_Query::create()
			->from('Default_Model_Role m')
			->addWhere('m.short = ?', array('guest'))
			->limit(1)
			->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR)
			->execute()
		;

		$oldMediaModelArray = Zend_Registry::get('L8M_Media_Edit_OldMediaModelArray');
		$oldRoleModelArray = Zend_Registry::get('L8M_Media_Edit_OldRoleModelArray');

		if ($model) {

			/**
			 * name changed?
			 */
			if ($model->short !== $oldMediaModelArray['short']) {

				/**
				 * delete old public file
				 */
				$oldPublicFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $oldMediaModelArray['short'];
				if (file_exists($oldPublicFile) &&
					is_writable($oldPublicFile)) {

					@unlink($oldPublicFile);
				}

				/**
				 * delete public files of childs
				 */
				if ($model instanceof Default_Model_MediaImage) {
					$childCollection = Doctrine_Query::create()
						->from('Default_Model_Media m')
						->addWhere('m.media_image_id = ?', array($ID))
						->execute()
					;
					foreach ($childCollection as $childModel) {
						$oldPublicFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $childModel->short;
						if (file_exists($oldPublicFile) &&
							is_writable($oldPublicFile)) {

							@unlink($oldPublicFile);
						}

						/**
						 * rename childs
						 */
						$childModel->file_name = $model->file_name;
						$childModel->short = L8M_Library::createShort('Default_Model_Media', 'short', $model->short, 45, TRUE);
						$childModel->save();
					}
				}
			}

			/**
			 * role changed?
			 */
			$roleID = $model->role_id;
			if ($model->role_id != $oldRoleModelArray['id']) {

				/**
				 * delete old public file if needed
				 */
				if ($model->role_id != $guestRoleID) {
					$oldPublicFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $oldMediaModelArray['short'];
					if (file_exists($oldPublicFile) &&
						is_writable($oldPublicFile)) {

						@unlink($oldPublicFile);
					}
				}

				/**
				 * delete public files of childs
				 */
				if ($model instanceof Default_Model_MediaImage) {
					$childCollection = Doctrine_Query::create()
						->from('Default_Model_Media m')
						->addWhere('m.media_image_id = ?', array($ID))
						->execute()
					;
					foreach ($childCollection as $childModel) {
						if ($model->role_id != $guestRoleID) {
							$oldPublicFile = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'mediafile' . DIRECTORY_SEPARATOR . $childModel->short;
							if (file_exists($oldPublicFile) &&
								is_writable($oldPublicFile)) {

								@unlink($oldPublicFile);
							}
						}
						$childModel->role_id = $model->role_id;
						$childModel->save();
					}
				}
			}


			$this->_goOn = TRUE;
		}
	}

	public function goOn()
	{
		return $this->_goOn;
	}

}