<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/ImageMeta/Iptc.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Iptc.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_ImageMeta_Iptc
 *
 *
 */
class L8M_ImageMeta_Iptc
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */
	const IPTC_OBJECT_NAME = '005';
	const IPTC_EDIT_STATUS = '007';
	const IPTC_PRIORITY = '010';
	const IPTC_CATEGORY = '015';
	const IPTC_SUPPLEMENTAL_CATEGORY = '020';
	const IPTC_FIXTURE_IDENTIFIER = '022';
	const IPTC_KEYWORDS = '025';
	const IPTC_RELEASE_DATE = '030';
	const IPTC_RELEASE_TIME = '035';
	const IPTC_SPECIAL_INSTRUCTIONS = '040';
	const IPTC_REFERENCE_SERVICE = '045';
	const IPTC_REFERENCE_DATE = '047';
	const IPTC_REFERENCE_NUMBER = '050';
	const IPTC_CREATED_DATE = '055';
	const IPTC_CREATED_TIME = '060';
	const IPTC_ORIGINATING_PROGRAM = '065';
	const IPTC_PROGRAM_VERSION = '070';
	const IPTC_OBJECT_CYCLE = '075';
	const IPTC_BYLINE = '080';
	const IPTC_BYLINE_TITLE = '085';
	const IPTC_CITY = '090';
	const IPTC_PROVINCE_STATE = '095';
	const IPTC_COUNTRY_CODE = '100';
	const IPTC_COUNTRY = '101';
	const IPTC_ORIGINAL_TRANSMISSION_REFERENCE = '103';
	const IPTC_HEADLINE = '105';
	const IPTC_CREDIT = '110';
	const IPTC_SOURCE = '115';
	const IPTC_COPYRIGHT_STRING = '116';
	const IPTC_CAPTION = '120';
	const IPTC_LOCAL_CAPTION = '121';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_meta = array();
	private $_hasMeta = FALSE;
	private $_file = FALSE;

	private static $_iptcObj = array();

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

			$size = getimagesize($filename, $info);
			$this->_hasMeta = isset($info['APP13']);
			if ($this->_hasMeta) {
				$this->_meta = iptcparse($info['APP13']);
			}
			$this->_file = $filename;
		}
	}

	/**
	 * Creates an L8M_ImageMeta_Iptc object.
	 *
	 * @param $filename
	 * @return L8M_ImageMeta_Iptc
	 */
	public static function factory($filename) {
		$returnValue = FALSE;

		if (array_key_exists($filename, self::$_iptcObj)) {
			$returnValue = self::$_iptcObj[$filename];
		} else {
			$iptcObj = new L8M_ImageMeta_Iptc($filename);
			if ($iptcObj->hasMeta()) {
				$returnValue = $iptcObj;
				self::$_iptcObj[$filename] = $iptcObj;
			}
		}

		return $returnValue;
	}

	public static function factoryGetTag($filename, $tag) {
		$returnValue = FALSE;
		$iptcObj = self::factory($filename);
		if ($iptcObj) {
			$returnValue = $iptcObj->get($tag);
		}

		return $returnValue;
	}

	public function hasMeta() {
		return $this->_hasMeta;
	}

	public function set($tag, $data) {
		$this->_meta['2#' . $tag] = array($data);
		$this->_hasMeta = TRUE;
	}

	public function get($tag) {
		$returnValue = NULL;
		if (isset($this->_meta['2#' . $tag])) {
			$returnValue = $this->_meta['2#' . $tag][0];
		}
		return $returnValue;
	}

	public function dump() {
		return $this->_meta;
	}

	private function _binary() {
		$iptc_new = '';
		foreach (array_keys($this->_meta) as $s) {
			$tag = str_replace('2#', '', $s);
			$iptc_new .= $this->_iptcMakeTag(2, $tag, $this->_meta[$s][0]);
		}
		return $iptc_new;
	}

	private function _iptcMakeTag($rec,$dat,$val) {
		$returnValue = NULL;

		$len = strlen($val);
		if ($len < 0x8000) {
			$returnValue = chr(0x1c) . chr($rec) . chr($dat) .
			chr($len >> 8) .
			chr($len & 0xff) .
			$val;
		} else {
			$returnValue = chr(0x1c) . chr($rec) . chr($dat) .
			chr(0x80) . chr(0x04) .
			chr(($len >> 24) & 0xff) .
			chr(($len >> 16) & 0xff) .
			chr(($len >> 8 ) & 0xff) .
			chr(($len ) & 0xff) .
			$val;
		}

		return $returnValue;
	}

	public function write() {
		$returnValue = FALSE;
		if (function_exists('iptcembed')) {
			$mode = 0;
			$content = iptcembed($this->_binary(), $this->_file, $mode);
			$filename = $this->_file;

			@unlink($filename); #delete if exists

			$fp = fopen($filename, 'w');
			fwrite($fp, $content);
			fclose($fp);

			$returnValue = TRUE;
		}

		return $returnValue;
	}

	public function removeAllTags() {
		$this->_hasMeta = FALSE;
		$this->_meta = array();
		$img = imagecreatefromstring(implode(file($this->_file)));
		@unlink($this->_file); #delete if exists
		imagejpeg($img, $this->_file, 100);
	}
}