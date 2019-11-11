<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/BlankSystemTopMenu.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BlankSystemTopMenu.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_BlankSystemTopMenu
 *
 *
 */
class L8M_View_Helper_BlankSystemTopMenu extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Create Top-Menu
	 *
	 * @return string
	 */
	public function blankSystemTopMenu()
	{
		$returnValue = NULL;

		/**
		 * retrieve active module
		 */
		if ($this->view->layout()->isException) {
			$rememberOldCalledForModuleName = $this->view->layout()->rememberOldCalledForModuleName;
			$rememberOldCalledForControllerName = $this->view->layout()->rememberOldCalledForControllerName;

			if (count($rememberOldCalledForModuleName) > 0) {
				if (count($rememberOldCalledForControllerName) > 0) {
					$activeController = array_shift($rememberOldCalledForControllerName);
				} else {
					$activeController = $this->view->layout()->rememberCalledForControllerName;
				}
				$activeModule = array_shift($rememberOldCalledForModuleName);
			} else {
				$activeModule = $this->view->layout()->rememberCalledForModuleName;
				$activeController = $this->view->layout()->rememberCalledForControllerName;
			}

			if ($activeController == 'media' &&
				Zend_Auth::getInstance()->hasIdentity()) {

				$activeModule = L8M_Acl_Resource::getModuleNameFromResource(Zend_Auth::getInstance()->getIdentity()->Role->default_action_resource);
			}
		} else {
			if ($this->view->layout()->calledForControllerName != 'media') {
				$activeModule = $this->view->layout()->calledForModuleName;
			} else {
				if (Zend_Auth::getInstance()->hasIdentity()) {
					$activeModule = L8M_Acl_Resource::getModuleNameFromResource(Zend_Auth::getInstance()->getIdentity()->Role->default_action_resource);
				}
			}
		}

		/**
		 * start HTML output
		 */
		ob_start();
		$hasActiveBorder = FALSE;
		$linkArray = $this->view->blankSystemModuleMenuStructure();
		foreach ($linkArray as $link) {

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
					$cssActive = ' class="active"';
				}

?>
<li<?php echo $cssActive; ?>>
	<a href="<?php echo $link['link']; ?>" class="<?php echo $link['css']; ?>" title="<?php echo $link['name']; ?>"><?php echo $link['name']; ?></a>
</li>
<?php

			}
		}
		$returnValue = '<ul class="menu">' . ob_get_clean() . '</ul>';

		return $returnValue;
	}
}