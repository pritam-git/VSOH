<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Controller/Plugin/Admin.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Admin.php 384 2015-07-09 09:20:41Z nm $
 */

/**
 *
 *
 * L8M_Controller_Plugin_Admin
 *
 *
 */
class L8M_Controller_Plugin_Admin extends Zend_Controller_Plugin_Abstract
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
	 * This callback allows for proxy or filter behavior. By altering the
	 * request and resetting its dispatched flag (via
	 * {@link Zend_Controller_Request_Abstract::setDispatched() setDispatched(false)}),
	 * a new action may be specified for dispatching.
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request)
	{
		if ($this->_usePlugin()) {

			$layout = Zend_Layout::getMvcInstance();
			$layout->getView()->headLink()
				->appendStylesheet('/css/system/screen/admin/base.css', 'screen')
				->appendStylesheet('/css/system/screen/admin/color.css', 'screen')
				->appendStylesheet('/css/system/screen/admin/rhythm.css', 'screen')
				->appendStylesheet('/css/system/screen/admin/typography.css', 'screen')
			;
		}
	}

	/**
	 * Called before Zend_Controller_Front exits its dispatch loop.
	 *
	 * @return void
	 */
	public function dispatchLoopShutdown()
	{
		if ($this->_usePlugin()) {

			/**
			 * prevent caching
			 */
			$this->getResponse()->setHeader('Expires', 0, TRUE);
			$this->getResponse()->setHeader('Cache-Control', 'no-cache', TRUE);

			$viewFromMVC = Zend_Layout::getMvcInstance()->getView();
			$moduleMenuStructure = $viewFromMVC->blankSystemModuleMenuStructure();

			/**
			 * retrieve active module
			 */
			if ($viewFromMVC->layout()->isException) {
				$rememberOldCalledForModuleName = $viewFromMVC->layout()->rememberOldCalledForModuleName;
				if (count($rememberOldCalledForModuleName) > 0) {
					$activeModule = array_shift($rememberOldCalledForModuleName);
				} else {
					$activeModule = $viewFromMVC->layout()->rememberCalledForModuleName;
				}
			} else {
				$activeModule = $viewFromMVC->layout()->calledForModuleName;
			}

			ob_start();

?>
<div id="admin_backend" class="admin_backend">
	<ul class="iconized">
<?php

			$hasActiveBorder = FALSE;
			foreach ($moduleMenuStructure as $link)

					if (isset($link['border']) &&
						$link['border']) {

						if (!$hasActiveBorder) {
							$hasActiveBorder = TRUE;

?>
		<li class="border"> | </li>
<?php

						}
					} else {
						$hasActiveBorder = FALSE;
						$cssActive = NULL;
						if ($link['module'] == $activeModule) {
							$cssActive = ' active';
						}

?>
		<li class="<?php echo $link['css'] . $cssActive; ?>">
			<a href="<?php echo $link['link']; ?>" class="admin" title="<?php echo $link['name']; ?>"><?php echo $link['name']; ?></a>
		</li>
<?php

					}

?>
	</ul>
</div>
<?php

			$this->_output(ob_get_clean());
		}
	}

	/**
	 * Appends Debug Bar html output to the original page
	 *
	 * @param string $html
	 * @return void
	 */
	protected function _output($html)
	{
		$response = $this->getResponse();
		$response->setBody(str_ireplace('</body>', '<div id="L8M_blank">'.$html.'</div></body>', $response->getBody()));
	}

	protected function _usePlugin()
	{
		$returnValue = TRUE;

		if (!L8M_Config::getOption('l8m.adminPannel.frontend.enabled')) {
			$returnValue = FALSE;
		} else {
			if ($this->getRequest()->isXmlHttpRequest()) {
				$returnValue = FALSE;
			} else {

				$layout = Zend_Layout::getMvcInstance();
				if (!$layout) {
					$returnValue = FALSE;
				} else {

					$calledForModuleName = $layout->calledForModuleName;
					$calledForResource = $layout->calledForResource;

					$adminPannelNotInModule = L8M_Config::getOption('l8m.adminPannel.frontend.showNotInModule');
					if (!is_array($adminPannelNotInModule)) {
						$adminPannelNotInModule = array(
							'admin',
							'system',
							'system-model-list',
						);
					}

					$adminPannelNotForResource = L8M_Config::getOption('l8m.adminPannel.frontend.showNotForResource');
					if (!is_array($adminPannelNotForResource)) {
						$adminPannelNotForResource = array(
							'default.error.error',
							'default.error.error403',
							'default.error.error404',
							'default.error.error503',
							'default.error.error-hacking-attempt',
						);
					}

					if (in_array($calledForModuleName, $adminPannelNotInModule) ||
						in_array($calledForResource, $adminPannelNotForResource)) {

						$returnValue = FALSE;
					} else {

						if (!Zend_Auth::getInstance()->hasIdentity()) {
							$returnValue = FALSE;
						} else {

							$roleShort = Zend_Auth::getInstance()->getIdentity()->Role->short;
							$adminPannelForRole = L8M_Config::getOption('l8m.adminPannel.frontend.showForRole');
							if (!is_array($adminPannelForRole)) {
								$adminPannelForRole = array(
									'admin',
									'supervisor',
								);
							}
							if (!in_array($roleShort, $adminPannelForRole)) {

								$returnValue = FALSE;
							}
						}
					}
				}
			}
		}

		return $returnValue;
	}
}