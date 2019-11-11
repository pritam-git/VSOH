<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Google/Maps/Api.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Api.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Google_Maps_Api
 *
 *
 */
class L8M_Google_Maps_Api
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * The URI to which geocode requests are sent.
	 */
	const URI_GEOCODE_REQUEST = 'http://maps.google.com/maps/geo';

	/**
	 * The version of the GoogleMaps Api used.
	 */
	const VERSION = '2';

	/**
	 * If used as a return format for geocode requests, data will be returned as
	 * XML.
	 */
	const FORMAT_XML = 'xml';

	/**
	 * If used as a return format for geocode requests, data will be returned as
	 * KML.
	 */
	const FORMAT_KML = 'kml';

	/**
	 * If used as a return format for geocode requests, data will be returned as
	 * comma separated values.
	 */
	const FORMAT_CSV = 'csv';

	/**
	 * If used as a return format for geocode requests, data will be returned as
	 * JSON.
	 */
	const FORMAT_JSON = 'json';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An L8M_Google_Maps_Api instance.
	 *
	 * @var L8M_Google_Maps_Api
	 */

	protected static $_instance = NULL;

	/**
	 * A string representing the GoogleMaps API key.
	 *
	 * @var string
	 */
	protected static $_apiKey = NULL;

	/**
	 * A Zend_Cache instance
	 *
	 * @var Zend_Cache
	 */
	protected static $_cache = NULL;

	/**
	 * An array with options for on-the-fly creation of a Zend_Cache instance.
	 *
	 * @var array
	 */
	protected static $_cacheOptions = NULL;

	/**
	 * Encoding to be used for returning data from GoogleMaps.
	 *
	 * @var string
	 */
	protected $_encoding = 'utf8';

	/**
	 * Output format to be used for returning data from GoogleMaps.
	 *
	 * @var string
	 */
	protected $_outputFormat = self::FORMAT_JSON;

	/**
	 * An array of allowed output formats.
	 *
	 * @var array
	 */
	protected $_outputFormats = array(self::FORMAT_CSV,
									  self::FORMAT_JSON,
									  self::FORMAT_KML,
									  self::FORMAT_XML);

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Google_Maps_Api instance.
	 *
	 * @param  string $apiKey
	 * @return void
	 */
	protected function __construct($apiKey = NULL)
	{
		$this->setApiKey($apiKey);
	}

	/**
	 *
	 *
	 * Class Setters
	 *
	 *
	 */

	/**
	 * Sets API key for GoogleMaps.
	 *
	 * @param  string $apiKey
	 * @return void
	 */
	public static function setApiKey($apiKey = NULL)
	{
		self::$_apiKey = (string) $apiKey;
	}

	/**
	 * Sets cache instance if it inherits from Zend_Cache.
	 *
	 * @param  Zend_Cache $cache
	 * @return void
	 */
	public static function setCache($cache = NULL)
	{
		if (is_object($cache) &&
			substr(get_class($cache), 0, 10) == 'Zend_Cache') {
			self::$_cache = $cache;
		}
	}

	/**
	 * Sets cache options for on-the-fly creation of Zend_Cache instance. Unsets
	 * cache instance, if one has already been assigned.
	 *
	 * @param  array|Zend_Config $options
	 * @return void
	 */
	public static function setCacheOptions($options = NULL)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		if (!is_array($options)) {
			throw new L8M_Google_Maps_Api_Exception('Cache options need to be specified as an array or a Zend_Config instance.');
		}
		self::$_cacheOptions = $options;
	}

	/**
	 * Sets encoding for data returned by GoogleMaps.
	 *
	 * @param  string $encoding
	 * @return L8M_Google_Maps_Api
	 */
	public function setEncoding($encoding = NULL)
	{
		$this->_encoding = (string) $encoding;
		return $this;
	}

	/**
	 * Sets output format for data returned by GoogleMaps.
	 *
	 * @param  string $outputFormat
	 * @return L8M_Google_Maps_Api
	 */
	public function setOutputFormat($outputFormat = NULL)
	{
		$outputFormat = (string) $outputFormat;
		if (in_array($outputFormat, $this->_outputFormats)) {
			$this->_outputFormat = $outputFormat;
		}
		return $this;
	}

	/**
	 *
	 *
	 * Class Getters
	 *
	 *
	 */

	/**
	 * Returns GoogleMaps API key.
	 *
	 * @return string
	 */
	public static function getApiKey()
	{
		return self::$_apiKey ;
	}

	/**
	 * Returns Zend_Cache instance if there is one present.
	 *
	 * @return Zend_Cache
	 */
	public function getCache()
	{
		if (self::$_cache === NULL &&
			self::$_cacheOptions) {
			self::$_cache = Zend_Cache::factory(self::$_cacheOptions['frontend']['name'],
												self::$_cacheOptions['backend']['name'],
												self::$_cacheOptions['frontend']['options'],
												self::$_cacheOptions['backend']['options']);
		}
		return self::$_cache;
	}

	/**
	 * Generates cache id from string.
	 *
	 * @param  string $string
	 * @return string
	 */
	public function getCacheId($string = NULL)
	{
		return L8M_Library::getCacheId($string, get_class($this));
	}

	/**
	 * Returns encoding.
	 *
	 * @return string
	 */
	public function getEncoding()
	{
		return $this->_encoding;
	}

	/**
	 * Returns output format.
	 *
	 * @return string
	 */
	public function getOutputFormat()
	{
		return $this->_outputFormat;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns L8M_Google_Maps_Api instance.
	 *
	 * @return L8M_Google_Maps_Api
	 */
	public static function getInstance()
	{
		if (self::$_instance===NULL) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * Requests GoogleMaps with the specified address and returns an array
	 * containing the latitude and longitude of the address on success.
	 *
	 * @param  string|array $address
	 * @return L8M_Google_Maps_LatLong
	 */
	public function geoCodeRequest($address = NULL)
	{
		if (is_array($address)) {
			$address = implode(',', $address);
		}
		$address = trim((string) $address);
		if ($address) {
			/**
			 * caching
			 */
			if ($this->isCacheEnabled() &&
				($latLng = $this->getCache()->load($this->getCacheId($address)))) {
				return $latLng;
			}
			$params = array('q'=>$address,
							'key'=>self::getApiKey(),
							'sensor'=>$this->clientHasSensor() ? 'true' : 'false',
							'output'=>$this->getOutputFormat(),
							'oe'=>$this->getEncoding(),
							'gl'=>'de');
			$response = @file_get_contents(self::URI_GEOCODE_REQUEST . '?' . L8M_Library::arrayToUrlParams($params));
			if ($response) {
				if ($this->getOutputFormat()==self::FORMAT_CSV) {
					list($status, $accuracy, $latitude, $longitude) = explode(',', $response);
				} else {
					if ($this->getOutputFormat()==self::FORMAT_JSON) {
						$response = Zend_Json::decode($response);
					} else
					if (in_array($this->getOutputFormat(), array(self::FORMAT_KML, self::FORMAT_XML))) {
						$response = Zend_Json::fromXml($response);
					}
					$status = $response['Status']['code'];
					$latitude = $response['Placemark'][0]['Point']['coordinates'][1];
					$longitude = $response['Placemark'][0]['Point']['coordinates'][0];
					$address = $response['Placemark'][0]['address'];
					$accuracy = $response['Placemark'][0]['AddressDetails']['Accuracy'];
				}
				if ($status=='200') {
					$latLng = new L8M_Google_Maps_LatLng();
					$latLng->setAddress($address)
						   ->setLatitude($latitude)
						   ->setLongitude($longitude)
						   ->setAccuracy($accuracy);
					/**
					 * caching
					 */
					if ($this->isCacheEnabled()) {
						$this->getCache()->save($latLng);
					}
		   			return $latLng;
				}
			}
		}
		return NULL;
	}

	/**
	 * Performs a reverse geocode lookup.
	 *
	 * @param  string $latitude
	 * @param  string $longitude
	 * @return L8M_Google_Maps_LatLng
	 */
	public function reverseGeoCodeRequest($latitude = NULL, $longitude = NULL)
	{
		return $this->geoCodeRequest(array($latitude, $longitude));
	}

	/**
	 * Returns TRUE if client is equipped with a GPS sensor.
	 *
	 * @return bool
	 */
	public function clientHasSensor()
	{
		return FALSE;
	}

	/**
	 * Returns TRUE if a Zend_Cache instance is present.
	 *
	 * @return bool
	 */
	public function isCacheEnabled()
	{
		return (substr(get_class($this->getCache()), 0, 10)=='Zend_Cache');
	}

}