<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/Google/Maps/Control.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Control.php 7 2014-03-11 16:18:40Z nm $
 */
 
/**
 * 
 * 
 * L8M_Google_Maps_Control
 * 
 * 
 */
class L8M_Google_Maps_Control extends L8M_Google_Maps_Control_Abstract  
{
	
	/**
	 * 
	 * 
	 * Class Constants
	 *  
	 * 
	 */
	
	/**
	 * Creates a control with buttons to pan in four directions, and zoom in and 
	 * zoom out.
	 */
	const SMALL_MAP_CONTROL = 'SmallMapControl';
	
	/**
	 * Creates a control with buttons to pan in four directions, and zoom in and
	 * zoom out, and a zoom slider.
	 */
	const LARGE_MAP_CONTROL = 'LargeMapControl'; 	
	
	/**
	 * Creates a control with buttons to zoom in and zoom out.
	 */
	const SMALL_ZOOM_CONTROL = 'SmallZoomControl';
	
	/**
	 * Creates a new 3D-style control with buttons to pan in four directions, 
	 * and zoom in and zoom out, and a zoom slider.
	 */
	const LARGE_MAP_CONTROL_3D = 'LargeMapControl3D';
	
	/**
	 * Creates a new 3D-style control with buttons to zoom in and zoom out.
	 */
	const SMALL_ZOOM_CONTROL_3D = 'SmallZoomControl3D';
	
	/**
	 * Creates a control that displays the map scale.
	 */
	const SCALE_CONTROL = 'ScaleControl';
	
	/**
	 * Creates a standard map type control for selecting and switching between 
	 * supported map types via buttons.
	 */
	const MAP_TYPE_CONTROL = 'MapTypeControl';
	
	/**
	 * Creates a drop-down map type control for switching between supported map 
	 * types.
	 */
	const MENU_MAP_TYPE_CONTROL = 'MenuMapTypeControl';
	
	/**
	 * Creates a "nested" map type control for selecting and switching between 
	 * supported map types via buttons and nested checkboxes.
	 */
	const HIERARCHICAL_MAP_TYPE_CONTROL = 'HierarchicalMapTypeControl';

	/**
	 * Creates a collapsible overview mini-map in the corner of the main map for 
	 * reference location and navigation (through dragging). The 
	 * GOverviewMapControl creates an overview map with a one-pixel black border. 
	 * Note: Unlike other controls, you can only place this control in the 
	 * bottom right corner of the map (G_ANCHOR_BOTTOM_RIGHT).
	 */
	const OVERVIEW_MAP_CONTROL = 'OverviewMapControl';

	/**
	 * Creates a dynamic "breadcrumb" label indicating the address of the 
	 * current viewport as a series of nested address components. This control 
	 * additionally provides navigation links to each of the individual address 
	 * subcomponents. 	
	 */
	const NAV_LABEL_CONTROL = 'NavLabelControl';

	/**
	 * 
	 * 
	 * Class Variables
	 *  
	 * 
	 */
	
	/**
	 * An array of control types.
	 *
	 * @var array
	 */
	protected $_types = array(self::HIERARCHICAL_MAP_TYPE_CONTROL,
							  self::LARGE_MAP_CONTROL,
							  self::LARGE_MAP_CONTROL_3D,
							  self::MAP_TYPE_CONTROL,
							  self::MENU_MAP_TYPE_CONTROL,
							  self::NAV_LABEL_CONTROL,
							  self::OVERVIEW_MAP_CONTROL,
							  self::SMALL_MAP_CONTROL,
							  self::SMALL_ZOOM_CONTROL,
							  self::SMALL_ZOOM_CONTROL_3D);

	/**
	 * The type of the control.
	 *
	 * @var string
	 */
	 protected $_type = NULL;
	 
	 /**
	  *
	  * 
	  * Class Constructor
	  * 
	  *  
	  */
	 
	 /**
	  * Constructs L8M_Google_Maps_Control instance
	  *
	  * @param  string $type
	  * @return void
	  */
	 public function __construct($type = NULL)
	 {
		$this->setType($type);
	 }
	 
	 /**
	  * 
	  * 
	  * Setter Methods
	  * 
	  * 
	  */
	 
	 /**
	  * Sets type of control
	  *
	  * @param  string $type
	  * @return L8M_Google_Maps_Control
	  */
	 public function setType($type = NULL)
	 {
	 	if (in_array($type, $this->_types)) {
	 		$this->_type = $type;
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
	  * Returns the type of this control.
	  *
	  * @return string
	  */
	 public function getType()
	 {
	     return $this->_type;
	 }
	
	/**
	 * 
	 * 
	 * Class Methods
	 *  
	 * 
	 */
	 
	/**
     * Creates and initializes a Javascript variable reflected by this class. 
     * 
     * @return string
     */
    public function createAndInitialize()
    {
        parent::createAndInitialize();
        ob_start();
?>
var <?php echo $this->getInstanceName(); ?> = new google.maps.<?php echo $this->getType();?>();
<?php        
        return ob_get_clean();    	 
    }

}