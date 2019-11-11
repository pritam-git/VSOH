<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/ImageBoxItem.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: ImageBoxItem.php 7 2019-05-16 15:40:00Z dp $
 */

/**
 *
 *
 * System_View_Helper_ImageBoxItem
 *
 *
 */
class Default_View_Helper_ImageBoxItem extends Zend_View_Helper_Abstract
{
	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Wraps type selection process HTML
	 *
	 * @param Doctrine_Object $data
	 * @return string
	 */
	public function imageBoxItem($data, $fullWidth = FALSE)
	{
		$modelName = get_class($data);
		if(strpos($modelName, 'Default_Model_') !== 0) throw new L8M_Exception(str_replace('Default_Model_', '', $modelname) . ': View: invalid protocol.');

		$departmentRelation = $modelName . "M2nDepartment";
		$regionRelation = $modelName . "M2nRegion";
		$msg = $this->view->translate("Dies ist für den Benutzer nicht sichtbar.", 'de');

		$isPublishedField = 'publish_' . L8M_Locale::getLang();

		$controller = L8M_Acl_CalledFor::controller();
		if ($controller == 'index') {
			$controller = 'news';
		}

		if($fullWidth) $widthClass = 'col-xs-12';
		else $widthClass = 'col-md-4 col-sm-6 col-xs-12';

		if($data instanceof Default_Model_Commission) {
			$link = $this->view->url(array('module'=>'default', 'controller'=>$controller, 'action'=>'protocol', 'short'=>$data->short), NULL, TRUE);
			$imgUrl = $data->MediaImage->getLink();
			$heading = $data->title;
			$description = $data->description;
		} else {
			$mediaConnectionFieldName = strtolower(L8M_Locale::getLang()) . '_presentation_media_id';

			$imgUrl = NULL;

			if(isset($data->media_image_id) && $data->media_image_id) {
				$imgUrl = $data->MediaImage->getLink();
			} else
			if($data->$mediaConnectionFieldName) {
				$mediaConnectionField = ucfirst(strtolower(L8M_Locale::getLang())) . 'PresentationMedia';
				$imgUrl = $this->getMediaImageForBox($data->$mediaConnectionField);
			} else {
				$imgUrl = '/mediafile/non-readable-pdf-img.png';
			}

			if(isset($data->region_id))
				$link = $this->view->url(array('module' => 'default', 'controller' => $controller, 'action' => 'detail', 'region' => $data->region_id, 'short' => $data->short), NULL, TRUE);
			else
				$link = $this->view->url(array('module' => 'default', 'controller' => $controller, 'action' => 'detail', 'short' => $data->short), NULL, TRUE);

			$heading = $data->title;
			$description = $data->description;
			if($data->publish_datetime > date('Y-m-d H:i:s')) {
				$colorClass = 'color5';
			} else {
				$colorClass = 'color3';
			}
			$publishDateTime = '<h3 class="font-normal ' . $colorClass . ' m-0"><span>' . date('d.m.Y', strtotime($data->publish_datetime)) . '</span></h3>';

			if(isset($this->view->isAdmin)) {
				if((isset($data->$isPublishedField)) && (!$data->$isPublishedField)) {
					$alertInHeading = '&nbsp;&nbsp;&nbsp;<span class="font16 color5 vamid"><i class="fa fa-exclamation-triangle" title="' . $msg . '"></i></span>';
				} else
				if((isset($data->published)) && (!$data->published)) {
					$alertInHeading = '&nbsp;&nbsp;&nbsp;<span class="font16 color5 vamid"><i class="fa fa-exclamation-triangle" title="' . $msg . '"></i></span>';
				}

				if(class_exists($regionRelation) && isset($data->$regionRelation)) {
					$regions = array();
					foreach($data->$regionRelation as $regionM2nRelation) {
						array_push($regions, $regionM2nRelation->Region->name);
					}

					if(count($regions) != 0) {
						$regionString = '<h3 class="font-normal color5 m-0" title="' . implode(", ", $regions) . '"><i class="fa fa-globe"></i>&nbsp;&nbsp;&nbsp;' . implode(", ", $regions) . '</h3>';
					}
				} else
				if(isset($data->region_id)) {
					$regionString = '<h3 class="font-normal color5 m-0" title="' . $data->Region->name . '"><i class="fa fa-globe"></i>&nbsp;&nbsp;&nbsp;' . $data->Region->name . '</h3>';
				}

				if(class_exists($departmentRelation) && isset($data->$departmentRelation)) {
					$departments = array();
					foreach($data->$departmentRelation as $departmentM2nRelation) {
						array_push($departments, $departmentM2nRelation->Department->name);
					}

					if(count($departments) != 0) {
						$departmentString = '<h3 class="font-normal color5 m-0" title="' . implode(", ", $departments) . '"><i class="fa fa-globe"></i>&nbsp;&nbsp;&nbsp;' . implode(", ", $departments) . '</h3>';
					}
				} else
				if(isset($data->department_id)) {
					$departmentString = '<h3 class="font-normal color5 m-0" title="' . $data->Department->name . '"><i class="fa fa-globe"></i>&nbsp;&nbsp;&nbsp;' . $data->Department->name . '</h3>';
				}
			}
		}

		ob_start();
		?>
		<div class="<?= $widthClass; ?> pb-15 imageBoxContainer">
            <a href="<?= $link; ?>">
                <div class="col-xs-12 p-0 imageBox">
                    <div class="col-xs-12 p-0 imageBoxLeft h-100" style="background-image: url('<?= $imgUrl; ?>');"></div>
                    <div class="col-xs-12 p-0 imageBoxRight h-100 pr-10 pt-10 pb-30">
                        <div class="col-xs-12 p-0 h-100 imageBoxInfo">
							<h2 class="col-xs-12 p-0 m-0 pb-5 text-truncate">
								<u title="<?= $heading; ?>"><?= $heading; ?></u>
								<?= isset($alertInHeading) ? $alertInHeading : ''; ?>
							</h2>
							<?= isset($regionString) ? $regionString : ''; ?>
							<?= isset($departmentString) ? $departmentString : ''; ?>
							<?= isset($publishDateTime) ? $publishDateTime : ''; ?>
                            <p class="col-xs-12 p-0 m-0" title="<?= $description; ?>"><?= $description; ?></p>
                        </div>
					    <p class="m-0 viewMore"><?= $this->view->translate('Mehr', 'de'); ?>&nbsp;&nbsp;&nbsp;<i class="fa fa-chevron-right"></i></p>
                    </div>
                </div>
            </a>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Create image from first page of pdf
	 *
	 * @return string
	 */
	public function getMediaImageForBox ($mediaModel)
	{
		$imgUrl = '/mediafile/non-readable-pdf-img.png';

		if(!$mediaModel->media_image_id) {
			//get temporary image path.
			$file = $mediaModel->createThumbnailFromFirstPageOfPdf($mediaModel->id);
			if(empty($file['isError'])){
				//store generated image in DB and get the media_id.
				$convertedImageId = Default_Service_Media::fromFileToMediaID($file['data'], 'user');
				if ($convertedImageId) {
					//save media_id in Default_Model_ProductOrderItem.
					$mediaModel->media_image_id = $convertedImageId;
					$mediaModel->save();
					unlink($file['data']);

					$mediaImageId = $convertedImageId;
				}
			}
		} else {
			$mediaImageId = $mediaModel->media_image_id;
		}

		if(isset($mediaImageId)) {
			$mediaImageModel = Default_Model_Media::getModelByID($mediaImageId);

			$imgUrl = $mediaImageModel->getLink();
		}

		return $imgUrl;
	}
}