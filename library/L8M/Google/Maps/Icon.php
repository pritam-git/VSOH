<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/Google/Maps/Icon.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Icon.php 7 2014-03-11 16:18:40Z nm $
 */
 
/**
 * 
 * 
 * L8M_Google_Maps_Icon
 * 
 * 
 */
class L8M_Google_Maps_Icon extends L8M_Google_Maps_Abstract 
{
	
    /**
     * 
     * 
     * Class Constants
     * 
     * 
     */
    
    const DEFAULT_ICON  = 'G_DEFAULT_ICON';
    
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
    protected $_instanceBaseName = 'icon';	
	
	/**
	 * The foreground image URL of the icon.
	 *
	 * @var string
	 */
	protected $_image = NULL;
	
	/**
	 * The shadow image URL of the icon.
	 *
	 * @var string
	 */
	protected $_shadow = NULL;

	/**
	 * An L8M_Google_Maps_Size instance, specifying the width and height of the 
	 * icon.
	 *
	 * @var L8M_Google_Maps_Size
	 */
	protected $_iconSize = NULL;
	
	/**
	 * An L8M_Google_Maps_Size instance, specifying the width and height of the 
	 * shadow image
	 *
	 * @var L8M_Google_Maps_Size
	 */
	protected $_shadowSize = NULL;
	
	/**
	 * An L8M_Google_Maps_Point instance. The pixel coordinate relative to the 
	 * top left corner of the icon image at which this icon is anchored to the 
	 * map.
	 *
	 * @var L8M_Google_Maps_Point
	 */
	protected $_iconAnchor = NULL;
	
	/**
	 * An L8M_Google_Maps_Point instance. The pixel coordinate relative to the 
	 * top left corner of the icon image at which the info window is anchored to 
	 * this icon.
	 *
	 * @var L8M_Google_Maps_Point
	 */
	protected $_infoWindowAnchor = NULL;
	
	/**
	 * The URL of an alternate foreground icon image used for printing on 
	 * browsers incapable of handling the default GIcon.image. Versions of IE 
	 * typically require an alternative image in these cases as they cannot 
	 * print the icons as transparent PNGs. Note that browsers capable of 
	 * printing the default image will ignore this property.
	 *
	 * @var string
	 */
	protected $_printImage = NULL;
	
	/**
	 * The URL of an alternate non-transparent icon image used for printing on 
	 * browsers incapable of handling either transparent PNGs (provided in the 
	 * default GIcon.image) or transparent GIFs (provided in GIcon.printImage). 
	 * Older versions of Firefox/Mozilla typically require non-transparent 
	 * images for printing. Note that browsers capable of printing the default 
	 * image will ignore this property.
	 *
	 * @var string
	 */
	protected $_mozPrintImage = NULL;
	
	/**
	 * The URL of the shadow image used for printed maps. It should be a GIF 
	 * image since most browsers cannot print PNG images.
	 *
	 * @var string
	 */
	protected $_printShadow = NULL;
	
	/**
	 * The URL of a virtually transparent version of the foreground icon image 
	 * used to capture click events in Internet Explorer. This image should be a 
	 * 24-bit PNG version of the main icon image with 1% opacity, but the same 
	 * shape and size as the main icon.
	 *
	 * @var string
	 */
	protected $_transparent = NULL;
	
	/**
	 * An array of integers representing the x/y coordinates of the image map we 
	 * should use to specify the clickable part of the icon image in browsers 
	 * other than Internet Explorer.
	 *
	 * @var array
	 */
	protected $_imageMap = array();
	
	/**
	 * Specifies the distance in pixels in which a marker will visually "rise" 
	 * vertically when dragged.
	 * 
	 * @var int
	 */
	protected $_maxHeight = NULL;
	
	/**
	 * Specifies the cross image URL when an icon is dragged.
	 *
	 * @var string
	 */
	protected $_dragCrossImage  = NULL;
	
	/**
	 * An L8M_Google_Maps_Size instance. Specifies the pixel size of the cross 
	 * image when an icon is dragged.
	 *
	 * @var L8M_Google_Maps_Size
	 */
	protected $_dragCrossSize = NULL;
	
	/**
	 * An L8M_Google_Maps_Point instance.
	 *
	 * @var L8M_Google_Maps_Point
	 */
	protected $_dragCrossAnchor = NULL;
	
	/**
	 * 
	 * 
	 * Class Constructor
	 * 
	 * 
	 */
	
	/**
	 * Constructs L8M_Google_Maps_Icon instance.
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
	 * Sets drag cross image anchor.
	 * 
	 * @param  L8M_Google_Maps_Point $anchor
	 * @return L8M_Google_Maps_Icon
	 */
	public function setDragCrossAnchor($anchor = NULL)
	{
		if ($anchor instanceof L8M_Google_Maps_Point) {
			$this->_dragCrossAnchor = $anchor;
		}
		return $this;
	}
	
	/**
	 * Sets URL of drag cross image.
	 * 
	 * @param  string $image
	 * @return L8M_Google_Maps_Icon
	 */
	public function setDragCrossImage($image = NULL)
	{
		$this->_dragCrossImage = $image;
		return $this;
	}
	
	/**
	 * Sets drag cross image size.
	 * 
	 * @param  L8M_Google_Maps_Size $size
	 * @return L8M_Google_Maps_Icon
	 */
	public function setDragCrossSize($size = NULL)
	{
		if ($size instanceof L8M_Google_Maps_Size) {
			$this->_dragCrossSize = $size;
		}
		return $this;
	}
	
	/**
	 * Sets icon anchor.
	 * 
	 * @param  L8M_Google_Maps_Point $anchor
	 * @return L8M_Google_Maps_Icon
	 */
	public function setIconAnchor($anchor = NULL)
	{
		if ($anchor instanceof L8M_Google_Maps_Point) {
			$this->_iconAnchor = $anchor;
		}
		return $this;
	}
	
	/**
	 * Sets icon size.
	 * 
	 * @param  L8M_Google_Maps_Size $size
	 * @return L8M_Google_Maps_Icon 
	 */
	public function setIconSize($size = NULL)
	{
		if ($size instanceof L8M_Google_Maps_Size) {
			$this->_iconSize = $size;
		}
		return $this;
	}
	
	/**
	 * Sets URL of image.
	 * 
	 * @param  string $image
	 * @return L8M_Google_Maps_Icon
	 */
	public function setImage($image = NULL)
	{
		$this->_image = $image;
		return $this;
	}
	
	/**
	 * Sets image map.
	 * 
	 * @param  array $map
	 * @return L8M_Google_Maps_Icon
	 */
	public function setImageMap($map = array())
	{
		$this->_imageMap = $map;
	}
	
	/**
	 * Sets info window anchor
	 * 
	 * @param  L8M_Google_Maps_Point $anchor
	 * @return L8M_Google_Maps_Icon
	 */
	public function setInfoWindowAnchor($anchor = NULL)
	{
		if ($anchor instanceof L8M_Google_Maps_Size) {
			$this->_infoWindowAnchor = $anchor;
		}
		return $this;
	}
	
	/**
	 * Sets max height.
	 * 
	 * @param int $height
	 * @return L8M_Google_Maps_Icon
	 */
	public function setMaxHeight($height = NULL)
	{
		$this->_maxHeight = (int) $height;
		return $this;
	}
	
	/**
	 * Sets URL of mozilla print image.
	 * 
	 * @param  string $image
	 * @return L8M_Google_Maps_Icon
	 */
	public function setMozPrintImage($image = NULL)
	{
		$this->_mozPrintImage = (string) $image;
		return $this;
	}
	
	/**
	 * Sets URL of print image.
	 * 
	 * @param string $image
	 * @return L8M_Google_Maps_Icon
	 */
	public function setPrintImage($image = NULL)
	{
		$this->_printImage = (string) $image;
		return $this;
	}
	
	/**
	 * Sets URL of print shadow image.
	 * 
	 * @param string $image
	 * @return L8M_Google_Maps_Icon
	 */
	public function setPrintShadow($image = NULL)
	{
		$this->_printShadow = (string) $image;
		return $this;
	}
	
	/**
	 * Sets URL of shadow image.
	 * 
	 * @param string $_shadow
	 * @return L8M_Google_Maps_Icon
	 */
	public function setShadow($image = NULL)
	{
		$this->_shadow = (string) $image;
		return $this;
	}
	
	/**
	 * Sets size of shadow image.
	 * 
	 * @param  L8M_Google_Maps_Size $size
	 * @return L8M_Google_Maps_Icon
	 */
	public function setShadowSize($size = NULL)
	{
		if ($size instanceof L8M_Google_Maps_Size) {
			$this->_shadowSize = $size;
		}	
		return $this;
	}
	
	/**
	 * Sets URL of transparent image for IE.
	 * 
	 * @param string $image
	 * @return L8M_Google_Maps_Icon
	 */
	public function setTransparent($image = NULL)
	{
		$this->_transparent = (string) $image;
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
	 * Returns drag chross anchor.
	 * 
	 * @return L8M_Google_Maps_Point
	 */
	public function getDragCrossAnchor() {
		return $this->_dragCrossAnchor;
	}
	
	/**
	 * Returns URL of drag cross image.
	 * 
	 * @return string
	 */
	public function getDragCrossImage() 
	{
		return $this->_dragCrossImage;
	}
	
	/**
	 * Returns size of drag cross image.
	 * 
	 * @return L8M_Google_Maps_Size
	 */
	public function getDragCrossSize() 
	{
		return $this->_dragCrossSize;
	}
	
	/**
	 * Returns anchor of icon.
	 * 
	 * @return L8M_Google_Maps_Point
	 */
	public function getIconAnchor()
	{
		return $this->_iconAnchor;
	}
	
	/**
	 * Returns size of icon.
	 * 
	 * @return L8M_Google_Maps_Size
	 */
	public function getIconSize() 
	{
		return $this->_iconSize;
	}
	
	/**
	 * Returns URL of image.
	 * 
	 * @return string
	 */
	public function getImage() 
	{
		return $this->_image;
	}
	
	/**
	 * Returns image map as an array.
	 * 
	 * @return array
	 */
	public function getImageMap() 
	{
		return $this->_imageMap;
	}
	
	/**
	 * Returns info window anchor.
	 * 
	 * @return L8M_Google_Maps_Point
	 */
	public function getInfoWindowAnchor()
	{
		return $this->_infoWindowAnchor;
	}
	
	/**
	 * Returns maximumg height.
	 * 
	 * @return int
	 */
	public function getMaxHeight()
	{
		return $this->_maxHeight;
	}
	
	/**
	 * Returns URL of Mozilla print image.
	 * 
	 * @return string
	 */
	public function getMozPrintImage() {
		return $this->_mozPrintImage;
	}
	
	/**
	 * Returns ULR of print image.
	 * 
	 * @return string
	 */
	public function getPrintImage()
	{
		return $this->_printImage;
	}
	
	/**
	 * Returns URL of print shadow.
	 * 
	 * @return string
	 */
	public function getPrintShadow() 
	{
		return $this->_printShadow;
	}
	
	/**
	 * Returns URL of shadow.
	 * 
	 * @return string
	 */
	public function getShadow() 
	{
		return $this->_shadow;
	}
	
	/**
	 * Returns shadow size.
	 * 
	 * @return L8M_Google_Maps_Size
	 */
	public function getShadowSize() 
	{
		return $this->_shadowSize;
	}
	
	/**
	 * Returns transparent image url.
	 * 
	 * @return string
	 */
	public function getTransparent() 
	{
		return $this->_transparent;
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
     * @todo   further assign specified options to icon instance
     * @return string
     */
    public function createAndInitialize() 
    {	
        parent::createAndInitialize();
        ob_start();
        
        /**
         * icon
         */
?>
var <?php echo $this->getInstanceName(); ?> = new google.maps.Icon();
<?php 

        /**
         * image
         */
        if ($this->getImage()) {
?>
<?php echo $this->getInstanceName(); ?>.image = '<?php echo $this->getImage(); ?>';
<?php
        }
        
        /**
         * iconSize
         */
        if ($this->getIconSize()) {
?>
<?php echo $this->getIconSize()->createAndInitialize(); ?>
<?php echo $this->getInstanceName(); ?>.iconSize = <?php echo $this->getIconSize()->getInstanceName(); ?>;
<?php
        }
        
        /**
         * shadow
         */
        if ($this->getShadow()) {
?>
<?php echo $this->getInstanceName(); ?>.shadow = '<?php echo $this->getShadow(); ?>';
<?php
        }
        
        /**
         * shadowSize
         */
        if ($this->getShadowSize()) {
?>
<?php echo $this->getShadowSize()->createAndInitialize(); ?>
<?php echo $this->getInstanceName(); ?>.shadowSize = <?php echo $this->getShadowSize()->getInstanceName(); ?>;
<?php
        }

        /**
         * iconAnchor
         */
        if ($this->getIconAnchor()) {
?>
<?php echo $this->getIconAnchor()->createAndInitialize(); ?>
<?php echo $this->getInstanceName(); ?>.iconAnchor = <?php echo $this->getIconAnchor()->getInstanceName(); ?>;
<?php            
        }
        
        /**
         * infoWindowAnchor
         */
        if ($this->getInfoWindowAnchor()) {
?>
<?php echo $this->getInfoWindowAnchor()->createAndInitialize(); ?>
<?php echo $this->getInstanceName(); ?>.infoWindowAnchor = <?php echo $this->getInfoWindowAnchor()->getInstanceName(); ?>;
<?php            
        }   
        
        /**
         * mozPrintImage
         */
        if ($this->getMozPrintImage()) {
?>
<?php echo $this->getInstanceName(); ?>.mozPrintImage = '<?php echo $this->getMozPrintImage(); ?>';
<?php            
        }
    
        /**
         * printShadow 
         */
        if ($this->getPrintShadow()) {
?>
<?php echo $this->getInstanceName(); ?>.printShadow = '<?php echo $this->getPrintShadow(); ?>';
<?php            
        }

        /**
         * transparent 
         */
        if ($this->getTransparent()) {
?>
<?php echo $this->getInstanceName(); ?>.transparent = '<?php echo $this->getTransparent(); ?>';
<?php            
        }

        /**
         * imageMap
         */
        if ($this->getImageMap()) {
?>
<?php echo $this->getInstanceName(); ?>.imageMap = <?php echo $this->getImageMap(); ?>;
<?php            
        }

		/**
         * imageMap
         */
        if ($this->getMaxHeight()) {
?>
<?php echo $this->getInstanceName(); ?>.maxHeight = <?php echo $this->getMaxHeight(); ?>;
<?php            
        }              
        
		/**
         * dragCrossImage
         */
        if ($this->getDragCrossImage()) {
?>
<?php echo $this->getInstanceName(); ?>.dragCrossImage = '<?php echo $this->getDragCrossImage(); ?>';
<?php            
        }        
        
		/**
         * dragCrossSize
         */
        if ($this->getDragCrossSize()) {
?>
<?php echo $this->getDragCrossSize()->createAndInitialize(); ?>
<?php echo $this->getInstanceName(); ?>.dragCrossSize = <?php echo $this->getDragCrossSize()->getInstanceName(); ?>;
<?php            
        }        
        
		/**
         * dragCrossAnchor
         */
        if ($this->getDragCrossAnchor()) {
?>
<?php echo $this->getDragCrossAnchor()->createAndInitialize(); ?>
<?php echo $this->getInstanceName(); ?>.dragCrossSize = <?php echo $this->getDragCrossAnchor()->getInstanceName(); ?>;
<?php            
        }                
        
        return ob_get_clean();
        
    }        
	
}