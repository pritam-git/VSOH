<?php

/**
 * L8M
 *
 *
 * @filesource library/L8M/Media/Edit/RemoveUnusedMedia.php
 * @author     Pritam Parmar <pp@l8m.com>
 * @version    $Id: RemoveUnusedMedia.php 7 2019-08-14 13:09:40Z pp $
 */


/**
 *
 *
 * L8M_Media_Edit_RemoveUnusedMedia
 *
 *
 */
class L8M_Media_Edit_RemoveUnusedMedia
{
	private $_goOn = TRUE;

	public function deleteMedia($ID, $modelName, $formValues)
	{
		foreach(array_keys($_POST) as $key) {
			if(strpos($key, 'image_resource_') !== FALSE) {
				$currentModelColumn = (str_replace('image_resource_', '', $key));
				$editedModel = $modelName::getModelByID($ID);
				if($editedModel->$currentModelColumn !=  $formValues[$currentModelColumn]) {
					$existingEditImageId = $editedModel->$currentModelColumn;
					$editedModel->$currentModelColumn = NULL;
					$editedModel->save();
					$oldMediaModel = Default_Model_Media::getModelByID($existingEditImageId);
					if($oldMediaModel) {
						$mediaImageModel = Doctrine_Query::create()
							->from('Default_Model_Media  m')
							->addWhere('m.media_image_id = ?', array($existingEditImageId))
							->execute()
						;
						foreach($mediaImageModel as $mediaModel) {
							$mediaModel->hardDelete();
						}
						$oldMediaModel->hardDelete();
					}
				}
			}
		}
	}

	public function goOn()
	{
		return $this->_goOn;
	}

}