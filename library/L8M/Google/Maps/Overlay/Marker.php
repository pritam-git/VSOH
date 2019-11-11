<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Google/Maps/Overlay/Marker.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Marker.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Google_Maps_Overlay_Marker
 *
 *
 */
class L8M_Google_Maps_Overlay_Marker extends L8M_Google_Maps_Overlay_Abstract
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
	protected $_instanceBaseName = 'marker';

	/**
	 * An array of options.
	 *
	 * @var array
	 */
	protected $_options = array('icon',
								'dragCrossMove',
								'title',
								'clickable',
								'draggable',
								'bouncy',
								'bounceGravity',
								'autoPan',
								'zIndexProcess',
								'hide');

	/**
	 * An array of default options.
	 *
	 * @var array
	 */
	protected $_defaultOptions = array('title'=>'Hoppla!');

	/**
	 * An L8M_Google_Maps_LatLng instance.
	 *
	 * @var L8M_Google_Maps_LatLng
	 */
	protected $_latLng = NULL;

	/**
	 * Chooses the Icon for this class. If not specified, G_DEFAULT_ICON is
	 * used.
	 *
	 * @var L8M_Google_Maps_Icon
	 */
	protected $_icon = NULL;

	/**
	 * When dragging markers normally, the marker floats up and away from the
	 * cursor. Setting this value to true  keeps the marker underneath the
	 * cursor, and moves the cross downwards instead. The default value for this
	 * option is false.
	 *
	 * @var bool
	 */
	protected $_dragCrossMove = FALSE;

	/**
	 * This string will appear as tooltip on the marker, i.e. it will work just
	 * as the title attribute on HTML elements.
	 *
	 * @var string
	 */
	protected $_title = NULL;

	/**
	 * Toggles whether or not the marker is clickable. Markers that are not
	 * clickable or draggable are inert, consume less resources and do not
	 * respond to any events. The default value for this option is true, i.e.
	 * if the option is not specified, the marker will be clickable.
	 *
	 * @var bool
	 */
	protected $_clickable = TRUE;

	/**
	 * Toggles whether or not the marker will be draggable by users. Markers set
	 * up to be dragged require more resources to set up than markers that are
	 * clickable. Any marker that is draggable is also clickable, bouncy and
	 * auto-pan enabled by default. The default value for this option is false.
	 *
	 * @var bool
	 */
	protected $_draggable  = FALSE;

	/**
	 * Toggles whether or not the marker should bounce up and down after it
	 * finishes dragging. The default value for this option is false.
	 *
	 * @var bool
	 */
	protected $_bouncy = FALSE;

	/**
	 * When finishing dragging, this number is used to define the acceleration
	 * rate of the marker during the bounce down to earth. The default value for
	 * this option is 1.
	 *
	 * @var int
	 */
	protected $_bounceGravity = 1;

	/**
	 * Auto-pan the map as you drag the marker near the edge. If the marker is
	 * draggable the default value for this option is true.
	 *
	 * @var bool
	 */
	protected $_autoPan = FALSE;

	/**
	 * When true, indicates that the map should not initially display the
	 * GMarker. To turn on the overlay, call GMarker.show(). By default, this
	 * value is set to false.
	 *
	 * @var bool
	 */
	protected $_hide = FALSE;

	/**
	 *
	 *
	 * Class Constructor
	 *
	 *
	 */

	/**
	 * Constructs L8M_Google_Maps_Overlay_Marker instance
	 *
	 * @param L8M_Google_Maps_LatLng $latLng
	 */
	public function __construct($latLng = NULL)
	{
		$this->setLatLng($latLng);
	}

	/**
	 *
	 *
	 * Setter Methods
	 *
	 *
	 */

	/**
	 * Sets auto pan.
	 *
	 * @param  bool $enabled
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public function setAutoPan($enabled = FALSE)
	{
		$this->_autoPan = (bool) $enabled;
		return $this;
	}

	/**
	 * Sets bounce gravity.
	 *
	 * @param int $gravity
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public function setBounceGravity($gravity = NULL)
	{
		$this->_bounceGravity = (int) $gravity;
		return $this;
	}

	/**
	 * Sets bouncy.
	 *
	 * @param  bool $enabled
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public function setBouncy($enabled = FALSE)
	{
		$this->_bouncy = (bool) $enabled;
		return $this;
	}

	/**
	 * Sets clickable.
	 *
	 * @param  bool $enabled
	 * @return L8M_Google_Maps_Overlay_Marker
	 *
	 */
	public function setClickable($enabled = FALSE)
	{
		$this->_clickable = (bool) $enabled;
		return $this;
	}

	/**
	 * Sets drag cross move.
	 *
	 * @param  bool $enabled
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public function setDragCrossMove($enabled = FALSE)
	{
		$this->_dragCrossMove = (bool) $enabled;
		return $this;
	}

	/**
	 * Sets draggable.
	 *
	 * @param  bool $enabled
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public function setDraggable($enabled = FALSE)
	{
		$this->_draggable = (bool) $enabled;
		return $this;
	}

	/**
	 * Sets hide.
	 *
	 * @param  bool $_hide
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public function setHide($enabled = FALSE)
	{
		$this->_hide = (bool) $enabled;
		return $this;
	}

	/**
	 * Sets URL of icon or a L8M_Google_Maps_Icon instance.
	 *
	 * @param  string|L8M_Google_Maps_Icon $_icon
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public function setIcon($icon = NULL)
	{
		if ($icon instanceof L8M_Google_Maps_Icon ||
			is_string($icon)) {
			$this->_icon = $icon;
		}
		return $this;
	}

	/**
	 * Sets L8M_Google_Maps_LatLng of marker.
	 *
	 * @param  L8M_Google_Maps_LatLng $latLng
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public function setLatLng($latLng = NULL)
	{
		if ($latLng instanceof L8M_Google_Maps_LatLng) {
			$this->_latLng = $latLng;
		}
		return $this;
	}

	/**
	 * Sets title.
	 *
	 * @param  string $title
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public function setTitle($title = NULL)
	{
		$this->_title = $title;
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
	 * Returns TRUE when auto pan is enabled for marker.
	 *
	 * @return bool
	 */
	public function getAutoPan()
	{
		return $this->_autoPan;
	}

	/**
	 * Returns bounce gravity.
	 *
	 * @return int
	 */
	public function getBounceGravity()
	{
		return $this->_bounceGravity;
	}

	/**
	 * Returns TRUE when bounce is enabled.
	 *
	 * @return bool
	 */
	public function getBouncy()
	{
		return $this->_bouncy;
	}

	/**
	 * Returns TRUE when marker is clickable.
	 *
	 * @return bool
	 */
	public function getClickable()
	{
		return $this->_clickable;
	}

	/**
	 * Returns TRUE when drag cross move is enabled.
	 *
	 * @return bool
	 */
	public function getDragCrossMove()
	{
		return $this->_dragCrossMove;
	}

	/**
	 * Returns TRUE when marker is draggable.
	 *
	 * @return bool
	 */
	public function getDraggable()
	{
		return $this->_draggable;
	}

	/**
	 * Returns TRUE when marker is initially hidden.
	 *
	 * @return bool
	 */
	public function getHide()
	{
		return $this->_hide;
	}

	/**
	 * Returns URL of icon.
	 *
	 * @return string|L8M_Google_Maps_Icon
	 */
	public function getIcon()
	{
		return $this->_icon;
	}

	/**
	 * Returns L8M_Google_Maps_LatLng instance.
	 *
	 * @return L8M_Google_Maps_LatLng
	 */
	public function getLatLng()
	{
		return $this->_latLng;
	}

	/**
	 * Returns title.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->_title;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns L8M_Google_Maps_Overlay_Marker instance from address.
	 *
	 * @param  string|array $address
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public static function fromAddress($address = NULL)
	{
		if (is_string($address) ||
			is_array($address)) {
			return self::fromLatLng(L8M_Google_Maps_LatLng::fromAddress($address));
		}
		return NULL;
	}

	/**
	 * Returns L8M_Google_Maps_Overlay_Marker from L8M_Google_Maps_LatLng
	 * instance.
	 *
	 * @param  L8M_Google_Maps_LatLng $latLng
	 * @return L8M_Google_Maps_Overlay_Marker
	 */
	public static function fromLatLng($latLng = NULL)
	{
		if ($latLng instanceof L8M_Google_Maps_LatLng) {
			$marker = new self($latLng);
			return $marker;
		}
		return NULL;
	}

	/**
	 * Creates and initializes a Javascript variable reflected by this class.
	 *
	 * @return string
	 */
	public function createAndInitialize($mapInstanceName = NULL, $html = NULL, $showInfoWindowOnStartup = TRUE)
	{
		parent::createAndInitialize();

		ob_start();

		/**
		 * marker has an L8M_Google_Maps_Icon?
		 */
		if ($this->getIcon() instanceof L8M_Google_Maps_Icon) {
			echo $this->getIcon()->createAndInitialize();
		}
?>
<?php echo $this->getLatLng()->createAndInitialize(); ?>
var <?php echo $this->getInstanceName(); ?> = new google.maps.Marker(<?php echo $this->getLatLng()->getInstanceName() . (count($this->getOptions())>0 ? ', {' . $this->getOptionsAsJavascript() . '}' : ''); ?>);


<?php

		if ($mapInstanceName !== NULL &&
			$html !== NULL) {

			if ($showInfoWindowOnStartup) {
?>
<?php echo $mapInstanceName; ?>.openInfoWindowHtml(<?php echo $this->getLatLng()->getInstanceName(); ?>, '<?php echo $html; ?>');
<?php

			}

?>
// Adding a click-event to the marker
GEvent.addListener(<?php echo $this->getInstanceName(); ?>, 'click', function() {
// When clicked, open an Info Window
<?php echo $this->getInstanceName(); ?>.openInfoWindowHtml('<?php echo $html; ?>');
});
<?php

		}

		return ob_get_clean();

	}

}