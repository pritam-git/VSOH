<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/HeadLinkMinified.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: HeadLinkMinified.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_HeadLinkMinified
 *
 *
 */
class L8M_View_Helper_HeadLinkMinified extends Zend_View_Helper_HeadLink
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array with minify options.
	 *
	 * @var array
	 */
	protected static $_options = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns Zend_View_Helper_HeadLink instance.
	 *
	 * @return Zend_View_Helper_HeadLink
	 */
	public function headLinkMinified()
	{
		/**
		 * retrieve options
		 */
		$options = $this->_getOptions();

		/**
		 * continue only when css minifying is enabled
		 */
		if (isset($options['enabled']) &&
			$options['enabled'] == TRUE &&
			isset($options['css']['enabled']) &&
			$options['css']['enabled'] == TRUE) {

			$items = array();
			$stylesheets = array();
			$minifiedItems = array();
			$allItems = array();

			/**
			 * iterate over known items
			 */
			foreach ($this as $item) {

				/**
				 * if it is a stylesheet that is minifiable, add it to the list
				 * of stylesheets
				 */
				if (property_exists($item, 'type') &&
					$item->type == 'text/css' &&
					$item->conditionalStylesheet === FALSE &&
					L8M_Utility_Minify_Css::isMinifiable($item->href)) {

					$stylesheets[$item->media][] = $item->href;

				} else {

					/**
					 * otherwise pass through
					 */
					$items[] = $this->itemToString($item);
				}
			}
			/**
			 * iterate over collected stylesheets
			 */
			foreach ($stylesheets as $media=>$styles) {
				$minifiedItem = new stdClass();
				$minifiedItem->rel = 'stylesheet';
				$minifiedItem->type = 'text/css';
				$minifiedItem->href = L8M_Utility_Minify_Css::getMinifyUrl($styles, $media, 'css');
				$minifiedItem->media = $media;
				$minifiedItem->conditionalStylesheet = false;
				$minifiedItems[] = $this->itemToString($minifiedItem);
			}
			$allItems = array_merge($minifiedItems, $items);
			return implode(PHP_EOL, $allItems);
		}
		return parent::headLink();
	}

	/**
	 * Returns options as retrieved from Zend_Registry.
	 *
	 * @return array
	 */
	protected function _getOptions()
	{
		if (self::$_options === NULL &&
			Zend_Registry::isRegistered('Zend_Config')) {
			$options = Zend_Registry::get('Zend_Config')->toArray();
			if (isset($options['minify'])) {
				self::$_options = $options['minify'];
			}
		}
		return self::$_options;
	}

}