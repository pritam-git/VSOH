<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Navigation.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Navigation.php 112 2014-06-17 06:59:04Z nm $
 */

/**
 *
 *
 * L8M_Navigation
 *
 *
 */
class L8M_Navigation extends L8M_Navigation_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Creates a dynamic menu.
	 *
	 * @param integer $navigationID
	 * @param string $dynamicShort
	 * @param array $urlOptions
	 * @return array
	 */
	protected function createDynamicNavigation($navigationID, $dynamicShort, $urlOptions)
	{
		return array();
	}

	/**
	 * Creates menu at beginning
	 * Returns a navigation array.
	 *
	 * Example:
	 * $navigationArray[] = array(
	 *     'label'=>'Menu-Name',
	 *     'style'=>'display:block; font-size:12px;',
	 *     'class'=>'CSS-Class',
	 *     'title'=>'Menu-Title',
	 *     'target'=>'_new',
	 *     'visible'=>TRUE,
	 *     'module'=>'default',
	 *     'controller'=>'contact',
	 *     'action'=>'index',
	 *     'params'=>array(
	 *         'param'=>'value',
	 *     ),
	 *     'id'=>'uid-of-menu-item',
	 *     'pages'=>array(), // possible next sub-navigation-array
	 *     'doNotTranslate'=>FALSE,
	 *     'defaultLanguage'=>L8M_Locale::getDefault(),
	 * );
	 *
	 * @return array
	 */
	protected function createNavigationStart()
	{
		/**
		 * empty navigation array
		 */
		$navigationArray = array();

		return $navigationArray;
	}

	/**
	 * Creates menu at the end
	 * Returns a navigation array.
	 *
	 * Example:
	 * $navigationArray[] = array(
	 *     'label'=>'Menu-Name',
	 *     'style'=>'display:block; font-size:12px;',
	 *     'class'=>'CSS-Class',
	 *     'title'=>'Menu-Title',
	 *     'target'=>'_new',
	 *     'visible'=>TRUE,
	 *     'module'=>'default',
	 *     'controller'=>'contact',
	 *     'action'=>'index',
	 *     'params'=>array(
	 *         'param'=>'value',
	 *     ),
	 *     'id'=>'uid-of-menu-item',
	 *     'pages'=>array(), // possible next sub-navigation-array
	 *     'doNotTranslate'=>FALSE,
	 *     'defaultLanguage'=>L8M_Locale::getDefault(),
	 * );
	 *
	 * @return array
	 */
	protected function createNavigationEnd()
	{
		/**
		 * empty navigation array
		 */
		$navigationArray = array();

		return $navigationArray;
	}
}