<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/GoogleConversion.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: GoogleConversion.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_GoogleConversion
 *
 *
 */
class L8M_View_Helper_GoogleConversion extends Zend_View_Helper_Abstract
{

    /**
     *
     *
     * Class Constants
     *
     *
     */

	/**
	 * GoogleConversion default mode, i.e., covering a single domain.
	 */
	const MODE_DEFAULT = 'default';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A string representing GoogleConversion tracker code.
	 *
	 * @var string
	 */
	protected $_code = NULL;

	/**
	 * A string representing GoogleConversion color.
	 *
	 * @var string
	 */
	protected $_color = NULL;

	/**
	 * A string representing the GoogleConversion converted value.
	 *
	 * @var string
	 */
	protected $_convertedValue = NULL;

	/**
	 * A string representing GoogleConversion label.
	 *
	 * @var string
	 */
	protected $_label = NULL;

	/**
	 * A string representing GoogleConversion language.
	 *
	 * @var string
	 */
	protected $_language = 'en';

    /**
     * A string representing the mode GoogleAnalytics is running in.
     *
     * @var string
     */
    protected $_mode = self::MODE_DEFAULT;

    /**
     * An array of modes GoogleAnalytics could run in.
     *
     * @var array
     */
    protected $_allowedModes = array(
    								 1 => self::MODE_DEFAULT,
    								);

	/**
	 * TRUE when the Google Conversion script has been loaded.
	 *
	 * @var bool
	 */
	protected static $_conversionScriptLoaded = FALSE;

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Return google conversion javascript code if a value is specified.
     *
     * @param  string $value
     * @return L8M_View_Helper_GoogleConversion
     */
    public function googleConversion($value = NULL)
    {
    	if ($value) {
    		$this->setConvertedValue($value);
        }

        return $this;
	}

	/**
	 *
	 *
	 * Class Setters
	 *
	 *
	 */

    /**
     * Sets options.
     *
     * @param array $options
     * @return L8M_View_Helper_GoogleAnalytics
     */
    public function setOptions($options = NULL)
    {
    	if (is_array($options)) {
    		if (isset($options['code'])) {
    			$this->setCode($options['code']);
    		}
    		if (isset($options['color'])) {
    			$this->setColor($options['color']);
    		}
    		if (isset($options['label'])) {
				$this->setLabel($options['label']);
    		}
    		if (isset($options['language'])) {
    			$this->setLanguage($options['language']);
    		}
    		if (isset($options['format'])) {
    			$this->setFormat($options['format']);
    		}
    	}
    	return $this;
    }

	/**
	 * Sets GoogleConversion tracker code.
	 *
	 * @param  string $code
	 * @return L8M_View_Helper_GoogleConversion
	 */
	public function setCode($code = NULL)
	{
		$this->_code = (string) $code;
		return $this;
	}

	/**
	 * Sets GoogleConversion color.
	 *
	 * @param  string $color
	 * @return L8M_View_Helper_GoogleConversion
	 */
	public function setColor($color = NULL)
	{
		$this->_color = (string) $color;
		return $this;
	}

	/**
	 * Sets GoogleConversion converted value.
	 *
	 * @param  string $value
	 * @return L8M_View_Helper_GoogleConversion
	 */
	public function setConvertedValue($value = NULL)
	{
		$this->_convertedValue = (string) $value;
		return $this;
	}

	/**
	 * Sets GoogleConversion tracker format.
	 *
	 * @param  string $format
	 * @return L8M_View_Helper_GoogleConversion
	 */
	public function setFormat($format = NULL)
	{
		if (array_key_exists($format, $this->getAllowedModes())) {
			$this->_format = $format;
		}
		return $this;
	}

	/**
	 * Sets GoogleConversion label.
	 *
	 * @param  string $label
	 * @return L8M_View_Helper_GoogleConversion
	 */
	public function setLabel($label = NULL)
	{
		$this->_label = (string) $label;
		return $this;
	}

	/**
	 * Sets GoogleConversion language.
	 *
	 * @param  string $language
	 * @return L8M_View_Helper_GoogleConversion
	 */
	public function setLanguage($language = NULL)
	{
		$this->_language = (string) $language;
		return $this;
	}

	/**
	 * Sets Google Conversion script as loaded.
	 *
	 * @param  bool $loaded
	 * @return L8M_View_Helper_GoogleConversion
	 */
	public function setConversionScriptLoaded($loaded = TRUE)
	{
		self::$_conversionScriptLoaded = (bool) $loaded;
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
	 * Returns GoogleConversion tracker code.
	 *
	 * @return string
	 */
	public function getCode()
	{
		return $this->_code;
	}

	/**
	 * Returns GoogleConversion color.
	 *
	 * @return string
	 */
	public function getColor()
	{
		return $this->_color;
	}

	/**
	 * Returns GoogleConversion converted value.
	 *
	 * @return string
	 */
	public function getConvertedValue()
	{
		return $this->_convertedValue;
	}

	/**
	 * Returns GoogleConversion label.
	 *
	 * @return string
	 */
	public function getLabel()
	{
		return $this->_label;
	}

	/**
	 * Returns GoogleConversion language.
	 *
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->_language;
	}

	/**
	 * Returns mode GoogleConversion is running in.
	 *
	 * @return string
	 */
	public function getMode()
	{
		$modes = $this->getAllowedModes();
		if (array_key_exists($this->getFormat(), $modes)) {
			return $modes[$this->getFormat()];
		}
		return NULL;
	}

	/**
	 * Returns format GoogleConversion is running with.
	 *
	 * @return string
	 */
	public function getFormat()
	{
		return $this->_format;
	}

	/**
	 * Returns an array with the allowed modes.
	 *
	 * @return array
	 */
	public function getAllowedModes()
	{
		return $this->_allowedModes;
	}

	/**
	 * Returns GoogleConversion tracker code for default mode.
	 *
	 * @return string
	 */
	protected function _getTrackerDefault()
	{

		ob_start();

?>
<!-- googleConversion begin -->
<script type="text/javascript">
<!--
	var google_conversion_id = <?php echo $this->getCode(); ?>;
	var google_conversion_language = "<?php echo $this->getLanguage(); ?>";
	var google_conversion_format = "<?php echo $this->getFormat(); ?>";
	var google_conversion_color = "<?php echo $this->getColor(); ?>";
	var google_conversion_label = "<?php echo $this->getLabel(); ?>";
	var google_conversion_value = <?php echo number_format($this->getConvertedValue(), 2); ?>;
//-->
</script>
<noscript>
	<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/<?php echo $this->getCode(); ?>/?label=<?php echo $this->getLabel(); ?>&amp;guid=ON&amp;script=0"/>
	</div>
</noscript>
<!-- googleAnalytics end -->
<?php

        return ob_get_clean();

	}

    /**
     * Adds headscript for loading the Google Conversion script if it has not been added
     * yet.
     *
     * @return L8M_View_Helper_GoogleConversion
     */
    public function loadConversionScript()
    {
        if (!$this->isConversionScriptLoaded()) {
            $this->view->headScript()->appendFile(L8M_Google_Conversion::URI_CONVERSION_SCRIPT_SOURCE);
            $this->setConversionScriptLoaded(TRUE);
        }
        return $this;
    }

    /**
     * Returns TRUE when Google Conversion script has been loaded, FALSE
     * otherwise.
     *
     * @return bool
     */
    public function isConversionScriptLoaded()
    {
        return self::$_conversionScriptLoaded;
    }

	/**
	 *
	 *
	 * Magic Methods
	 *
	 *
	 */

	/**
	 * Returns GoogleAnalytics as string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		if (!$this->getCode()) {
			$this->setOptions(L8M_Google_Conversion::getOptions());
		}

		if ($this->getConvertedValue() &&
			$this->getCode() &&
			$this->getMode() &&
		    method_exists($this, '_getTracker' . ucfirst($this->getMode()))) {
			/**
	         * make sure the API is loaded
	         */
	    	$this->loadConversionScript();

			return $this->{'_getTracker' . ucfirst($this->getMode())}();
        }
        return '';
	}

}