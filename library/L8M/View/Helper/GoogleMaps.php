<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/GoogleMaps.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: GoogleMaps.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_GoogleMaps
 *
 *
 */
class L8M_View_Helper_GoogleMaps extends Zend_View_Helper_Abstract
{

    /**
     *
     *
     * Class Constants
     *
     *
     */

	/**
     * Is TRUE when the Google Maps API has been loaded, FALSE otherwise.
     *
     * @var bool
     */
    protected static $_apiLoaded = FALSE;

	/**
	 * An L8M_Google_Maps instance
	 *
	 * @var L8M_Google_Maps
	 */
	protected $_map = NULL;

	/**
	 * An array of CSS classes added to the map.
	 *
	 * @var array
	 */
	protected $_classes = array();

	/**
	 * An array of CSS inline styles added to the map.
	 *
	 * @var array
	 */
	protected $_styles = array();

    /**
     *
     *
     * Setter Methods
     *
     *
     */

	/**
     * Sets Google Maps API as loaded.
     *
     * @return L8M_View_Helper_GoogleMaps
     *
     */
    public function setApiLoaded($loaded = TRUE)
    {
        self::$_apiLoaded = (bool) $loaded;
        return $this;
    }

	/**
     * Sets map instance.
     *
     * @param  L8M_Google_Maps $map
     * @return L8M_View_Helper_GoogleMaps
     */
    public function setMap($map = NULL)
    {
    	if ($map instanceof L8M_Google_Maps) {
    		$this->_map = $map;
    	}
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
     * Returns L8M_Google_Maps instance
     *
     * @param  bool $force
     * @return L8M_Google_Maps
     */
    public function getMap($force = FALSE)
    {
    	if (!$this->_map ||
    		$force==TRUE) {
   			$this->_map = new L8M_Google_Maps();
   		}
   		return $this->_map;
    }

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Renders GoogleMaps or returns L8M_View_Helper_GoogleMaps instance.
     *
     * Note: in all cases where a parameter is passed to this function, a new map
     * instance is generated.
     *
     * @todo   allow multiple map instances to be rendered on one page. As of
     *         the moment, the number of instances is not incremented, and even
     *         though there are multiple divs and initialization functions, they
     *         address the same div. Therefore, only one map is displayed.
     *
     * @param  string|array|L8M_Google_Maps|House $center
     * @param  string                            $mode
     * @param  array                             $options
     * @return string
     */
    public function googleMaps($center = NULL, $mode = L8M_Google_Maps::MODE_MAP, $options = array())
    {

        /**
         * return  L8M_View_Helper_GoogleMaps instance
         */
        if (func_num_args()==0) {
            return $this;
        }

    	/**
         * center is an L8M_Google_Maps instance
         */
        if ($center instanceof L8M_Google_Maps) {
    		$this->setMap($center);
    	} else

		/**
         * center is a House instance
         */
        if ($center instanceof Default_Model_House) {
            $marker = L8M_Google_Maps_Overlay_HouseMarker::fromHouse($center);
            $center = $marker->getLatLng();
    		$this->getMap(TRUE)
    			->setCenter($center)
    		    ->addOverlay($marker)
    		    ->addControl(new L8M_Google_Maps_Control(L8M_Google_Maps_Control::SMALL_ZOOM_CONTROL_3D))
			;
    	} else

    	/**
    	 * center is a string (i.e., an address or a comma-separated
    	 * latitude/longitude-pair or an array(latitude, longitude)
    	 */
    	if (is_string($center) ||
    	    is_array($center)) {
            $center = L8M_Google_Maps_LatLng::fromAddress($center);
            $this->getMap(TRUE)
            	->setCenter($center)
                ->addOverlay(L8M_Google_Maps_Overlay_Marker::fromLatLng($center))
			;
        }

    	/**
    	 * options
    	 */
        $this->getMap()->setOptions($options);

        return $this;
    }

    /**
     * Adds headscript for loading the Google Maps Api if it has not been added
     * yet.
     *
     * @return L8M_View_Helper_GoogleMaps
     */
    public function loadApi()
    {
        /**
         * make sure Google Ajax API is loaded
         */
        $this->view->googleAjax()->loadApi();
        /**
         * load GoogleMaps API only if it hasn't been loaded before
         */
        if (!$this->isApiLoaded()) {
            $this->view->headScript()->captureStart();
?>
google.load("maps", "<?php echo L8M_Google_Maps_Api::VERSION; ?>");
<?php
			$this->view->headScript()->captureEnd();
			$this->view->headLink()
				->appendStylesheet('/css/default/screen/google-maps/base.css', 'screen')
				->appendStylesheet('/css/default/screen/google-maps/color.css', 'screen')
			;
            $this->setApiLoaded(TRUE);
        }
        return $this;
    }

    /**
     * Returns TRUE when Google Maps API has been loaded, FALSE otherwise.
     *
     * @return bool
     */
    public function isApiLoaded()
    {
        return self::$_apiLoaded;
    }

    /**
     * Adds a CSS class selector to the map.
     *
     * @param  string $class
     * @return L8M_View_Helper_GoogleMaps
     */
    public function addClass($class = NULL)
    {
        $class = trim(strip_tags((string) $class));
        if ($class &&
            !in_array($class, $this->_classes)) {
            $this->_classes[] = $class;
        }
        return $this;
    }

    /**
     * Returns an array of CSS classes added to the map.
     *
     * @return array.
     */
    public function getClasses()
    {
        return $this->_classes;
    }

    /**
     * Adds a CSS inline style to the map.
     *
     * @param  string $class
     * @return L8M_View_Helper_GoogleMaps
     */
    public function addStyle($style = NULL)
    {
        $style = trim(strip_tags((string) $style));
        if ($style) {
            $this->_styles[] = $style;
        }
        return $this;
    }

    /**
     * Returns an array of CSS inline styles added to the map.
     *
     * @return array.
     */
    public function getStyles()
    {
        return $this->_styles;
    }

    /**
     *
     *
     * Magic Methods
     *
     *
     */

    /**
     * Attempts to set a property of the associated L8M_Google_Maps instance to
     * the specified value.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
    	$setterFunction = 'set' . ucfirst($name);
    	if (method_exists($this->getMap(), $setterFunction)) {
    		$this->getMap()->{$setterFunction}($value);
    	}
    }

    /**
     * Attempts to access a property of the associated L8M_Google_Maps instance.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
		$getterFunction = 'get' . ucfirst($name);
    	if (method_exists($this->getMap(), $getterFunction)) {
    		return $this->getMap()->{$getterFunction}();
    	}
    	return NULL;
    }

    /**
     * Converts L8M_View_Helper_GoogleMaps instance to string and adds some
     * headscript.
     *
     * @return string
     */
    public function __toString()
    {
        /**
         * make sure the API is loaded
         */
    	$this->loadApi();

    	/**
    	 * add map Javascript to headscript
    	 */
		$this->view->headScript()->captureStart();
		echo $this->getMap();
		$this->view->headScript()->captureEnd();

		/**
		 * add minimum classes
		 */
		$this->addClass('google-maps');

		/**
		 * return div
		 */
        ob_start();
?>
<!-- googleMaps begin -->
<div class="<?php echo implode(' ', $this->getClasses()); ?>"<?php echo (count($this->getStyles())>0 ? ' style="' . implode('; ', $this->getStyles()). '"' : ''); ?>>
	<div id="<?php echo $this->getMap()->getId(); ?>" class="container"></div>
</div>
<!-- googleMaps end -->
<?php
    	return ob_get_clean();
    }
}