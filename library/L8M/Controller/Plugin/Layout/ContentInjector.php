<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Layout/ContentInjector.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ContentInjector.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Layout_ContentInjector
 *
 *
 */
class L8M_Controller_Plugin_Layout_ContentInjector extends Zend_Controller_Plugin_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Called after an action is dispatched by Zend_Controller_Dispatcher.
     *
     * We need to make sure that this plugin is registered last before the
     * Zend_Controller_Plugin_Layout, so that this method gets called before
     * Zend_Controller_Plugin_Layout::postDispatch().
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {

        /**
    	 * return early on redirect
    	 */
        if (!$request->isDispatched() ||
        	$this->getResponse()->isRedirect() ||
        	L8M_Doctrine::isDisabled() ||
        	(!class_exists('Default_Model_Base_Page') ||
 		     !class_exists('Default_Model_Page'))) {
			return;
		}

        /**
         * retrieve layout
         */
		$layout = Zend_Layout::getMvcInstance();

		/**
		 * layout enabled?
		 */
		if (!($layout instanceof Zend_Layout) ||
    		!$layout->isEnabled()) {
			return;
    	}

		/**
		 * content
		 */
		$content = $this->getResponse()->getBody(TRUE);

		if ($content) {

			if (array_key_exists('default', $content)) {
				$contentKey = 'default';
			} else {
				$contentKey = $layout->getContentKey();
			}
			$content = $content[$contentKey];

			/**
			 * retrieve page content
			 */
	//		$page = Doctrine_Query::create()->from('Default_Model_Page p')
	//										->limit(1)
	//										->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

			$page = '<p class="last">' . $layout->getView()->note('Here is some content injected with the help of <code>L8M_Controller_Plugin_Layout_ContentInjector</code>.') . '</p>';

			if ($page) {
	    		$content = $content . $page;
				$this->getResponse()->setBody($content, 'default');
			}
		}

    }

}