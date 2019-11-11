<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Session/Translate.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Translate.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Session_Translate
 *
 *
 */
class L8M_Controller_Plugin_Session_Translate extends Zend_Controller_Plugin_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	protected $_translateOptions = array();

	/**
	 *
	 *
	 * Class Constructors
	 *
	 *
	 */

	/**
	 * Constructs L8M_Controller_Plugin_Session_Locale instance.
	 *
	 * @return void
	 */
	public function __construct($translateOptions)
	{
		if (is_array($translateOptions)) {
			$this->_translateOptions = $translateOptions;
		}
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */


	/**
	 * Called before an action is dispatched by Zend_Controller_Dispatcher.
	 *
	 * This callback allows for proxy or filter behavior.  By altering the
	 * request and resetting its dispatched flag (via
	 * {@link Zend_Controller_Request_Abstract::setDispatched() setDispatched(false)}),
	 * the current action may be skipped.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		if (!Zend_Registry::isRegistered('Zend_Translate')) {

			/**
			 * create translate adapter
			 */
			if (class_exists('Default_Model_Base_Translator', TRUE) &&
				L8M_Doctrine::isEnabled()) {

				/**
				 * modifie options
				 */
				$alternativeAdapter = $this->_translateOptions['options']['adapter'];
				$this->_translateOptions['options']['adapter'] = 'L8M_Translate_Adapter_Doctrine';
				$this->_translateOptions['options']['defaultLocale'] = L8M_Locale::getLang();

				try {
					$translate = new Zend_Translate(
						$this->_translateOptions['options']['adapter'],
						$this->_translateOptions['options']['data']['directory'],
						NULL,
						$this->_translateOptions['options']
					);
				} catch (Doctrine_Connection_Exception $exception) {
					$this->_translateOptions['options']['adapter'] = $alternativeAdapter;
					$translate = new Zend_Translate(
						$this->_translateOptions['options']['adapter'],
						$this->_translateOptions['options']['data']['directory'],
						NULL,
						$this->_translateOptions['options']
					);
				}
			} else {
				$translate = new Zend_Translate(
					$this->_translateOptions['options']['adapter'],
					$this->_translateOptions['options']['data']['directory'],
					NULL,
					$this->_translateOptions['options']
				);
			}

			/**
			 * set Zend_Translate instance as default translator for forms
			 */
			Zend_Form::setDefaultTranslator($translate);

			/**
			 * register
			 */
			Zend_Registry::set('Zend_Translate', $translate);
		}
	}
}