<?php

/**
 * L8M
 *
 *
 * @filesource /application/services/MediaImageInstance.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MediaImageInstance.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Default_Service_MediaImageInstance
 *
 *
 */
class Default_Service_MediaImageInstance extends Default_Service_Base_Abstract
{
	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Attempts to create and returns a Default_Model_MediaImageInstance
	 * instance from the specified Default_Model_Media instance.
	 *
	 * @param  Default_Model_Media $media
	 * @return Default_Model_MediaImageInstance
	 */
	public static function fromMedia($media = NULL)
	{
		if (!$media) {
			return NULL;
		}

		if (!($media instanceof Default_Model_Media)) {
			throw new Default_Service_MediaImageInstance_Exception('Media needs to be specified as a Default_Model_Media instance.');
		}

		if ($media instanceof Default_Model_MediaImage) {
			return self::fromMediaImage($media);
		}
	}

	/**
	 * Attempts to create and returns a Default_Model_MediaImageInstance
	 * instance from the specified Default_Model_MediaImage instance.
	 *
	 * @param  Default_Model_MediaImage $image
	 * @return Default_Model_MediaImageInstance
	 */
	public static function fromMediaImage($image = NULL)
	{
		if (!$image) {
			return NULL;
		}

		if (!($image instanceof Default_Model_MediaImage)) {
			throw new Default_Service_MediaImageInstance_Exception('Image needs to be specified as a Default_Model_MediaImage instance.');
		}

		$imageInstance = new Default_Model_MediaImageInstance();

		/**
		 * merge data
		 *
		 * @todo consider media folder (maybe have a default media folder for
		 * 		 cached images?)
		 */
		$imageInstance->merge(array(
			'media_image_id'=>$image->id,
			'file_name'=>$image->file_name,
			'file_size'=>$image->file_size,
			'mime_type'=>$image->mime_type,
			'width'=>$image->width,
			'height'=>$image->height,
			'channels'=>$image->channels,
			'role_id'=>$image->role_id,
		));

		if (isset($image['entity_id'])) {
			$imageInstance->entity_id = $image->entity_id;
		}

		return $imageInstance;

	}

}
