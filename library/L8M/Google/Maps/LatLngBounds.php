<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/Google/Maps/LatLngBounds.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: LatLngBounds.php 7 2014-03-11 16:18:40Z nm $
 */
 
/**
 * 
 * 
 * L8M_Google_Maps_LatLngBounds
 * 
 * 
 */
class L8M_Google_Maps_LatLngBounds extends L8M_Google_Maps_Abstract 
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
    protected $_instanceBaseName = 'latLngBounds';
	    
	
	/**
	 * A L8M_Google_Maps_LatLng instance.
	 *
	 * @var L8M_Google_Maps_LatLng
	 */
	protected $_northEast = NULL;
	
	/**
	 * A L8M_Google_Maps_LatLng instance.
	 *
	 * @var L8M_Google_Maps_LatLng
	 */
	protected $_southWest = NULL;
	
	/**
	 * 
	 * 
	 * Class Constructor
	 * 
	 * 
	 */
	
	/**
	 * Constructs L8M_Google_Maps_LatLngBounds instance.
	 * 
	 * @return void
	 */
	public function __construct($northEast = array(0,0), $southWest = array(0,0))
	{
		$this->setNorthEast($northEast)
			 ->setSouthWest($southWest);
	}
	
	/**
	 * 
	 * 
	 * Class Setters
	 * 
	 * 
	 */
	
	/**
	 * Sets north east L8M_Google_Maps_LatLng instance.
	 *
	 * @param  string|array|L8M_Google_Maps_LatLng $northEast
	 * @return L8M_Google_Maps_LatLong
	 */
	public function setNorthEast($northEast = NULL)
	{
		if (is_string($northEast) ||
			is_array($northEast)) {
			$northEast = L8M_Google_Maps_LatLng::fromAddress($northEast);		
		}
		if ($northEast instanceof L8M_Google_Maps_LatLng) {
			$this->_northEast = $northEast;
		}
		return $this;
	}
	
	/**
	 * Sets sout west L8M_Google_Maps_LatLng instance.
	 *
	 * @param  string|array|L8M_Google_Maps_LatLng $southWest
	 * @return L8M_Google_Maps_LatLong
	 */
	public function setSouthWest($southWest = NULL)
	{
		if (is_string($southWest) ||
			is_array($southWest)) {
			$southWest = L8M_Google_Maps_LatLng::fromAddress($southWest);		
		}
		if ($southWest instanceof L8M_Google_Maps_LatLng) {
			$this->_southWest = $southWest;
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
	 * Returns north east L8M_Google_Maps_LatLng instance.
	 *
	 * @return L8M_Google_Maps_LatLng
	 */
	public function getNorthEast()
	{
		return $this->_northEast;	
	}
	
	/**
	 * Returns south west L8M_Google_Maps_LatLng instance.
	 *
	 * @return L8M_Google_Maps_LatLng
	 */
	public function getSouthWest()
	{
		return $this->_southWest;	
	}	
	
	/**
	 * 
	 * 
	 * Class Methods
	 * 
	 * 
	 */
	
	/**
	 * Returns L8M_Google_Maps_LatLngBounds instance from array of overlays that
	 * are supposed to be displayed on the map, with a padding of $padding 
	 * pixels. 
	 *
	 * @param  array $overlays
	 * @param  int   $padding
	 * @return L8M_Google_Maps_LatLngBounds
	 */
	public static function fromOverlays($overlays = array(), $options = array()) 
	{
	    if (is_array($overlays) &&
	        count($overlays)>0) {
            $latLngBounds = new self;
            foreach($overlays as $overlay) {
                if ($overlay instanceof L8M_Google_Maps_Overlay_Marker) {
                    /* @var $overlay L8M_Google_Maps_Overlay_Marker */
                    $latLng = $overlay->getLatLng();
                } else 
                if ($overlay instanceof L8M_Google_Maps_LatLng) {
                    $latLng = $overlay;
                }
            }
        }
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
        
        /**
         * create and initialize south-west and north-east and then add instances
         * to latLngBounds instance
         */
?>
<?php echo $this->getSouthWest()->createAndInitialize();?>
<?php echo $this->getNorthEast()->createAndInitialize();?>
var <?php echo $this->getInstanceName(); ?> = new google.maps.LatLngBounds(<?php echo $this->getSouthWest()->getInstanceName(); ?>,<?php echo $this->getNorthEast()->getInstanceName();?>);
<?php 
        return ob_get_clean();
        
    }      	
	
}