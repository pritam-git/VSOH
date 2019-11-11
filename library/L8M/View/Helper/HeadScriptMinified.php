<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/HeadScriptMinified.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: HeadScriptMinified.php 564 2018-05-10 12:49:13Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_HeadScriptMinified
 *
 *
 */
class L8M_View_Helper_HeadScriptMinified extends Zend_View_Helper_HeadScript
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 * A constant specifying an empty condition. This will be used for grouping
	 * minifiable Javascript content (whether it is head script or file script)
	 * by condition.
	 *
	 * @todo provide functionality
	 */
	const CONDITION_NONE = 'NONE';

	/**
	 * A constant specifiying scripts loaded from a remote server (which are
	 * unminifiable, as they either come from a CDN and already are minified, or
	 * because they change their contents irregularly, and proxying may not make
	 * sense.
	 */
	const REMOTE = 'REMOTE';

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
	 * Returns Zend_View_Helper_HeadScript instance.
	 *
	 * @return Zend_View_Helper_HeadScript
	 */
	public function headScriptMinified()
	{
		/**
		 * options
		 */
		$options = $this->_getOptions();

		/**
		 * continue only if minifying is enabled
		 */
		if (isset($options['enabled']) &&
			$options['enabled'] == TRUE &&
			isset($options['js']['enabled']) &&
			$options['js']['enabled'] == TRUE) {

			$scripts = array(
				self::REMOTE=>NULL,
				self::FILE=>NULL,
				self::SCRIPT=>NULL,
			);

			foreach ($this as $item) {
				if ($this->_isValid($item)) {
					/**
					 * file
					 */
					if (isset($item->attributes['src'])) {

						/**
						 * add it to list of javascript files that can be
						 * minified if it is minifiable resource
						 */
						if (L8M_Utility_Minify_Js::isMinifiable($item->attributes['src'])) {
							if (!isset($scripts[self::FILE])) {
								$scripts[self::FILE] = new stdClass();
								$scripts[self::FILE]->type = 'text/javascript';
								$scripts[self::FILE]->source = NULL;
							}
							$scripts[self::FILE]->attributes['src'][] = $item->attributes['src'];
						} else {
							/**
							 * remote (unminifiable)
							 */
							$scripts[self::REMOTE][] = $item;
						}
					} else
					/**
					 * head
					 */
					if ($item->source) {
						if (!isset($scripts[self::SCRIPT])) {
							$scripts[self::SCRIPT] = new stdClass();
							$scripts[self::SCRIPT]->type = 'text/javascript';
							$scripts[self::SCRIPT]->source = $item->source;
						} else {
							$scripts[self::SCRIPT]->source.= PHP_EOL . $item->source;
						}
					}
				}
			}

			if (isset($scripts[self::FILE])) {
				$scripts[self::FILE]->attributes['src'] = L8M_Utility_Minify_Js::getMinifyUrl($scripts[self::FILE]->attributes['src'], 'js', 'js');
			}

			$items = array();
			/**
			 * remote
			 */
			if (is_array($scripts[self::REMOTE]) &&
				count($scripts[self::REMOTE])>0) {
				foreach($scripts[self::REMOTE] as $remoteScript) {
					if ($this->_isValid($remoteScript)) {
						$items[] = $this->itemToString($remoteScript, NULL, NULL, NULL);
					}
				}
			}
			/**
			 * file
			 */
			if ($this->_isValid($scripts[self::FILE])) {
				$items[] = $this->itemToString($scripts[self::FILE], NULL, NULL, NULL);
			}
			/**
			 * head
			 *
			 * @todo reconsider (other attributes? conditional?)
			 * @todo caching?
			 */
			if ($this->_isValid($scripts[self::SCRIPT])) {
				require_once('JSMinPlus.php');
				$scripts[self::SCRIPT]->source = JSMinPlus::minify($scripts[self::SCRIPT]->source);
				if ($this->view) {
					$useCdata = $this->view->doctype()->isXhtml() ? true : false;
				} else {
					$useCdata = $this->useCdata ? true : false;
				}
				$escapeStart = ($useCdata) ? '//<![CDATA[' : '//<!--';
				$escapeEnd   = ($useCdata) ? '//]]>'	   : '//-->';
				$items[] = $this->itemToString($scripts[self::SCRIPT], NULL, $escapeStart, $escapeEnd);
			}
			return implode($this->_escape(PHP_EOL), $items);
		}
		return parent::headScript();
	}

	/**
	 * Create script HTML. (This is taken from Zend_View_Helper_HeadScript, but
	 * leaving out some unnecessary spaces)
	 *
	 * @param  string	 $type
	 * @param  array	  $attributes
	 * @param  string	 $content
	 * @param  string|int $indent
	 * @return string
	 */
	public function itemToString($item, $indent, $escapeStart, $escapeEnd)
	{
		$attrString = '';
		if (!empty($item->attributes)) {
			foreach ($item->attributes as $key => $value) {
				if (!$this->arbitraryAttributesAllowed()
					&& !in_array($key, $this->_optionalAttributes))
				{
					continue;
				}
				if ('defer' == $key) {
					$value = 'defer';
				}
				$attrString .= sprintf(' %s="%s"', $key, ($this->_autoEscape) ? $this->_escape($value) : $value);
			}
		}

		if (strlen($item->type) > 0) {
			$type = ($this->_autoEscape) ? $this->_escape($item->type) : $item->type;
			$html  = $indent . '<script type="' . $type . '"' . $attrString . '>';
		} else {
			$html  = $indent . '<script' . $attrString . '>';
		}
		if (!empty($item->source)) {
			  $html .= $indent . ($escapeStart ? PHP_EOL : '') . $escapeStart . PHP_EOL . $item->source . $indent . ($escapeEnd ? PHP_EOL : '') . $escapeEnd . PHP_EOL;
		}
		$html .= '</script>';

		if (isset($item->attributes['conditional'])
			&& !empty($item->attributes['conditional'])
			&& is_string($item->attributes['conditional']))
		{
			$html = '<!--[if ' . $item->attributes['conditional'] . ']> ' . $html . '<![endif]-->';
		}

		return $html;
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