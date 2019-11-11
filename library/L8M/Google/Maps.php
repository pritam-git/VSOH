<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Google/Maps.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Maps.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Google_Maps
 *
 *
 */
class L8M_Google_Maps extends L8M_Google_Maps_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * Specifies the default zoom level.
	 */
	const DEFAULT_ZOOM_LEVEL = 15;
	/**
	 * A map will be rendered.
	 */
	const MODE_MAP = 'map';
	/**
	 * A map will be rendered, but additionally it will be allowed to enter an
	 * address to route from.
	 */
	const MODE_ROUTE = 'route';
	/**
	 * A string specifying the URI of the GoogleMaps API.
	 */
	const API_SOURCE = 'http://maps.google.com/maps/api/js?sensor=false';

	const MAP_TYPE_ROADMAP = 'google.maps.MapTypeId.ROADMAP';
	const MAP_TYPE_SATELLITE = 'google.maps.MapTypeId.SATELLITE';
	const MAP_TYPE_HYBRID = 'google.maps.MapTypeId.HYBRID';
	const MAP_TYPE_TERRAIN = 'google.maps.MapTypeId.TERRAIN';

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
	protected $_instanceBaseName = 'map';

	/**
	 * Contains the map bounds.
	 *
	 * @var L8M_Google_Maps_LatLongBounds
	 */
	protected $_bounds = NULL;

	/**
	 * Contains the location to center around on the map.
	 *
	 * @var L8M_Google_Maps_LatLong
	 */
	protected $_center = NULL;

	/**
	 * An array of L8M_Google_Maps_Control instances.
	 *
	 * @var array
	 */
	protected $_controls = array();

	/**
	 * Contains the id of the div that is supposed to hold the map.
	 *
	 * @var string
	 */
	protected $_id = NULL;

	/**
	 * An array of L8M_Google_Maps_Overlay_Abstract instances to display on the
	 * map.
	 *
	 * @var array
	 */
	protected $_overlays = array();

	/**
	 * An array of infoWindowHTMLs
	 *
	 * @var array
	 */
	protected $_infoWindowHTMLs = array();

	/**
	 * Contains an integer representing the zoom level for the map.
	 *
	 * @var int
	 */
	protected $_zoom = self::DEFAULT_ZOOM_LEVEL;

	/**
	 * An array of unsettable options.
	 *
	 * @var array
	 */
	protected $_unsettableOptions = array('instanceBaseName',
										  'mapIds');

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Google_Maps instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

	}

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Sets bounds of map.
	 *
	 * @param  string|array|L8M_Google_Maps_LatLong $center
	 * @return L8M_Google_Maps
	 */
	public function setBounds($bounds = NULL)
	{
		if ($bounds instanceof L8M_Google_Maps_LatLngBounds) {
			$this->_bounds = $bounds;
		}
		return $this;
	}

	/**
	 * Sets center of map.
	 *
	 * @param  string|array|L8M_Google_Maps_LatLong $center
	 * @return L8M_Google_Maps
	 */
	public function setCenter($center = NULL)
	{
		if (is_string($center) ||
			is_array($center)) {
		 	$center = L8M_Google_Maps_Api::getInstance()->geoCodeRequest($center);
		}
		if ($center instanceof L8M_Google_Maps_LatLng) {
			$this->_center = $center;
		}
		return $this;
	}

	/**
	 * Sets Id of map container.
	 *
	 * @param  string $id
	 * @return L8M_View_Helper_GoogleMaps
	 */
	public function setId($id = NULL)
	{
		$id = (string) $id;
		$id = strip_tags($id);
		$id = preg_replace('/\s/', '', $id);
		if ($id!=NULL) {
			$this->_id = $id;
		}
		return $this;
	}

	/**
	 * Sets zoom level of map.
	 *
	 * @param  int|string $zoom
	 * @return L8M_Google_Maps
	 */
	public function setZoom($zoom = NULL)
	{
		$this->_zoom = $zoom;
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
	 * Returns L8M_Google_Maps_LatLngBounds instance.
	 *
	 * @return L8M_Google_Maps_LatLngBounds
	 */
	public function getBounds($force = FALSE)
	{
		if ($force===TRUE &&
			!$this->_bounds) {
			$this->_bounds = new L8M_Google_Maps_LatLngBounds();
   		}
		return $this->_bounds;
	}

	/**
	 * Returns center.
	 *
	 * @return L8M_Google_Maps_LatLng
	 */
	public function getCenter()
	{
		return $this->_center;
	}

	/**
	 * Returns an array of L8M_Google_Maps_Control_Abstract instances added to
	 * the map.
	 *
	 * @return array
	 */
	public function getControls()
	{
		return $this->_controls;
	}

	/**
	 * Returns default L8M_Google_Maps_Abstract options as an array.
	 *
	 * @return array
	 */
	public function getDefaultOptions()
	{
		return array_merge(parent::getDefaultOptions(), $this->_defaultOptions);
	}

	/**
	 * Returns id of div container.
	 *
	 * @param  bool $force
	 * @return string
	 */
	public function getId($force = FALSE)
	{
		$force = (bool) $force;
		if (!$this->_id ||
			$force) {
			$this->setId($this->getInstanceName());
		}
		return $this->_id;
	}

	/**
	 * Returns an array of L8M_Google_Maps_Overlay_Abstract instances added to
	 * the map.
	 *
	 * @return array
	 */
	public function getOverlays()
	{
		return $this->_overlays;
	}

	/**
	 * Returns zoom level.
	 *
	 * @return int
	 */
	public function getZoom()
	{
		return $this->_zoom;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Adds L8M_Google_Maps_Control instances.
	 *
	 * @param L8M_Google_Maps_Control_Abstract $control
	 * @return unknown
	 */
	public function addControl($control = NULL)
	{
		if ($control instanceof L8M_Google_Maps_Control_Abstract) {
			$this->_controls[] = $control;
		}
		return $this;
	}

	/**
	 * Adds overlay to map.
	 *
	 * @todo   update map bounds when overlays are added?
	 * @todo   update map bounds when map is rendered and no center or zoom is set?
	 * @param  L8M_Google_Maps_Overlay_Abstract $overlay
	 * @return L8M_Google_Maps
	 */
	public function addOverlay($overlay = NULL)
	{
		if ($overlay instanceof L8M_Google_Maps_Overlay_Abstract) {
			$this->_overlays[] = $overlay;
		}
		return $this;
	}

	/**
	 *
	 * @param L8M_Google_Maps_Overlay_Marker $overlay
	 * @param string $html
	 */
	public function addInfoWindowHtml($overlay = NULL, $html, $showOnStartup = TRUE)
	{
		if ($overlay instanceof L8M_Google_Maps_Overlay_Marker) {
			$html = preg_replace('/\r\n|\r/', "\n", $html);
			$html = str_replace(PHP_EOL, '', $html);
			$this->_infoWindowHTMLs[$overlay->getLatLng()->getLatitude() . 'x' . $overlay->getLatLng()->getLongitude()] = array(
				'overlay'=>$overlay,
				'html'=>$html,
				'showOnStartup'=>$showOnStartup,
			);
		}
		return $this;
	}

	/**
	 * Creates and initializes a Javascript variable reflected by this class.
	 *
	 * @return string
	 */
	public function createAndInitialize()
	{
		parent::createAndInitialize();
		ob_start();

		/**
		 * render initialization function which will be used as a callback when
		 * Google has loaded
		 */

?>
function initialize<?php echo ucfirst($this->getId(TRUE)); ?>() {

<?php

		/**
		 * map
		 */
?>
var <?php echo $this->getInstanceName(); ?> = new google.maps.Map2(document.getElementById("<?php echo $this->getId(); ?>")<?php echo (count($this->getOptions())>0 ? ', {' . $this->getOptionsAsJavascript() . '}' : ''); ?>);

<?php

		/**
		 * bounds
		 */
		$bounds = $this->getBounds(TRUE);
		/*
?>
<?php echo $bounds->createAndInitialize(); ?>
<?php
		*/

		/**
		 * render controls
		 */
		foreach ($this->getControls() as $control) {

			/**
			 * create, initialize and finally add control to map
			 */

			/* @var $control L8M_Google_Maps_Control_Abstract */
?>
<?php echo $control->createAndInitialize(); ?>
<?php echo $this->getInstanceName(); ?>.addControl(<?php echo $control->getInstanceName(); ?>);
<?php
		}

		/**
		 * render overlays
		 */
		foreach ($this->getOverlays() as $overlay) {

			/**
			 * create, initialize and finally add overlay to map
			 */

			/* @var $overlay L8M_Google_Maps_Overlay_Marker */

			if ($overlay instanceof L8M_Google_Maps_Overlay_Marker) {
				$markerIndex = $overlay->getLatLng()->getLatitude() . 'x' . $overlay->getLatLng()->getLongitude();
				if (array_key_exists($markerIndex, $this->_infoWindowHTMLs)) {
					echo $overlay->createAndInitialize($this->getInstanceName(), $this->_infoWindowHTMLs[$markerIndex]['html'], $this->_infoWindowHTMLs[$markerIndex]['showOnStartup']);
				} else {
					echo $overlay->createAndInitialize();
				}
			} else {
				echo $overlay->createAndInitialize();
			}
?>
<?php echo $this->getInstanceName(); ?>.addOverlay(<?php echo $overlay->getInstanceName(); ?>);
<?php



	/*
<?php echo $bounds->getInstanceName(); ?>.extend(<?php echo $overlay->getLatLng()->getInstanceName(); ?>);
	*/
?>
<?php
		}

		/**
		 * center
		 */
		if (!$this->getCenter() &&
			count($this->getOverlays())>0) {
			/**
			 * @todo calculate center
			 */
			$center = 'Berlin';
			$this->setCenter($center);
		}

		$googleMapsLatLng = $this->getCenter();
		if ($googleMapsLatLng) {
			echo $googleMapsLatLng->createAndInitialize();
			echo $this->getInstanceName() . '.setCenter(' . $googleMapsLatLng->getInstanceName() . ');';
		}

		/**
		 * zoom
		 */
		if (!$this->getZoom()  ||
			count($this->getOverlays())>0) {
			/**
			 * @todo calculate map zoom level
			 * @todo set map bounds
			 */
		}

?>
<?php echo $this->getInstanceName(); ?>.setZoom(<?php echo $this->getZoom(); ?>);
<?php

		/**
		 * bounds
		 */

		/*

?>
<?php echo $this->getInstanceName(); ?>.setBounds(<?php echo $this->getBounds()->getInstanceName(); ?>);
<?php

		*/


		/**
		 * add initialization function as call back
		 */
?>

}
google.setOnLoadCallback(initialize<?php echo ucfirst($this->getId()); ?>);
<?php
		return ob_get_clean();
	}

}