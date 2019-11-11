<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/Google/Maps/Point.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Point.php 7 2014-03-11 16:18:40Z nm $
 */
 
/**
 * 
 * 
 * L8M_Google_Maps_Point
 * 
 * 
 */
class L8M_Google_Maps_Point extends L8M_Google_Maps_Abstract
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
    protected $_instanceBaseName = 'point';    

    /**
     * The x coordinate. (This value increases to the right in the Google Maps 
     * coordinate system.)
     *
     * @var int
     */
	protected $_x = NULL;
	
	/**
     * The y coordinate. (This value increases downwards in the Google Maps 
     * coordinate system.)
     *
     * @var int
     */
	protected $_y = NULL;
	
	/**
	 * 
	 * 
	 * Class Constructor
	 * 
	 * 
	 */
	
	/**
	 * Constructs L8M_Google_Maps_Point instance
	 *
	 * @param  int $x
	 * @param  int $y
	 * @return void
	 */
	public function __construct($x = NULL, $y = NULL)
	{
		$this->setX($x)
			 ->setY($y);
	}
	
	/**
	 * 
	 * 
	 * Setter Methods
	 * 
	 * 
	 */
	
	/**
	 * Sets x coordinate of point.
	 *
	 * @param  int $x
	 * @return L8M_Google_Maps_Point
	 */
	public function setX($x = NULL)
	{
		$this->_x = (int) $x;
		return $this;	
	}
	
	/**
	 * Sets y coordinate of point.
	 *
	 * @param  int $y
	 * @return L8M_Google_Maps_Point
	 */
	public function setY($y = NULL)
	{
		$this->_y = (int) $y;
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
	 * Returns x coordinate of point.
	 *
	 * @return int
	 */
	public function getX()
	{
		return $this->_x;
	}

	/**
	 * Returns y coordinate of point.
	 *
	 * @return int
	 */
	public function getY()
	{
		return $this->_y;
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
?>
var <?php echo $this->getInstanceName(); ?> = new google.maps.Point(<?php echo $this->getX(); ?>,<?php echo $this->getY(); ?>);
<?php 
        return ob_get_clean();
        
    }      		
	
}