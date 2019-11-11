<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/ImageMeta/Exif.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Exif.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_ImageMeta_Exif
 *
 *
 */
class L8M_ImageMeta_Exif
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
	private $_meta = array();
	private $_metaCache = array();
	private $_hasMeta = FALSE;
	private $_file = FALSE;

	private static $_exifObj = array();

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	public function __construct($filename) {
		if (file_exists($filename) &&
			is_readable($filename)) {

			if (exif_imagetype($filename) == IMAGETYPE_JPEG ||
				exif_imagetype($filename) == IMAGETYPE_TIFF_II ||
				exif_imagetype($filename) == IMAGETYPE_TIFF_MM) {

				$exif = @exif_read_data($filename, 0, TRUE);
				if ($exif &&
					is_array($exif) &&
					count($exif) > 0) {

					$this->_hasMeta = TRUE;
					$this->_meta = $exif;
				}
			}
			$this->_file = $filename;
		}
	}

	/**
	 * Creates an L8M_ImageMeta_Exif object.
	 *
	 * @param String $filename
	 * @return L8M_ImageMeta_Exif
	 */
	public static function factory($filename) {
		$returnValue = FALSE;

		if (array_key_exists($filename, self::$_exifObj)) {
			$returnValue = self::$_exifObj[$filename];
		} else {
			$exifObj = new L8M_ImageMeta_Exif($filename);
			if ($exifObj->hasMeta()) {
				$returnValue = $exifObj;
				self::$_exifObj[$filename] = $exifObj;
			}
		}

		return $returnValue;
	}

	public static function factoryGetTag($filename, $tag) {
		$returnValue = FALSE;
		$exifObj = self::factory($filename);
		if ($exifObj) {
			$returnValue = $exifObj->get($tag);
		}

		return $returnValue;
	}

	public function hasMeta() {
		return $this->_hasMeta;
	}

	public function get($tag) {
		$returnValue = NULL;

		$tag = (string) $tag;

		if ($tag) {
			if (array_key_exists($tag, $this->_metaCache)) {
				$returnValue = $this->_metaCache[$tag];
			} else {
				$keys = explode('.', $tag);
				$option = $this->_meta;
				for ($i = 0; $i < count($keys); $i++) {
					if (is_array($option) &&
						isset($option[$keys[$i]])) {

						$option = $option[$keys[$i]];
						if ($option &&
							is_string($option) &&
							!L8M_Library::isUTF8($option)) {

							$option = iconv('ISO-8859-1', 'UTF-8', $option);
						}
					} else {
						$option = NULL;
					}
				}
				$returnValue = $option;
				$this->_metaCache[$tag] = $option;
			}
		}
		return $returnValue;
	}

	public function dump() {
		return $this->_meta;
	}

	public function getGPS() {
		$returnValue = array(
			'latitude'=>NULL,
			'longitude'=>NULL,
		);

		$latitude = $this->_getGps($this->get('GPS.GPSLatitude'), $this->get('GPS.GPSLatitudeRef'));
		$longitude = $this->_getGps($this->get('GPS.GPSLongitude'), $this->get('GPS.GPSLongitudeRef'));

		if ($longitude !== NULL &&
			$latitude !== NULL) {

			$returnValue = array(
				'latitude'=>$latitude,
				'longitude'=>$longitude,
			);
		}

		return $returnValue;
	}


	private function _getGps($exifCoord, $hemi) {
		if (is_array($exifCoord) &&
			in_array($hemi, array('N', 'E', 'S', 'W'))) {

			$degrees = count($exifCoord) > 0 ? $this->_gps2Num($exifCoord[0]) : 0;
			$minutes = count($exifCoord) > 1 ? $this->_gps2Num($exifCoord[1]) : 0;
			$seconds = count($exifCoord) > 2 ? $this->_gps2Num($exifCoord[2]) : 0;

			$flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;
			$returnValue = $flip * ($degrees + $minutes / 60 + $seconds / 3600);
		} else {
			$returnValue = NULL;
		}
		return $returnValue;
	}

	private function _gps2Num($coordPart) {
		$parts = explode('/', $coordPart);

		if (count($parts) <= 0) {
			$returnValue = 0;
		} else
		if (count($parts) == 1) {
			$returnValue = $parts[0];
		} else {
			$returnValue = floatval($parts[0]) / floatval($parts[1]);
		}

		return $returnValue;
	}
}