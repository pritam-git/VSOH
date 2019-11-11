<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Google/Maps/LatLong.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: LatLng.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Google_Maps_LatLng
 *
 *
 */
class L8M_Google_Maps_LatLng extends L8M_Google_Maps_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

   /**
	 * The base name of instances of this type.
	 *
	 * @var string
	 */
	protected $_instanceBaseName = 'latLng';

	/**
	 * The latitude of the point.
	 *
	 * @var string
	 */
	protected $_latitude = 0;

	/**
	 * Enter description here...
	 *
	 * @var string
	 */
	protected $_longitude = 0;

	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	protected $_address;

	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	protected $_accuracy;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Google_Maps_LatLong instance.
	 *
	 * @return void
	 */
	public function __construct($latitude = NULL, $longitude = NULL)
	{
		if (func_num_args()==1) {
			$this->setAddress($latitude);
		} else
		if (func_num_args()==2) {
			$this->setLatitude($latitude);
			$this->setLongitude($longitude);
		}
	}

	/**
	 *
	 *
	 * Class Setters
	 *
	 *
	 */

	/**
	 * Sets latitude.
	 *
	 * @param  string $latitude
	 * @return L8M_Google_Maps_LatLong
	 */
	public function setLatitude($latitude = NULL)
	{
		$this->_latitude = $latitude;
		return $this;
	}

	/**
	 * Sets longitude.
	 *
	 * @param  string $longitude
	 * @return L8M_Google_Maps_LatLong
	 */
	public function setLongitude($longitude = NULL)
	{
		$this->_longitude = $longitude;
		return $this;
	}

	/**
	 * Sets address.
	 *
	 * @param  string $address
	 * @return L8M_Google_Maps_LatLong
	 */
	public function setAddress($address = NULL)
	{
		$this->_address = $address;
		return $this;
	}

	/**
	 * Sets accuracy.
	 *
	 * @param  string $accuracy
	 * @return L8M_Google_Maps_LatLong
	 */
	public function setAccuracy($accuracy = NULL)
	{
		$this->_accuracy = $accuracy;
		return $this;
	}

	/**
	 *
	 *
	 * Getter Methods
	 *
	 *
	 */

	/**
	 * Returns accuracy.
	 *
	 * @return int
	 */
	public function getAccuracy()
	{
		return $this->_accuracy;
	}

	/**
	 * Returns address
	 *
	 * @return string
	 */
	public function getAddress()
	{
		return $this->_address;
	}

	/**
	 * Returns latitude.
	 *
	 * @return string
	 */
	public function getLatitude()
	{
		return $this->_latitude;
	}

	/**
	 * Returns longitude
	 *
	 * @return string
	 */
	public function getLongitude()
	{
		return $this->_longitude;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns L8M_Google_Maps_LatLong instance from address.
	 *
	 * @param  string|array $address
	 * @return L8M_Google_Maps_LatLng
	 */
	public static function fromAddress($address = NULL)
	{
		return L8M_Google_Maps_Api::getInstance()->geoCodeRequest($address);
	}

	/**
	 * Returns L8M_Google_Maps_LatLong instance from latitude and longitude.
	 *
	 * @param  string $latitude
	 * @param  string $longitude
	 * @return L8M_Google_Maps_LatLng
	 */
	public static function fromLatitudeLongitude($latitude = NULL, $longitude = NULL)
	{
		return L8M_Google_Maps_Api::getInstance()->reverseGeoCodeRequest($latitude, $longitude);
	}

	/**
	 * Returns L8M_Google_Maps_LatLng instance that specifies the center of
	 * the passed array of L8M_Google_Maps_Overlay_Abstract or
	 * L8M_Google_Maps_LatLng instances.
	 *
	 * @param  array $overlays
	 * @return L8M_Google_Maps_LatLng
	 * @todo   calculation
	 * @todo   optional calculation of center of gravity
	 */
	public static function fromOverlays($overlays = array())
	{
		return NULL;
	}

	/**
	 * Creates and initializes a Javascript variable reflected by this class.
	 *
	 * @todo   further assign specified options to icon instance
	 * @return string
	 */
	public function createAndInitialize()
	{
		parent::createAndInitialize();
		ob_start();
?>
var <?php echo $this->getInstanceName(); ?> = new google.maps.LatLng(<?php echo $this->getLatitude(); ?>,<?php echo $this->getLongitude(); ?>);
<?php
		return ob_get_clean();

	}

}