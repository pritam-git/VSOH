<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/BlankSystemThirdMenu.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BlankSystemThirdMenu.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_BlankSystemThirdMenu
 *
 *
 */
class L8M_View_Helper_BlankSystemThirdMenu extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Create Third-Menu
	 *
	 * @return string
	 */
	public function blankSystemThirdMenu()
	{
		$returnValue = NULL;

		if (isset($this->view->modelFormListButtons) &&
			count($this->view->modelFormListButtons) > 0) {

			$activeResource = $this->view->layout()->calledForResource;

			if (isset($this->view->modelFormListLeadThroughUrl) &&
				is_array($this->view->modelFormListLeadThroughUrl)) {

				$modelFormListLeadThroughUrl = $this->view->modelFormListLeadThroughUrl;
			} else {
				$modelFormListLeadThroughUrl = array();
			}

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

				$rememberOldCalledForResource = $this->view->layout()->rememberOldCalledForResource;
				if (count($rememberOldCalledForResource) > 0) {
					$activeResource = array_shift($rememberOldCalledForResource);
				}
			} else {
				$activeController = $this->view->layout()->calledForControllerName;
				$activeModule = $this->view->layout()->calledForModuleName;
			}

			$menuContent = NULL;

			$menuContent .= '<li class="separator">|</li>';

			$secondMenuName = $this->view->blankSystemSecondMenu(TRUE);
			if ($secondMenuName) {
				$menuContent .= '<li class="main"><span>' . $this->view->blankSystemSecondMenu(TRUE) . '<span class="sign">&raquo;</span></span></li>';
			}

			$activeCss = NULL;
			if ($activeResource == L8M_Acl_Resource::getResourceName($activeModule, $activeController, 'list')) {
				$activeCss = ' class="active"';
			}
			$menuContent .= '<li' . $activeCss . '><a href="' . $this->view->url(array_merge(array('module'=>$activeModule, 'controller'=>$activeController, 'action'=>'list'), $modelFormListLeadThroughUrl), NULL, TRUE) . '">' . $this->view->translate('List') . '</a></li>';
			$menuContent .= '<li class="separator">|</li>';

			foreach ($this->view->modelFormListButtons as $menuButton) {
				$createLink = FALSE;
				$tempResource = NULL;
				$tempUrl = array();
				$tempMenuContent = NULL;
				if (isset($menuButton['name'])) {
					if (isset($menuButton['url']) &&
						count($menuButton['url']) >= 3 &&
						isset($menuButton['url']['action']) &&
						isset($menuButton['url']['controller']) &&
						isset($menuButton['url']['module'])) {

						$tempResource = L8M_Acl_Resource::getResourceName($menuButton['url']['module'], $menuButton['url']['controller'], $menuButton['url']['action']);
						if (!isset($menuButton['needSelectedRow']) ||
							(isset($menuButton['needSelectedRow']) && !$menuButton['needSelectedRow'])) {

							$tempUrl = $menuButton['url'];
							$createLink = TRUE;
						}
					}

					if (!$tempResource &&
						isset($menuButton['onpress']) &&
						$menuButton['onpress'] != 'flexPress()') {

						if ($menuButton['onpress'] == 'function:flexAdd') {
							$tempResource = L8M_Acl_Resource::getResourceName($activeModule, $activeController, 'create');
							$tempUrl = array(
								'module'=>$activeModule,
								'controller'=>$activeController,
								'action'=>'create'
							);
							$createLink = TRUE;
						} else
						if ($menuButton['onpress'] == 'function:flexEdit') {
							$tempResource = L8M_Acl_Resource::getResourceName($activeModule, $activeController, 'edit');
						} else
						if ($menuButton['onpress'] == 'function:flexDelete') {
							$tempResource = L8M_Acl_Resource::getResourceName($activeModule, $activeController, 'delete');
						}
					}

					$activeCss = NULL;
					if ($tempResource == $activeResource) {
						$activeCss = ' class="active"';
					}
					if ($createLink) {
						$tempMenuContent = '<a href="' . $this->view->url(array_merge($tempUrl, $modelFormListLeadThroughUrl), NULL, TRUE) . '">' . $menuButton['name'] . '</a>';
					} else {
						$tempMenuContent = '<span>' . $menuButton['name'] . '</span>';
					}
					$tempMenuContent = '<li' . $activeCss . '>' . $tempMenuContent . '</li>';
				} else
				if (isset($menuButton['separator'])) {
					$tempMenuContent = '<li class="separator">|</li>';
				}

				$menuContent .= $tempMenuContent;
			}
			if ($menuContent) {
				$returnValue = '<div id="third-menu"><ul class="third-menu">' . $menuContent . '</ul><br class="clear" /></div>';
			}
		}

		return $returnValue;
	}
}