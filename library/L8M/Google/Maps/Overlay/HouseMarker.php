<?php

/**
 * L8M 
 *
 *
 * @filesource /library/L8M/Google/Maps/Overlay/HouseMarker.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: HouseMarker.php 399 2015-09-02 09:23:38Z nm $
 */

/**
 * 
 * 
 * L8M_Google_Maps_Overlay_HouseMarker
 * 
 * 
 */
class L8M_Google_Maps_Overlay_HouseMarker extends L8M_Google_Maps_Overlay_Marker
{
    
    /**
     * 
     * 
     * Class Variables
     * 
     * 
     */
    
    /**
     * 
     * 
     * Class Constructor
     * 
     * 
     */    
    
	/**
	 * Constructs L8M_Google_Maps_Overlay_HouseMarker instance
	 *
	 * @param L8M_Google_Maps_LatLng $latLng
	 */
    public function __construct($latLng = NULL)
    {
        parent::__construct($latLng);
    }
    
    /**
     * 
     * 
     * Class Methods
     * 
     * 
     */
    
	/**
	 * Returns L8M_Google_Maps_Overlay_HouseMarker instance from house.
	 *
     * @param  Default_Model_House $house
     * @param  bool                $withText
     * @return L8M_Google_Maps_Overlay_Marker
     */
	public static function fromHouse($house = NULL, $withText = TRUE)
	{
	    if ($house instanceof Default_Model_House) {
	        
	        /**
	         * city 
	         */
	        $city = $withText ? $house->City->name : $house->City->Translation['en']->name;
	        $city = strtolower($city);
	        $city = utf8_decode($city);
	        $city = str_replace('ä', 'ae', $city);
	        $city = str_replace('ü', 'ue', $city);
	        $city = str_replace('ö', 'oe', $city);
	        $city = utf8_encode($city);
	            
    		/**
             * icon image
             * 
             * @todo folder in config?
             */
            $imageSource = L8M_Library::getSchemeAndHttpHost() . '/img/google.maps.overlay.marker.city.' . $city . ($withText ? '.text' : '') . '.png';
            $imageInfo = getimagesize($imageSource);
            
            /**
             * icon shadow
             * 
             * @todo folder in config? 
             */
            $shadowSource = L8M_Library::getSchemeAndHttpHost() . '/img/google.maps.overlay.marker.city.' . ($withText ? $city . '.text.' : '') . 'shadow.png';
            $shadowInfo = getimagesize($shadowSource);
            
            /**
             * icon transparent
             */
            $transparentSource = preg_replace('/\.png$/', '.trans.png', $imageSource); 
    								       
            /**
             * create an icon
             * 
             * @todo anchor is semi-hardcoded
             */			
            $icon = new L8M_Google_Maps_Icon();
            $icon->setImage($imageSource)
                 ->setIconAnchor(new L8M_Google_Maps_Point(15, $imageInfo[1]))
                 ->setIconSize(new L8M_Google_Maps_Size($imageInfo[0], $imageInfo[1]))
                 ->setShadow($shadowSource)
                 ->setShadowSize(new L8M_Google_Maps_Size($shadowInfo[0], $shadowInfo[1]))
                 ->setTransparent($transparentSource);
                 
			/**
	         * address
	         * 
	         * @todo optionally retrieve longitude/latitude from House instance,
	         *       if it's empty, request it and store the result in House 
	         *       instance?
	         */
	        $address = implode(', ', array($house->street, 
								           $house->zip . ' ' . $house->City->name,
								           $house->City->Country->name));                 
    
            /**
             * create a marker
             * 
             * @todo add info window?
             * @todo modify options?
             */
    		$marker = L8M_Google_Maps_Overlay_Marker::fromAddress($address);
    		$marker->setClickable(TRUE)
    			   ->setDraggable(FALSE)
    			   ->setHide(FALSE)
    			   ->setTitle($house->name)
    			   ->setIcon($icon);            

            return $marker;
	            
        }
		return NULL;
	}
}