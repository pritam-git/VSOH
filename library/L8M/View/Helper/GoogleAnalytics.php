<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/GoogleAnalytics.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: GoogleAnalytics.php 566 2018-05-24 08:29:48Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_GoogleAnalytics
 *
 *
 */
class L8M_View_Helper_GoogleAnalytics extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * GoogleAnalytics default mode, i.e., covering a single domain.
	 */
	const MODE_DEFAULT = 'default';

	/**
	 * GoogleAnalytics universal mode, i.e., covering a single domain and its new features.
	 */
	const MODE_UNIVERSAL = 'universal';

	/**
	 * GoogleAnalytics multiple sub-domains mode, covering a domain with
	 * multiple sub-domains.
	 */
	const MODE_MULTIPLE_SUB_DOMAINS = 'multipleSubDomains';

	/**
	 * GoogleAnalytics multiple top-level-domains mode, covering multiple
	 * top-level domains.
	 */
	const MODE_MULTIPLE_TOP_LEVEL_DOMAINS = 'multipleTopLevelDomains';

	/**
	 * GoogleAnalytics mobile mode.
	 */
	const MODE_MOBILE = 'mobile';

	/**
	 * GoogleAnalytics asynchronous tracking mode.
	 */
	const MODE_ASYNCHRONOUS = 'asynchronous';

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * A string representing GoogleAnalytics tracker code.
	 *
	 * @var string
	 */
	protected $_code = NULL;

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
		self::MODE_DEFAULT,
		self::MODE_UNIVERSAL,
		self::MODE_MULTIPLE_SUB_DOMAINS,
		self::MODE_MULTIPLE_TOP_LEVEL_DOMAINS,
		self::MODE_MOBILE,
		self::MODE_ASYNCHRONOUS,
	);

	/**
	 * A string representing the domain name, needed for GoogleAnalytics when
	 * tracking multiple subdomains.
	 *
	 * @var string
	 */
	protected $_domainName = NULL;

	/**
	 * A flag concerning enhanced link attribution
	 *
	 * @var boolean
	 */
	protected $_enhancedLinkAttribution = FALSE;

	/**
	 * A flag concerning anonymize IP
	 *
	 * @var boolean
	 */
	protected $_anonymizeIp = TRUE;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Return google analytics javascript code if a google analytics code is
	 * specified
	 *
	 * @param  string|array|Zend_Config $options
	 * @return L8M_View_Helper_GoogleAnalytics
	 */
	public function googleAnalytics($options = NULL)
	{
		if (is_string($options)) {
			$options = array('code'=>$options);
		} else

		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}

		if (is_array($options)) {
			$this->setOptions($options);
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
	 * @param  array $options
	 * @return L8M_View_Helper_GoogleAnalytics
	 */
	public function setOptions($options = NULL)
	{
		if (is_array($options)) {
			if (array_key_exists('code', $options)) {
				$this->setCode($options['code']);
			}
			if (array_key_exists('mode', $options)) {
				$this->setMode($options['mode']);
			}
			if (array_key_exists('domainName', $options)) {
				$this->setDomainName($options['domainName']);
			}
			if (array_key_exists('enhancedLinkAttribution', $options)) {
				$this->setEnhancedLinkAttribution($options['enhancedLinkAttribution']);
			}
			if (array_key_exists('anonymizeIp', $options)) {
				$this->setAnonymizeIp($options['anonymizeIp']);
			}
		}
		return $this;
	}

	/**
	 * Sets GoogleAnalytics tracker code.
	 *
	 * @param  string $code
	 * @return L8M_View_Helper_GoogleAnalytics
	 */
	public function setCode($code = NULL)
	{
		$this->_code = (string) $code;
		return $this;
	}

	/**
	 * Sets enhanced link attribution if needed
	 *
	 * @param  boolean $enhancedLinkAttribution
	 * @return L8M_View_Helper_GoogleAnalytics
	 */
	public function setEnhancedLinkAttribution($enhancedLinkAttribution) {
		if ($enhancedLinkAttribution) {
			$this->_enhancedLinkAttribution = (bool) $enhancedLinkAttribution;
		}
		return $this;
	}

	/**
	 * Sets anonymize IP if needed
	 *
	 * @param  boolean $domainName
	 * @return L8M_View_Helper_GoogleAnalytics
	 */
	public function setAnonymizeIp($anonymizeIp) {
		if ($anonymizeIp) {
			$this->_anonymizeIp = (bool) $anonymizeIp;
		}
		return $this;
	}

	/**
	 * Sets domain name needed when tracking multiple subdomains.
	 *
	 * @param  string $domainName
	 * @return L8M_View_Helper_GoogleAnalytics
	 */
	public function setDomainName($domainName = NULL)
	{
		if ($domainName &&
			is_string($domainName)) {

			$this->_domainName = $domainName;
		}
		return $this;
	}

	/**
	 * Sets mode GoogleAnalytics is running in.
	 *
	 * @param  string $mode
	 * @return L8M_View_Helper_GoogleAnalytics
	 */
	public function setMode($mode = NULL)
	{
		if (in_array($mode, $this->_allowedModes)) {
			$this->_mode = $mode;
		}
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
	 * Returns GoogleAnalytics tracker code.
	 *
	 * @return string
	 */
	public function getCode()
	{
		return $this->_code;
	}

	/**
	 * Returns domain name that has been set for tracking multiple subdomains.
	 *
	 * @return string
	 */
	public function getDomainName()
	{
		$returnValue = $this->_domainName;
		if (!$returnValue) {
			$returnValue = 'auto';
		}
		return $returnValue;
	}

	/**
	 * Returns mode GoogleAnalytics is running in.
	 *
	 * @return string
	 */
	public function getMode()
	{
		return $this->_mode;
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
	 * Returns GoogleAnalytics tracker code for default mode.
	 *
	 * @return string
	 */
	protected function _getTrackerDefault()
	{

		ob_start();

?>
<!-- googleAnalytics begin -->
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try {
		var pageTracker = _gat._getTracker("<?php echo $this->getCode(); ?>");
		pageTracker._trackPageview();
	} catch(err) {}
</script>
<!-- googleAnalytics end -->
<?php

		return ob_get_clean();

	}

	/**
	 * Returns Google Analytics tracker code for asynchronous tracking.
	 *
	 * @return string
	 */
	protected function _getTrackerAsynchronous()
	{

		ob_start();

?>
<!-- googleAnalytics begin -->
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '<?php echo $this->getCode(); ?>']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
	})();
</script>
<!-- googleAnalytics end -->
<?php

		return ob_get_clean();

	}

	/**
	 * Returns GoogleAnalytics tracker code for tracking multiple subdomains.
	 *
	 * @return string
	 */
	protected function _getTrackerMultipleSubDomains()
	{
		if (!$this->getDomainName()) {
			return '';
		}

		ob_start();
?>
<!-- googleAnalytics begin -->
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try {
		var pageTracker = _gat._getTracker("<?php echo $this->getCode(); ?>");
		pageTracker._trackPageview();
		pageTracker._setDomainName("<?php echo $this->getDomainName(); ?>");
	} catch(err) {}
</script>
<!-- googleAnalytics end -->
<?php

		return ob_get_clean();

	}

	/**
	 * Returns GoogleAnalytics tracker code for multiple top level domains mode.
	 *
	 * @return string
	 */
	protected function _getTrackerMultipleTopLevelDomains()
	{

		ob_start();
?>
<!-- googleAnalytics begin -->
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try {
		var pageTracker = _gat._getTracker("<?php echo $this->getCode(); ?>");
		pageTracker._setDomainName("none");
		pageTracker._setAllowLinker(true);
		pageTracker._trackPageview();
	} catch(err) {}
</script>
<!-- googleAnalytics end -->
<?php

		return ob_get_clean();

	}

	/**
	 * Returns GoogleAnalytics tracker code for the new universal mode.
	 *
	 * @return string
	 */
	protected function _getTrackerUniversal()
	{
		$codeExtra = PHP_EOL;
		if ($this->_enhancedLinkAttribution) {
			$codeExtra .= 'ga(\'require\', \'linkid\', \'linkid.js\');' . PHP_EOL;
		}
		if ($this->_anonymizeIp) {
			$codeExtra .= 'ga(\'set\', \'anonymizeIp\', true);' . PHP_EOL;
		}

		ob_start();
?>
<!-- googleAnalytics begin -->
<script>
//Set to the same value as the web property used on the site
var gaProperty = '<?php echo $this->getCode(); ?>';

// Disable tracking if the opt-out cookie exists.
var disableStr = 'ga-disable-' + gaProperty;
if (document.cookie.indexOf(disableStr + '=true') > -1) {
  window[disableStr] = true;
}

// Opt-out function
function gaOptout() {
  document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
  window[disableStr] = true;
}

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', '<?php echo $this->getCode(); ?>', '<?php echo $this->getDomainName(); ?>');<?php echo $codeExtra; ?>
ga('require', 'displayfeatures');
ga('send', 'pageview');
</script>
<!-- googleAnalytics end -->
<?php

		return ob_get_clean();

	}

	/**
	 * Returns GoogleAnalytics tracker code for mobile mode.
	 *
	 * @return string
	 */
	protected function _getTrackerMobile()
	{

		ob_start();
?>
<img src="<?php echo $this->_getGoogleAnalyticsImageUrl(); ?>" />
<?php

		return ob_get_clean();

	}

	/**
	 * Returns Url to GoogleAnalytics tracker image.
	 *
	 * @return string
	 */
	protected function _getGoogleAnalyticsImageUrl()
	{

		/**
		 * replace prefix
		 */
		$GA_ACCOUNT = preg_replace('/^UA', 'MO', $this->getCode());
		$GA_PIXEL = "ga.php";

		/**
		 * as taken from Google
		 */
		$url = "";
		$url .= $GA_PIXEL . "?";
		$url .= "utmac=" . $GA_ACCOUNT;
		$url .= "&utmn=" . rand(0, 0x7fffffff);
		$referer = $_SERVER["HTTP_REFERER"];
		$query = $_SERVER["QUERY_STRING"];
		$path = $_SERVER["REQUEST_URI"];
		if (empty($referer)) {
			$referer = "-";
		}
		$url .= "&utmr=" . urlencode($referer);
		if (!empty($path)) {
			$url .= "&utmp=" . urlencode($path);
		}
		$url .= "&guid=ON";
		return $url;

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
		if ($this->getCode() &&
			$this->getMode() &&
			in_array($this->getMode(), $this->getAllowedModes()) &&
			method_exists($this, '_getTracker' . ucfirst($this->getMode()))) {
			return $this->{'_getTracker' . ucfirst($this->getMode())}();
		}
		return '';
	}

}