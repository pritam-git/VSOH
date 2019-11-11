<?php

/**
 * L8M
 * 
 * 
 * @filesource /library/L8M/Google/Maps/Size.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Size.php 7 2014-03-11 16:18:40Z nm $
 */
 
/**
 * 
 * 
 * L8M_Google_Maps_Size
 * 
 * 
 */
class L8M_Google_Maps_Size extends L8M_Google_Maps_Abstract 
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
    protected $_instanceBaseName = 'size';
	
	/**
	 * The height parameter.
	 *
	 * @var int
	 */
	protected $_height= NULL;
	
	/**
	 * The width parameter.
	 *
	 * @var int
	 */
	protected $_width = NULL;
	
	/**
	 * 
	 * 
	 * Class Constructor
	 *  
	 * 
	 */	
	
	/**
	 * Constructs L8M_Google_Maps_Size instance.
	 *
	 * @param  int $width
	 * @param  int $height
	 * @return void
	 */
	public function __construct($width = NULL, $height = NULL)
	{
		$this->setWidth($width)
			 ->setHeight($height);
	}
	
	/**
	 * 
	 * 
	 * Class Setters
	 *  
	 * 
	 */	
	
	/**
	 * Sets height.
	 *
	 * @param  int $height
	 * @return L8M_Google_Maps_Size
	 */
	public function setHeight($height = NULL)
	{
		$this->_height = (int) $height;
		return $this;
	}
	
	/**
	 * Sets width.
	 *
	 * @param  int $width
	 * @return L8M_Google_Maps_Size
	 */
	public function setWidth($width = NULL)
	{
		$this->_width = (int) $width;
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
	 * Returns height.
	 *
	 * @return int
	 */
	public function getHeight()
	{
		return $this->_height;
	}

	/**
	 * Returns width.
	 *
	 * @return int
	 */
	public function getWidth()
	{
		return $this->_width;
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
var <?php echo $this->getInstanceName(); ?> = new google.maps.Size(<?php echo $this->getWidth(); ?>,<?php echo $this->getHeight(); ?>);
<?php 
        return ob_get_clean();
        
    }      			
}
