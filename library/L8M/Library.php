<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Library.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Library.php 491 2016-04-05 08:49:15Z nm $
 */

/**
 *
 *
 * L8M_Library
 *
 *
 */
class L8M_Library
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	const SIZE_STRING_FULL = 'full';
	const SIZE_STRING_SHORT = 'short';

	/**
	 * Date Format according to Zend_Date in default ISO mode
	 *
	 * Don't use 'YYYY', always use 'yyyy' !!!
	 *
	 * @see http://zendframework.com/manual/en/zend.date.constants.html#zend.date.constants.selfdefinedformats
	 *
	 *
	 */

	const DATE_FORMAT = 'yyyy-MM-dd';
	const DATETIME_FORMAT = 'yyyy-MM-dd HH:mm:ss';
	const TIME_FORMAT = 'HH:mm:ss';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * TRUE when arrayShow is supposed to generate output
	 *
	 * @var unknown_type
	 */
	protected static $_arrayShowEnabled = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Expands IP, i.e., transforms 127.0.0.1 into 127.000.000.001 - used
	 * for better readability in log files
	 *
	 * @param  string $IP
	 * @return string
	 */
	public static function expandIP ($IP = NULL)
	{
		if (preg_match('/([0-9]{1,3}\.){3}[0-9]{1,3}/', $IP) && ! preg_match('/\.{15}/', $IP)) {
			$IP = explode('.', $IP);
			foreach ($IP as $ipSubPart) {
				$ipExpanded[] = str_pad($ipSubPart, 3, '0', STR_PAD_LEFT);
			}
			return implode('.', $ipExpanded);
		}
		return NULL;
	}

	/**
	 * Returns TRUE if arrayShow is supposed to render output.
	 *
	 * @return bool
	 */
	public static function isArrayShowEnabled()
	{
		if (self::$_arrayShowEnabled === NULL) {
			if (Zend_Registry::isRegistered('Zend_Config') &&
				(FALSE != $config = Zend_Registry::get('Zend_Config')) &&
				$config->get('arrayshow') &&
				$config->arrayshow->get('enabled')) {
				self::$_arrayShowEnabled = TRUE;
			} else {
				self::$_arrayShowEnabled = FALSE;
			}
		}
		return self::$_arrayShowEnabled;
	}

	/**
	 * Enables or disables arrayShow output.
	 *
	 * @param  bool $enable
	 * @return void
	 */
	public static function enableArrayShow($enable = TRUE)
	{
		self::$_arrayShowEnabled = (bool) $enable;
	}

	/**
	 * Diisables arrayShow output.
	 *
	 * @return void
	 */
	public static function disableArrayShow()
	{
		self::enableArrayShow(FALSE);
	}

	/**
	 * Returns type of data.
	 *
	 * @param  mixed $data
	 * @return string
	 */
	protected static function _getType($data = NULL)
	{
		if (is_object($data)) {
			$type = 'object (' . get_class($data) . ')';
		} else

		if (is_array($data)) {
			$type = 'array (' . count($data). ')';
		} else


		if (is_resource($data)) {
			$type = 'resource';
		} else

		if (is_string($data)) {
			$type = 'string (' . strlen($data). ')';
		} else

		if ($data === FALSE ||
			$data === TRUE) {
			$type = 'boolean';
		} else

		if (is_float($data)) {
			$type = 'float';
		} else

		if (is_int($data)) {
			$type = 'integer';
		} else {
			$type = 'n/a';
		}

		return $type;
	}

	/**
	 * Traverses through data and outputs it.
	 *
	 * @param  mixed $data
	 * @return void
	 */
	protected static function _dataTraverse($data = NULL, $skipFirstUl = FALSE, $withType = TRUE)
	{
		/**
		 * data is traversable
		 */
		if (is_array($data) ||
			($data instanceof Traversable) &&
			count($data)>0) {

/*

?>
<span class="value"><?php echo (($data instanceof Traversable) ? 'TRAVERSABLE' : 'ARRAY'); ?></span>
<?php

*/

			if (!$skipFirstUl) {
				echo '<ul class="last">';
			}

			foreach($data as $key=>$value) {
				echo '<li><span class="key">' . htmlentities($key, ENT_COMPAT, 'UTF-8');
				if ($withType) {
					$type = self::_getType($value);
					echo ' <span class="type">' . htmlentities($type, ENT_COMPAT, 'UTF-8') . '</span>';
				}
				echo '</span>';
				self::_dataTraverse($value, FALSE, $withType);
				echo '</li>';
			}

			if (!$skipFirstUl) {
				echo '</ul>';
			}

		} else {

			if (is_object($data)) {
				$data = get_class($data);
			} else

			if ($data === TRUE) {
				$data = 'TRUE';
			} else

			if ($data === FALSE) {
				$data = 'FALSE';
			} else

			if ($data === '') {
				$data = 'EMPTY STRING';
			} else

			if ($data === NULL) {
				$data = 'NULL';
			}
?>
<div class="value"><?php echo htmlentities($data, ENT_COMPAT, 'UTF-8'); ?></div>
<?php
		}

	}

	/**
	 * Traverses through data and outputs it, together
	 * with some useful information and nicely decorated.
	 *
	 * @param $data
	 */
	public static function arrayShow($data = NULL)
	{
		ob_start();

		/**
		 * type
		 */
		$type = self::_getType($data);

		/**
		 * caller
		 */
		$caller = debug_backtrace();
		$caller = $caller[0];

?>
<div class="box debug">
	<ul class="array-show iconized">
		<li class="page-white">
			<span class="key">File</span> <span class="value"><?php echo htmlentities($caller['file'], ENT_COMPAT, 'UTF-8'); ?></span>
		</li>
		<li class="page-white-code">
			<span class="key">Line</span> <span class="value"><?php echo htmlentities($caller['line'], ENT_COMPAT, 'UTF-8'); ?></span>
		</li>
		<li class="page-white-database">
			<span class="key">Data <span class="type"><?php echo $type; ?></span></span>
<?php

		self::_dataTraverse($data);

?>
		</li>
	</ul>
</div>
<?php

		echo ob_get_clean();

	}

	/**
	 * Traverses through the specified data.
	 *
	 * @param  mixed $data
	 * @return void
	 */
	public static function dataShow($data = NULL)
	{
		ob_start();

?>
<ul class="array-show">
<?php

		if ($data) {
			self::_dataTraverse($data, TRUE, FALSE);
		} else {

			if ($data === FALSE) {
				$data = 'FALSE';
			} else

			if ($data === '') {
				$data = 'EMPTY STRING';
			} else

			if ($data === NULL) {
				$data = 'NULL';
			} else

			if (is_array($data) &&
				count($data) ==0) {
				$data = 'EMPTY ARRAY';
			}

?>
	<li><span class="value"><?php echo $data; ?></span></li>
<?php
		}

?>
</ul>
<?php

		echo ob_get_clean();
	}

	/**
	 * Returns array including only values from a certain subkey in its major keys e.x.:
	 * $before = array(
	 *   'de'=>array(
	 *     'title=>'123',
	 *     'name=>'456',
	 *   ),
	 *   'en'=>array(
	 *     'title=>'987',
	 *     'name=>'654',
	 *   ),
	 * )
	 * $after = L8M_Library::arrayCopySubKeyToMajorKey('name', $before)
	 * $after = array(
	 *   'de'=>'456',
	 *   'en'=>'654',
	 * )
	 *
	 * @param String $key
	 * @param Array $dataArray
	 * @return Array
	 */
	public static function arrayCopySubKeyToMajorKey($key, $dataArray)
	{
		$returnValue = array();

		if ((is_numeric($key) || is_string($key)) &&
			is_array($dataArray)) {

			foreach ($dataArray as $dataKey => $dataValueArray) {
				if (is_array($dataValueArray) &&
					array_key_exists($key, $dataValueArray)) {

					$returnValue[$dataKey] = $dataValueArray[$key];
				}
			}
		}

		return $returnValue;
	}

	/**
	 * Crops a string either hard or soft to the specified maxLength at most. In
	 * soft mode, the next space left from the specified maxLength is searched,
	 * as a crop border, in hard mode it is cropped at maxLength
	 *
	 * @param  string $string
	 * @param  int	$maxLength
	 * @param  bool   $hardCrop
	 * @return string
	 */
	public static function cropString ($string = NULL, $maxLength = 100, $hardCrop = FALSE, $endWith = '. . .')
	{
		return mb_strlen($string)>$maxLength ? mb_substr($string,0,($hardCrop==FALSE) ? mb_strrpos(mb_substr($string, 0, $maxLength),' ') : $maxLength ) . $endWith : $string;
	}

	/**
	 * Breaks a string by inserting spaces, where necessary, as to make it its
	 * words at most maxRowLength characters long
	 *
	 * @todo   consider whether this function could be replaced by PHP wordwrap
	 * 		   function
	 * @param  string $string
	 * @param  int	$maxRowLength
	 * @return string
	 */
	public static function breakString ($string = NULL, $maxRowLength = 50)
	{
		if ($string!==NULL &&
			preg_match('/^[1-9]+[0-9]*$/', $maxRowLength)) {
			$stringParts = str_split($string, $maxRowLength);
			foreach ($stringParts as $stringPart) {
				if (strrpos($stringPart, ' ')!=FALSE) {
					$stringBroken.= $stringPart;
				} else {
					$stringBroken.= ' ' . $stringPart;
				}
			}
			return $stringBroken;
		}
		return NULL;
	}

	/**
	 * Returns TRUE if provided string validates as link
	 *
	 * @todo   optimize regular expression
	 * @param  string $string
	 * @return bool
	 */
	public static function isLink($string = NULL)
	{
		// if (preg_match('/(([a-zA-Z]+:\/\/)([a-z][a-z0-9_\..-]*[a-z]{2,6})([a-zA-Z0-9\/*-?&%]*))/i', $string)) {
		if (preg_match('/^((http|https|ftp):\/\/|javascript:)/i', $string)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Returns TRUE if provided string validates as eMail
	 *
	 * @param  string $string
	 * @return string
	 */
	public static function isEmail ($string = NULL)
	{
		$string = trim(strtolower($string));
// 		if (eregi('^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$',$string,$check)) {
// 		if (preg_match('/^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$/', $string)) {
		if (preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/', $string)) {
			return $string;
		}
		return NULL;
	}

	/**
	 * Returns TRUE if the provided string validates as HTML Color
	 *
	 * @param  string $string
	 * @return bool
	 */
	public static function isHtmlColor($string = NULL)
	{
		// leaving out rgb(255,255,255) notation
		if ($string!=NULL &&
			preg_match('/^#[0-9a-f]{6}$/i', $string)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	* Check if a data is serialized or not.
	*
	* @param mixed $data	variable to check
	* @return boolean
	*/
	function isSerialized($data){
		if (trim($data) == "")
		{
			return false;
		}
		if (preg_match("/^(i|s|a|o|d)(.*);/si",$data))
		{
			return true;
		}
		return false;
	}

	/**
	 * Returns a Byte-String
	 *
	 * @param integer $integer
	 * @param integer $potence
	 * @return string
	 */
	public static function getBytes($integer = NULL, $potence = 0)
	{
		$units = array(
			0=>'Bytes',
			1=>'KiloBytes',
			2=>'MegaBytes',
			3=>'GigaBytes',
		);
		return number_format(round($integer/pow(1024, $potence), 0), 0, ',', '.') . ' ' . $units[$potence];
	}

	/**
	 * Returns memory limit in bytes
	 */
	public static function getPhpMemoryLimit()
	{
		$returnMemoryLimit = NULL;
		$memoryLimit = ini_get('memory_limit');
		if ($memoryLimit === FALSE) {
			$memoryLimit = L8M_Config::getOption('l8m.php.memory_limit');
			if (!$memoryLimit) {
				$memoryLimit = '128M';
			}
		}

		if (substr($memoryLimit, -1) == 'M') {
			$returnMemoryLimit = substr($memoryLimit, 0, strlen($memoryLimit) - 1) * 1024 * 1024;
		} else
		if (substr($memoryLimit, -2) == 'GB') {
			$returnMemoryLimit = substr($memoryLimit, 0, strlen($memoryLimit) - 1) * 1024 * 1024 * 1024;
		}

		return $returnMemoryLimit;
	}

	/**
	 * Returns a properly formatted string representing the provided bytes
	 *
	 * @param  int	$bytes
	 * @return string
	 */
	public static function getSizeString($bytes = NULL)
	{

		$sizeString = FALSE;
		if ($bytes>=1073741824) {
			$sizeString = round($bytes / 1073741824 * 10) / 10 . 'GB';
		} else
		if ($bytes>=1048576)	{
			$sizeString = round($bytes / 1048576 * 10) / 10 . 'MB';
		} else
		if ($bytes>=1024) {
			$sizeString = round($bytes / 1024 * 10) / 10 . 'kB';
		} else
		if ($bytes!=0) {
			$sizeString = $bytes . ' Bytes';
		}
		return $sizeString;
	}

	/**
	 * Returns max upload size in Bytes defined by application.ini and php.ini
	 *
	 * @return integer
	 */
	public static function getMaxUploadSize()
	{
		$phpIniMaxSize = ini_get('upload_max_filesize');
		if ($phpIniMaxSize &&
			substr($phpIniMaxSize, -1) == 'M') {

			$phpIniMaxSize = substr($phpIniMaxSize, 0, strlen($phpIniMaxSize) -1) * 1024 * 1024;
		} else {
			$phpIniMaxSize = NULL;
		}

		$appIniMaxSize = L8M_Config::getOption('mediabrowser.maxupload');

		$returnValue = $appIniMaxSize;
		if ($phpIniMaxSize !== NULL &&
			$appIniMaxSize > $phpIniMaxSize) {

			$returnValue = $phpIniMaxSize;
		}

		return $returnValue;
	}

	/**
	 * Converts Cent to Euro and returns the localized currency display based on
	 * Zend_Locale in Registry with fallback to 'de_DE'
	 *
	 * @param integer $cent Value in Cent to be displayed as Euro
	 * @param Zend_Locale $locale Optionel Setting of locale and overriding Registry setting
	 * @return string
	 */
	public static function formatEuroCent($cent, $locale = NULL)
	{
		/* @var $locale Zend_Locale */

		if ($locale === NULL) {
			$locale = Zend_Registry::get('Zend_Locale');
		}

		if ($locale === NULL) {
			$locale = new Zend_Locale('de_DE');
		}

		if (!$locale->getRegion()) {
			$language = $locale->getLanguage();
			switch ($language) {
				case 'de':
					$locale->setLocale($language.'_DE');
					break;
				case 'en':
					$locale->setLocale($language.'_GB');
					break;
			}
		}

		$currency = new Zend_Currency('EUR', $locale);
		//$currency->setFormat(array('position' => Zend_Currency::STANDARD));
		return $currency->toCurrency($cent / 100);

	}

	/**
	 * Calculates number of days between timestamps
	 *
	 * @param int $arrival_tstamp
	 * @param int $departure_tstamp
	 * @return int
	 */
	public static function countDaysBetweenTimestamps ($before, $after)
	{
		$n = intval(round(($after - $before) / (60 * 60 * 24)));
		//echo t3lib_div::debug($n)."\n";
		return $n;
	}


	/**
	 * Converts an array to URL encoded params to be appended to a URL.
	 *
	 * @todo   escaping of & . . . ?
	 * @param  array $array
	 * @param  bool  $questionMark
	 * @return string
	 */
	public static function arrayToUrlParams($array = array())
	{
		if (is_array($array) &&
			count($array)>0) {
			$params = array();
			foreach($array as $key=>$value) {
				if (is_array($value)) {
					$params[] = $key . '[]=' . urlencode($value);
				} else {
					$params[] = $key . '=' . urlencode($value);
				}
			}
			return implode('&', $params);
		}
		return NULL;
	}

	/**
	 * Returns an converted Array array(1=>array('id'=>'lala', 'name'=>'peter')) to array('lala'=>'peter')
	 *
	 * @param array $array
	 * @param string $key
	 * @param string $value
	 * @return array
	 */
	public static function arrayIdToKeyValue($array = array(), $key = '', $value = '') {
		$tmpArray = array();

		if (is_array($array) &&
			count($array) > 0) {

			foreach ($array as $i => $arrayKeyValue) {
				if (isset($arrayKeyValue[$key]) &&
					isset($arrayKeyValue[$value])) {

					$tmpArray[$arrayKeyValue[$key]] = $arrayKeyValue[$value];
				}
			}
		}
		return $tmpArray;
	}

	/**
	 *
	 *
	 * @param $requiredKeys
	 * @param $compareArray
	 * @param $flip
	 * @return boolean
	 */
	public static function arrayKeysExists($requiredKeys = array(), $compareArray = array()) {
		$returnValue = FALSE;

		if (!is_array($requiredKeys)) {
			$requiredKeys = array($requiredKeys);
		}
		$calculatedRequiredKeysArray = array();
		foreach ($requiredKeys as $key) {
			$calculatedRequiredKeysArray[$key] = NULL;
		}

		if (is_array($compareArray)) {
			if (count(array_intersect_key($calculatedRequiredKeysArray, $compareArray)) === count($calculatedRequiredKeysArray)) {
				$returnValue = TRUE;
			}
		}

		return $returnValue;
	}

	/**
	 * Checks, if a directory exists and creates it if the second parameter is
	 * set to TRUE.
	 *
	 * @param  string $directory
	 * @param  bool $enforceCreation
	 * @return bool
	 */
	public static function directoryExists($directory = NULL, $enforceCreation = FALSE)
	{
		if (!is_string($directory)) {
			return NULL;
		}

		if ($enforceCreation == TRUE &&
			!file_exists($directory)) {
			return mkdir($directory, 755, TRUE);
		}

		return (file_exists($directory) && is_dir($directory));

	}

	/**
	 * Returns a string with the first character of str,
	 * lowercased if that character is alphabetic.
	 * Workaround for PHP < 5.3
	 *
	 * @param string $charString
	 * @return string
	 */
	public static function lcFirst($charString) {
		$charString{0} = strtolower($charString{0});
		return $charString;
	}

	/**
	 * retrieve language
	 *
	 * @return string
	 */
	public static function getLanguage()
	{
		/**
		 * language
		 */
		$language = Zend_Registry::isRegistered('Zend_Locale')
				  ? Zend_Registry::get('Zend_Locale')->getLanguage()
				  : NULL
		;
		return $language;
	}

	/**
	 * create not existing short
	 *
	 * @param Default_Model_Base_Abstract $model
	 * @param string $column
	 * @param string $value
	 * @param int $columnLength
	 * @param boolean $withFileExtension
	 * @param int $counter
	 * @return string
	 */
	public static function createShort($model = NULL, $column = NULL, $value = NULL, $columnLength = NULL, $withFileExtension = FALSE, $counter = 0)
	{
		$returnValue = NULL;

		$valueExtension = NULL;
		if ($withFileExtension) {
			$valueArray = explode('.', $value);
			if (count($valueArray) >= 2) {

				$valueExtension = '.' . $valueArray[count($valueArray) - 1];
				unset($valueArray[count($valueArray) - 1]);
				$value = implode('.', $valueArray);
			}
		}

		$valueName = L8M_Library::getUsableUrlStringOnly($value);

		if ($counter > 1) {
			$oldCounterString = (string) ($counter - 1);
			$oldCounterString = '_' . $oldCounterString;
			$valueName = substr($valueName, 0, strlen($valueName) - strlen($oldCounterString));

			$counterString = (string) $counter;
			$counterString = '_' . $counterString;
			$usableUrlString = substr($valueName, 0, $columnLength - strlen($counterString) - strlen($valueExtension)) . $counterString . $valueExtension;
		} else
		if ($counter == 1) {
			$counterString = (string) $counter;
			$counterString = '_' . $counterString;
			$usableUrlString = substr($valueName, 0, $columnLength - strlen($counterString) - strlen($valueExtension)) . $counterString . $valueExtension;
		} else {
			$usableUrlString = substr($valueName, 0, $columnLength - strlen($valueExtension)) . $valueExtension;
		}

		if (!($model instanceof Default_Model_Base_Abstract) &&
			is_string($model) &&
			substr($model, 0, strlen('Default_Model_')) == 'Default_Model_' &&
			class_exists($model, TRUE)) {

			$model = new $model();
		}

		if (!($model instanceof Default_Model_Base_Abstract)) {
			throw new L8M_Exception('Model needs to be child of Default_Model_Base_Abstract.');
		}

		$className = get_class($model);
		$tryArray = Doctrine_Query::create()
			->from($className . ' m')
			->where('m.' . $column . ' = ? ',array($usableUrlString))
			->limit(1)
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute()
		;

		if (is_array($tryArray) &&
			count($tryArray) > 0) {

			if ($tryArray[0]['m_id'] == $model->id) {
				$returnValue = $usableUrlString;
			} else {
				$returnValue = L8M_Library::createShort($model, $column, $usableUrlString, $columnLength, $withFileExtension, ++$counter);
			}
		} else {
			$returnValue = $usableUrlString;
		}
		return $returnValue;
	}

	/**
	 * Create not existing ushort for a langKey.
	 * There is no validation if Translation is existing, 'cause of the runtime.
	 *
	 * @param Default_Model_Base_Abstract $model
	 * @param string $langKey
	 * @param string $value
	 * @param int $columnLength
	 * @param int $counter
	 * @return string
	 */
	public static function createUShort($model = NULL, $langKey = NULL, $value = NULL, $columnLength = NULL, $counter = 0)
	{
		$returnValue = NULL;

		$valueName = L8M_Library::getUsableUrlStringOnly($value);

		if ($counter > 1) {
			$oldCounterString = (string) ($counter - 1);
			$oldCounterString = '_' . $oldCounterString;
			$valueName = substr($valueName, 0, strlen($valueName) - strlen($oldCounterString));

			$counterString = (string) $counter;
			$counterString = '_' . $counterString;
			$usableUrlString = substr($valueName, 0, $columnLength - strlen($counterString)) . $counterString;
		} else
		if ($counter == 1) {
			$counterString = (string) $counter;
			$counterString = '_' . $counterString;
			$usableUrlString = substr($valueName, 0, $columnLength - strlen($counterString)) . $counterString;
		} else {
			$usableUrlString = substr($valueName, 0, $columnLength);
		}

		if (!($model instanceof Default_Model_Base_Abstract) &&
			is_string($model) &&
			substr($model, 0, strlen('Default_Model_')) == 'Default_Model_' &&
			class_exists($model, TRUE)) {

			$model = new $model();
		}

		if (!($model instanceof Default_Model_Base_Abstract)) {
			throw new L8M_Exception('Model needs to be child of Default_Model_Base_Abstract.');
		}

		$className = get_class($model);
		$tryModel = Doctrine_Query::create()
			->from($className . ' m')
			->leftJoin('m.Translation mt')
			->where('m.id <> ? ',array($model->id))
			->addWhere('mt.ushort = ? AND mt.lang = ? ',array($usableUrlString, $langKey))
			->limit(1)
			->execute()
			->getFirst()
		;

		if ($tryModel) {
			$tryModel->free(TRUE);
			$returnValue = L8M_Library::createUShort($model, $langKey, $usableUrlString, $columnLength, ++$counter);
		} else {
			$returnValue = $usableUrlString;
		}
		return $returnValue;
	}

	/**
	 * parse to latin chars only without whitspace
	 *
	 * @return string
	 */
	public static function getUsableUrlStringOnly($charString = '', $whitespace = '-', $allowedChars = array(), $replaceChars = array(), $stringToLower = TRUE)
	{
		if (count($allowedChars) == 0) {
			if ($stringToLower) {
				$allowedChars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '-', '_');
			} else {
				$allowedChars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '-', '_');
			}
		}

		if (count($replaceChars) == 0) {
			if ($stringToLower) {
				$replaceChars = array(
					'ä'=>'ae',
					'ö'=>'oe',
					'ü'=>'ue',
					'ß'=>'ss',
					'é'=>'e',
					'ê'=>'e',
					'è'=>'e',
					'ó'=>'o',
					'ô'=>'o',
					'ò'=>'o',
					'ú'=>'u',
					'û'=>'u',
					'ù'=>'u',
					'á'=>'a',
					'â'=>'a',
					'à'=>'a',
					'ñ'=>'n',
					'í'=>'i',
					'î'=>'i',
					'ì'=>'i',
					'€'=>'euro',
					'@'=>'at',
					'$'=>'dollar',
					'#'=>'number_sign',
					'%'=>'percent',
					'§'=>'paragraph',
				);
			} else {
				$replaceChars = array(
					'ä'=>'ae',
					'ö'=>'oe',
					'ü'=>'ue',
					'ß'=>'ss',
					'é'=>'e',
					'ê'=>'e',
					'è'=>'e',
					'ó'=>'o',
					'ô'=>'o',
					'ò'=>'o',
					'ú'=>'u',
					'û'=>'u',
					'ù'=>'u',
					'á'=>'a',
					'â'=>'a',
					'à'=>'a',
					'ñ'=>'n',
					'í'=>'i',
					'î'=>'i',
					'ì'=>'i',
					'Ä'=>'ae',
					'Ö'=>'oe',
					'Ü'=>'ue',
					'É'=>'e',
					'Ê'=>'e',
					'È'=>'e',
					'Ó'=>'o',
					'Ô'=>'o',
					'Ò'=>'o',
					'Ú'=>'u',
					'Û'=>'u',
					'Ù'=>'u',
					'Á'=>'a',
					'Â'=>'a',
					'À'=>'a',
					'Ñ'=>'n',
					'Í'=>'i',
					'Î'=>'i',
					'Ì'=>'i',
					'€'=>'euro',
					'@'=>'at',
					'$'=>'dollar',
					'#'=>'number_sign',
					'%'=>'percent',
					'§'=>'paragraph',
				);
			}
		}

		if (is_string($charString)) {

			$newCharString = '';
			for ($i = 0; $i < mb_strlen($charString, 'UTF-8'); $i++) {
				if ($stringToLower) {
					$char = mb_strtolower(mb_substr($charString, $i, 1, 'UTF-8'), 'UTF-8');
				} else {
					$char = mb_substr($charString, $i, 1, 'UTF-8');
				}
				if (in_array($char, $allowedChars)) {
					$newCharString .= $char;
				} else {
					if (array_key_exists($char, $replaceChars)) {
						$newCharString .= $replaceChars[$char];
					} else {
						$newCharString .= $whitespace;
					}
				}
			}
		} else {
			$newCharString = $charString;
		}

		$newCharString = preg_replace('/--+/', '-', $newCharString);
		$newCharString = preg_replace('/__+/', '_', $newCharString);

		if (substr($newCharString, -1) == $whitespace ||
			substr($newCharString, -1) == '-' ||
			substr($newCharString, -1) == '_') {

			$newCharString = substr($newCharString, 0, -1);
		}


		if (substr($newCharString, 0, 1) == $whitespace ||
			substr($newCharString, 0, 1) == '-' ||
			substr($newCharString, 0, 1) == '_') {

			$newCharString = substr($newCharString, 1);
		}

		return $newCharString;
	}

	/**
	 * Generate PasswordHash for saving into DB
	 *
	 * @param string $password
	 * @return string
	 */
	public static function generateDBPasswordHash ($password)
	{
		$possibleSalts = '123467890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$rand = rand(0, strlen($possibleSalts) - 1);
		$salt = substr($possibleSalts, $rand, 1);

		return md5($salt . $password) . ':' . $salt;
	}

	/**
	 * Check PasswordHash with Password
	 *
	 * @param string $passwordHash
	 * @param string $password
	 * @return boolean
	 */
	public static function checkPasswordHash ($passwordHash, $password)
	{
		$returnValue = FALSE;

		$passwordHashStack = explode(':', $passwordHash);

		/**
		 * check the stacked hashes
		 * needed, cause of the salt password hashes
		 */
		if (is_array($passwordHashStack)) {
			if (count($passwordHashStack) == 2 &&
				isset($passwordHashStack[0]) &&
				isset($passwordHashStack[1])) {

				if (md5($passwordHashStack[1] . $password) == $passwordHashStack[0]) {
					$returnValue = TRUE;
				}
			} else {
				if (count($passwordHashStack) == 1 &&
					isset($passwordHashStack[0]) &&
					$passwordHashStack[0] == md5($password)) {

					$returnValue = TRUE;
				}
			}
		}

		return $returnValue;
	}

	public static function generatePassword ($length = 8)
	{

		/**
		 * start with a blank password
		 */
		$password = '';

		/**
		 * define possible characters - any character in this string can be
		 * picked for use in the password, so if you want to put vowels back in
		 * or add special characters such as exclamation marks, this is where
		 * you should do it
		 */
		$possible = '2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ';

		/**
		 * we refer to the length of $possible a few times, so let's grab it now
		 */
		$maxlength = strlen($possible);

		/**
		 * check for length overflow and truncate if necessary
		 */
		if ($length > $maxlength) {
			$length = $maxlength;
		}

		/**
		 * set up a counter for how many characters are in the password so far
		 */
		$i = 0;

		/**
		 * add random characters to $password until $length is reached
		 */
		while ($i < $length) {

			/**
			 * pick a random character from the possible ones
			 */
			$char = substr($possible, mt_rand(0, $maxlength-1), 1);

			/**
			 * have we already used this character in $password?
			 */
			if (!strstr($password, $char)) {

				/**
				 * no, so it's OK to add it onto the end of whatever we've already got...
				 */
				$password .= $char;

				/**
				 * ... and increase the counter by one
				 */
				$i++;
			}

		}

		/**
		 * done!
		 */
		return $password;
	}

	/**
	 * This function will return true is the string contains html tags and false otherwise
	 *
	 * @param string $str
	 * @return boolean
	 */
	public static function hasHtml($str){
		$returnValue = FALSE;

		/**
		 * we compare the length of the string with html tags and without html tags
		 */
		if (mb_strlen($str) != mb_strlen(html_entity_decode(strip_tags($str)))) {
			$returnValue = TRUE;
		}
		return $returnValue;
	}

	/**
	 * Remove empty items from array
	 *
	 * @param array $arr
	 * @return array
	 */
	public static function removeEmptyArrayKeys($arr) {
		foreach ($arr as $key => $value) {
			if (is_array($arr[$key])) {
				foreach ($arr[$key] as $key2 => $value2) {
					if (empty($arr[$key][$key2])) {
						unset($arr[$key][$key2]);
					}
				}
			}
			if (empty($arr[$key])) {
				unset($arr[$key]);
			}
		}
		return $arr;
	}

	/**
	 * Retrieve Ping of a Domain
	 *
	 * @param String $domain
	 * @param integer $timeout
	 * @return boolean|integer
	 */
	public static function getPing($domain, $timeout = 1) {
		$starttime = microtime(TRUE);
		$file = @fsockopen ($domain, 80, $errno, $errstr, $timeout);
		$stoptime = microtime(TRUE);
		$status = 0;

		if (!$file) {
			$status = FALSE;  // Site is down
		}else {
			fclose($file);
			$status = ($stoptime - $starttime) * 1000;
			$status = floor($status);
		}
		return $status;
	}

	/**
	 * Checks, whether IPV4 is part of subnet or not.
	 *
	 * @param String $ip
	 * @param integer $subnet
	 * @return boolean
	 */
	public static function isSubnetIP($ip, $subnet) {
		$returnValue = FALSE;

		$subnetParts = explode('/', $subnet);
		if (count($subnetParts) == 2) {
			$subnetIpParts = explode('.', $subnetParts[0]);
			$ipParts = explode('.', $ip);

			if (count($ipParts) == 4 &&
				count($subnetIpParts) == 4) {

				if ($subnetParts[1] == 24 &&
					$ipParts[0] == $subnetIpParts[0] &&
					$ipParts[1] == $subnetIpParts[1] &&
					$ipParts[2] == $subnetIpParts[2] &&
					$subnetIpParts[3] >= 0 &&
					$subnetIpParts[3] <= 255) {

					$returnValue = TRUE;
				} else
				if ($subnetParts[1] == 16 &&
					$ipParts[0] == $subnetIpParts[0] &&
					$ipParts[1] == $subnetIpParts[1] &&
					$subnetIpParts[2] >= 0 &&
					$subnetIpParts[2] <= 255 &&
					$subnetIpParts[3] >= 0 &&
					$subnetIpParts[3] <= 255) {

					$returnValue = TRUE;
				} else
				if ($subnetParts[1] == 8 &&
					$ipParts[0] == $subnetIpParts[0] &&
					$subnetIpParts[1] >= 0 &&
					$subnetIpParts[1] <= 255 &&
					$subnetIpParts[2] >= 0 &&
					$subnetIpParts[2] <= 255 &&
					$subnetIpParts[3] >= 0 &&
					$subnetIpParts[3] <= 255) {

					$returnValue = TRUE;
				} else
				if ($subnetParts[1] == 4 &&
					$subnetIpParts[0] >= 0 &&
					$subnetIpParts[0] <= 255 &&
					$subnetIpParts[1] >= 0 &&
					$subnetIpParts[1] <= 255 &&
					$subnetIpParts[2] >= 0 &&
					$subnetIpParts[2] <= 255 &&
					$subnetIpParts[3] >= 0 &&
					$subnetIpParts[3] <= 255) {

					$returnValue = TRUE;
				}
			}
		}

		return $returnValue;
	}

	/**
	 * Returns true if $string is valid UTF-8 and false otherwise.
	 *
	 * @param String $string
	 * @return boolean
	 */
	public static function isUTF8($string) {

		$returnValue = FALSE;
		if (is_string($string)) {
			// From http://w3.org/International/questions/qa-forms-utf-8.html
			$returnValue = preg_match('%^(?:
				  [\x09\x0A\x0D\x20-\x7E]			# ASCII
				| [\xC2-\xDF][\x80-\xBF]			# non-overlong 2-byte
				|  \xE0[\xA0-\xBF][\x80-\xBF]		# excluding overlongs
				| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}	# straight 3-byte
				|  \xED[\x80-\x9F][\x80-\xBF]		# excluding surrogates
				|  \xF0[\x90-\xBF][\x80-\xBF]{2}	# planes 1-3
				| [\xF1-\xF3][\x80-\xBF]{3}			# planes 4-15
				|  \xF4[\x80-\x8F][\x80-\xBF]{2}	# plane 16
			)*$%xs', $string);
		}

		if ($returnValue) {
			$returnValue = TRUE;
		} else {
			$returnValue = FALSE;
		}

		return $returnValue;
	}

	/**
	 * Replaces HTML entities with chars
	 * This leads to the problem, that "html_entity_decode()" does not really work fine in all PHP Versions.
	 *
	 * @param String $string
	 * return String
	 */
	public static function decodeHTMLentities($string) {
		$replaces = array(
			'&quot;'=>'"',		// quotation mark
			'&apos;'=>'\'',		// apostrophe
			'&amp;'=>'&',		// ampersand
			'&lt;'=>'<',		// less-than
			'&gt;'=>'>',		// greater-than
			'&nbsp;'=>' ',		// non-breaking space
			'&iexcl;'=>'¡',		// inverted exclamation mark
			'&cent;'=>'¢',		// cent
			'&pound;'=>'£',		// pound
			'&curren;'=>'¤',	// currency
			'&yen;'=>'¥',		// yen
			'&brvbar;'=>'¦',	// broken vertical bar
			'&sect;'=>'§',		// section
			'&uml;'=>'¨',		// spacing diaeresis
			'&copy;'=>'©',		// copyright
			'&ordf;'=>'ª',		// feminine ordinal indicator
			'&laquo;'=>'«',		// angle quotation mark (left)
			'&not;'=>'¬',		// negation
			'&shy;'=>'—',		// soft hyphen
			'&reg;'=>'®',		// registered trademark
			'&macr;'=>'¯',		// spacing macron
			'&deg;'=>'°',		// degree
			'&plusmn;'=>'±',	// plus-or-minus
			'&sup1;'=>'¹',		// superscript 1
			'&sup2;'=>'²',		// superscript 2
			'&sup3;'=>'³',		// superscript 3
			'&acute;'=>'´',		// spacing acute
			'&micro;'=>'µ',		// micro
			'&para;'=>'¶',		// paragraph
			'&middot;'=>'·',	// middle dot
			'&cedil;'=>'¸',		// spacing cedilla
			'&ordm;'=>'º',		// masculine ordinal indicator
			'&raquo;'=>'»',		// angle quotation mark (right)
			'&frac14;'=>'¼',	// fraction 1/4
			'&frac12;'=>'½',	// fraction 1/2
			'&frac34;'=>'¾',	// fraction 3/4
			'&iquest;'=>'¿',	// inverted question mark
			'&times;'=>'×',		// multiplication
			'&divide;'=>'÷',	// division
			'&Agrave;'=>'À',	// capital a, grave accent
			'&Aacute;'=>'Á',	// capital a, acute accent
			'&Acirc;'=>'Â',		// capital a, circumflex accent
			'&Atilde;'=>'Ã',	// capital a, tilde
			'&Auml;'=>'Ä',		// capital a, umlaut mark
			'&Aring;'=>'Å',		// capital a, ring
			'&AElig;'=>'Æ',		// capital ae
			'&Ccedil;'=>'Ç',	// capital c, cedilla
			'&Egrave;'=>'È',	// capital e, grave accent
			'&Eacute;'=>'É',	// capital e, acute accent
			'&Ecirc;'=>'Ê',		// capital e, circumflex accent
			'&Euml;'=>'Ë',		// capital e, umlaut mark
			'&Igrave;'=>'Ì',	// capital i, grave accent
			'&Iacute;'=>'Í',	// capital i, acute accent
			'&Icirc;'=>'Î',		// capital i, circumflex accent
			'&Iuml;'=>'Ï',		// capital i, umlaut mark
			'&ETH;'=>'Ð',		// capital eth, Icelandic
			'&Ntilde;'=>'Ñ',	// capital n, tilde
			'&Ograve;'=>'Ò',	// capital o, grave accent
			'&Oacute;'=>'Ó',	// capital o, acute accent
			'&Ocirc;'=>'Ô',		// capital o, circumflex accent
			'&Otilde;'=>'Õ',	// capital o, tilde
			'&Ouml;'=>'Ö',		// capital o, umlaut mark
			'&Oslash;'=>'Ø',	// capital o, slash
			'&Ugrave;'=>'Ù',	// capital u, grave accent
			'&Uacute;'=>'Ú',	// capital u, acute accent
			'&Ucirc;'=>'Û',		// capital u, circumflex accent
			'&Uuml;'=>'Ü',		// capital u, umlaut mark
			'&Yacute;'=>'Ý',	// capital y, acute accent
			'&THORN;'=>'Þ',		// capital THORN, Icelandic
			'&szlig;'=>'ß',		// small sharp s, German
			'&agrave;'=>'à',	// small a, grave accent
			'&aacute;'=>'á',	// small a, acute accent
			'&acirc;'=>'â',		// small a, circumflex accent
			'&atilde;'=>'ã',	// small a, tilde
			'&auml;'=>'ä',		// small a, umlaut mark
			'&aring;'=>'å',		// small a, ring
			'&aelig;'=>'æ',		// small ae
			'&ccedil;'=>'ç',	// small c, cedilla
			'&egrave;'=>'è',	// small e, grave accent
			'&eacute;'=>'é',	// small e, acute accent
			'&ecirc;'=>'ê',		// small e, circumflex accent
			'&euml;'=>'ë',		// small e, umlaut mark
			'&igrave;'=>'ì',	// small i, grave accent
			'&iacute;'=>'í',	// small i, acute accent
			'&icirc;'=>'î',		// small i, circumflex accent
			'&iuml;'=>'ï',		// small i, umlaut mark
			'&eth;'=>'ð',		// small eth, Icelandic
			'&ntilde;'=>'ñ',	// small n, tilde
			'&ograve;'=>'ò',	// small o, grave accent
			'&oacute;'=>'ó',	// small o, acute accent
			'&ocirc;'=>'ô',		// small o, circumflex accent
			'&otilde;'=>'õ',	// small o, tilde
			'&ouml;'=>'ö',		// small o, umlaut mark
			'&oslash;'=>'ø',	// small o, slash
			'&ugrave;'=>'ù',	// small u, grave accent
			'&uacute;'=>'ú',	// small u, acute accent
			'&ucirc;'=>'û',		// small u, circumflex accent
			'&uuml;'=>'ü',		// small u, umlaut mark
			'&yacute;'=>'ý',	// small y, acute accent
			'&thorn;'=>'þ',		// small thorn, Icelandic
			'&yuml;'=>'ÿ',		// small y, umlaut mark
		);

		foreach ($replaces as $key=>$value) {
			$string = str_replace($key, $value, $string);
		}
		return $string;
	}

	public static function mbStrPad($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT, $encoding = NULL) {
		$encoding = $encoding === NULL ? mb_internal_encoding() : $encoding;
		$padBefore = $dir === STR_PAD_BOTH || $dir === STR_PAD_LEFT;
		$padAfter = $dir === STR_PAD_BOTH || $dir === STR_PAD_RIGHT;
		$pad_len -= mb_strlen($str, $encoding);
		$targetLen = $padBefore && $padAfter ? $pad_len / 2 : $pad_len;
		$strToRepeatLen = mb_strlen($pad_str, $encoding);
		$repeatTimes = ceil($targetLen / $strToRepeatLen);
		$repeatedString = str_repeat($pad_str, max(0, $repeatTimes)); // safe if used with valid utf-8 strings
		$before = $padBefore ? mb_substr($repeatedString, 0, floor($targetLen), $encoding) : '';
		$after = $padAfter ? mb_substr($repeatedString, 0, ceil($targetLen), $encoding) : '';
		return $before . $str . $after;
	}

	/**
	 * Returns time difference string from given Values in Y-m-d
	 *
	 * @param string $fromDateString
	 * @param string $toDateString
	 * @return string
	 */
	public static function getTimeDifferenceString($fromDateString, $toDateString = NULL) {

		if (!$toDateString) {
			$toDateTime = date('Y-m-d');
		}

		/**
		 * check from date
		 */
		$fromDateStringArray = explode('-', $fromDateString);
		$toDateStringArray = explode('-', $toDateString);
		if (count($fromDateStringArray) != 3 &&
			count($toDateStringArray) != 3 &&
			checkdate($fromDateStringArray[1], $fromDateStringArray[2], $fromDateStringArray[0]) &&
			checkdate($toDateStringArray[1], $toDateStringArray[2], $toDateStringArray[0])) {

			throw new L8M_Exception('One or more dates do not to match Y-m-d format.');
		}

		$returnValue = NULL;
		$dateValue = NULL;
		$arrayValue = NULL;

		/**
		 * get time difference interval array
		 */
		$toDateTime = new DateTime($toDateString);
		$fromDateTime = new DateTime($toDateTime);
		$intervalArray = explode(',', $toDateTime->diff($fromDateTime)->format('%y,%m,%d'));

		/**
		 * date word array
		 */
		$dateArray = array(
			'Jahren',
			'Jahr',
			'Monaten',
			'Monat',
			'Tagen',
			'Tag',
			'Wochen',
			'Woche',
		);

		/**
		 * look for the first interval greather then 0
		 */
		for ($i = 0; $i < count($intervalArray); $i++) {

			if ($intervalArray[$i] > 0) {
				$dateValue = $intervalArray[$i];
				$j = $i;
				break;
			}
		}

		/**
		 * if $j is NULL, then article ist published today
		 */
		if ($j == NULL) {

			$returnValue = 'Heute';

		} else {

			/**
			 * check for weeks, if value are days and value greater then 6
			 */
			if ($j == 2) {

				if ($dateValue > 6) {

					$dateValue = round($dateValue / 7, 0, PHP_ROUND_HALF_DOWN);
					$j++;
				}

			}

			/**
			 * if date value is 1, get singular date word, else plural dateword
			 */
			if ($dateValue == 1) {
				$arrayValue = $dateArray[(2 * $j) + 1];
			} else {

				$arrayValue = $dateArray[2 * $j];
			}

			$returnValue = 'vor ' . $dateValue . ' ' . $arrayValue;

		}

		return $returnValue;
	}

	/**
	 * Calls function and returns result
	 *
	 * @param String $className
	 * @param String $classFunction
	 * @param Array $parameterArray
	 * @return mixed
	 */
	public static function callStaticFunction($className = 'Foo', $classFunction = 'bar', $parameterArray = array()) {
		$returnValue = NULL;

		if (!is_array($parameterArray)) {
			throw new L8M_Exception('L8M_Library::callStaticFunction need $parameterArray to be an array.');
		}

		if (!class_exists($className, TRUE)) {
			throw new L8M_Exception('L8M_Library::callStaticFunction can not find class: "' . $className . '"');
		}

		try {
			if (version_compare(PHP_VERSION, '5.2.3') >= 0) {
				if (count($parameterArray) > 0) {
					$returnValue = call_user_func($className . '::' . $classFunction, $parameterArray);
				} else {
					$returnValue = call_user_func($className . '::' . $classFunction);
				}
			} else {
				if (count($parameterArray) > 0) {
					$returnValue = call_user_func(array($className, $classFunction), $parameterArray);
				} else {
					$returnValue = call_user_func(array($className, $classFunction));
				}
			}
		} catch (Exception $e) {
			throw new L8M_Exception($e->getMessage());
		}

		return $returnValue;
	}

	/**
	 * Creates and returns a scheme and http host string like 'https://www.l8m.com'.
	 *
	 * @param boolean $withTerminatingSlash
	 * @return String
	 */
	public static function getSchemeAndHttpHost($withTerminatingSlash = FALSE) {
		$returnValue = '';

		/**
		 * @var Zend_Controller_Request_Abstract
		 */
		$requestObject = Zend_Controller_Front::getInstance()->getRequest();
		if ($requestObject instanceof Zend_Controller_Request_Http) {
			$returnValue = $requestObject->getScheme() . '://' . $requestObject->getHttpHost();
			if ($withTerminatingSlash) {
				$returnValue .= '/';
			}
		}

		return $returnValue;
	}

	/**
	 * Checkes whether HttpHost is secure or not.
	 *
	 * @return boolean
	 */
	public static function isHttpHostSecure() {
		$returnValue = FALSE;

		/**
		 * @var Zend_Controller_Request_Abstract
		 */
		$requestObject = Zend_Controller_Front::getInstance()->getRequest();
		if ($requestObject instanceof Zend_Controller_Request_Http) {
			$returnValue = $requestObject->isSecure();
		}

		return $returnValue;
	}

	/**
	 * Function $name exists.
	 * Function is not disabled.
	 * Safe Mode is not on.
	 *
	 * @param String $name
	 */
	public static function functionExists($name) {
		$returnValue = FALSE;

		if (function_exists($name) &&
			!in_array($name, array_map('trim', explode(', ', ini_get('disable_functions')))) &&
			strtolower(ini_get('safe_mode')) != 1) {

			$returnValue = TRUE;
		}

		return $returnValue;
	}
}