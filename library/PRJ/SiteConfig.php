<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/SiteConfig.php
 * @author     Norbert Marks <nm@lmoc.m8>
 * @version    $Id: SiteConfig.php 403 2015-09-08 10:29:49Z nm $
 */

/**
 *
 *
 * PRJ_SiteConfig
 *
 *
 */
class PRJ_SiteConfig
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
	private static $_optionsArray = array();
	private static $_mediaImageArray = array();
	private static $_mediaArray = array();

	/**
	 *
	 *
	 * Class methods
	 *
	 *
	 */

	/**
	 * get option
	 *
	 * @param string $key
	 * @return string
	 */
	public static function getOption($key = NULL)
	{
		$returnValue = NULL;

		if (!is_string($key)) {
			throw new L8M_Controller_Action_Exception('Key needs to be specified as a string.');
		}

		if (!array_key_exists($key, self::$_optionsArray)) {
			$siteConfigModel = Doctrine_Query::create()
				->from('Default_Model_SiteConfig m')
				->addWhere('m.short = ? ', $key)
				->limit(1)
				->execute()
				->getFirst()
			;

			if ($siteConfigModel) {
				self::$_optionsArray[$key] = $siteConfigModel->value;
			} else {
				$siteConfigModel = new Default_Model_SiteConfig();
				$siteConfigModel->short = $key;
				$siteConfigModel->name = $key;
				$siteConfigModel->save();

				self::$_optionsArray[$key] = NULL;
			}
		}

		$returnValue = self::$_optionsArray[$key];

		/**
		 * return option
		 */
		return $returnValue;
	}


	/**
	 * get media image
	 *
	 * @param string $key
	 * @return Default_Model_MediaImage
	 */
	public static function getMediaImage($key = NULL)
	{
		$returnValue = NULL;

		if (!is_string($key)) {
			throw new L8M_Controller_Action_Exception('Key needs to be specified as a string.');
		}

		if (!array_key_exists($key, self::$_mediaImageArray)) {
			$imageConfigModel = Doctrine_Query::create()
				->from('Default_Model_ImageConfig m')
				->addWhere('m.short = ? ', $key)
				->limit(1)
				->execute()
				->getFirst()
			;


			if ($imageConfigModel &&
				$imageConfigModel->media_image_id) {

				self::$_mediaImageArray[$key] = $imageConfigModel->MediaImage;
			}
			if (!isset(self::$_mediaImageArray[$key])) {

				/**
				 * media could not be retrieved
				 */
				$fileShort = 'not_configured.png';
				$fileName = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $fileShort;
				if (!file_exists($fileName)) {

					if (!is_writable(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' )) {
						throw new L8M_Exception('Not writeable: ' . $fileName);
					}

					/**
					 * Create the image
					 */
					$im = imagecreatetruecolor(140, 30);

					/**
					 * Create some colors
					 */
					$white = imagecolorallocate($im, 255, 255, 255);
					$black = imagecolorallocate($im, 0, 0, 0);

					/**
					 * create background
					 */
					imagefilledrectangle($im, 0, 0, 139, 29, $white);

					/**
					 * The text to draw
					*/
					$text = 'Not Configured';

					/**
					 * Add the text
					 */
					imagestring($im, 1, 10, 10, $text, $black);

					/**
					 * save image using imagepng()
					 */
					imagepng($im, $fileName);

					imagedestroy($im);
				}
				if (!$imageConfigModel) {
					$imageConfigModel = new Default_Model_ImageConfig();
					$imageConfigModel->short = $key;
					$imageConfigModel->name = $key;
				}
				$imageConfigModel->media_image_id = Default_Service_Media::fromFileToMediaID($fileName);
				$imageConfigModel->save();
				self::$_mediaImageArray[$key] = $imageConfigModel->MediaImage;
			}
		}

		$returnValue = self::$_mediaImageArray[$key];

		/**
		 * return option
		 */
		return $returnValue;
	}

	public static function getFavicon() {

		$favicon = '/favicon.png';

		$configModel = Default_Model_ImageConfig::getModelByShort('favicon');

		if ($configModel &&
			$configModel->media_image_id) {

			$favicon = $configModel->MediaImage->maxBox(16, 16)->getLink();

		}

		return $favicon;
	}

	/**
	 * get media
	 *
	 * @param string $key
	 * @return Default_Model_Media
	 */
	public static function getMedia($key = NULL)
	{
		$returnValue = NULL;

		if (!is_string($key)) {
			throw new L8M_Controller_Action_Exception('Key needs to be specified as a string.');
		}

		if (!array_key_exists($key, self::$_mediaArray)) {
			$mediaConfigModel = Doctrine_Query::create()
				->from('Default_Model_MediaConfig m')
				->addWhere('m.short = ? ', $key)
				->limit(1)
				->execute()
				->getFirst()
			;


			if ($mediaConfigModel &&
				$mediaConfigModel->media_id) {

				self::$_mediaArray[$key] = $mediaConfigModel->Media;
			}
			if (!isset(self::$_mediaArray[$key])) {

				/**
				 * media could not be retrieved
				 */
				$fileShort = 'not_configured.pdf';
				$fileName = BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . $fileShort;
				if (!file_exists($fileName)) {

					if (!is_writable(BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'media' )) {
						throw new L8M_Exception('Not writeable: ' . $fileName);
					}

					/**
					 * The text to draw
					 */
					$text = 'Not Configured';

					/**
					 * Create the PDF
					 */
					// create new PDF document
					$pdf = new PRJ_Pdf_Base(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

					// set document information
					$pdf->SetCreator(PDF_CREATOR);
					$pdf->SetAuthor($text);
					$pdf->SetTitle($text);
					$pdf->SetKeywords('');
					$pdf->SetProtection(array('modify', 'copy'), '', 'xxxxPasswordxxxx');

					// adds a new page
					$pdf->AddPage();

					$pdf->SetFont('helvetica', '', 10);
					$pdf->SetTextColor(0, 0, 0);

					/**
					 * Add the text
					 */
					$stringWidth = $pdf->GetStringWidth($text) + 4;
					$pdf->MultiCell($stringWidth, 10, $text, 0, 'L', FALSE, 1, 13, 50);

					/**
					 * save image using imagepng()
					 */
					$pdf->Output($fileName, 'F');
				}
				if (!$mediaConfigModel) {
					$mediaConfigModel = new Default_Model_MediaConfig();
					$mediaConfigModel->short = $key;
					$mediaConfigModel->name = $key;
				}
				$mediaConfigModel->media_id = Default_Service_Media::fromFileToMediaID($fileName);
				$mediaConfigModel->save();
				self::$_mediaArray[$key] = $mediaConfigModel->Media;
			}
		}

		$returnValue = self::$_mediaArray[$key];

		/**
		 * return option
		 */
		return $returnValue;
	}
}