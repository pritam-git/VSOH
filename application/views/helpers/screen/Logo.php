<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/Screen/Logo.php
 * @author     Santino Lange <sl@l8m.com>
 * @version    $Id: Logo.php 69 2014-08-11 16:10:19Z sl $
 */

/**
 *
 *
 * Default_View_Helper_Screen_Logo
 *
 *
 */
class Default_View_Helper_Screen_Logo extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a footer.
	 *
	 * @return string
	 */
	public function logo($content = NULL, $maxWidth = 300, $maxHeight = 200, $HTMLelement = 'a')
	{

		$style = NULL;

		if (!$content) {

			$content = 'OPC';

		}

		$image = PRJ_SiteConfig::getMediaImage('website_logo')->getLink();

		if ($image) {

			$style = 'style="background-image: url(' . $image . ');"';

		}

		$return = '<' . $HTMLelement . ' class="logo" href="' . $this->view->url(array('module'=>'default', 'controller'=>'index', 'action'=>'index'), NULL, TRUE) . '" ' . $style . ' >';
		$return.= $content;
		$return.= '</' . $HTMLelement . '>';

// 		$image = PRJ_SiteConfig::getMediaImage('opel_logo')->getLink();

// 		if ($image) {

// 			$style = 'style="background-image: url(' . $image . ');"';

// 		}

// 		$return.= '<' . $HTMLelement . ' class="logo" href="https://fr.opel.ch" ' . $style . ' >';
// 		$return.= $content;
// 		$return.= '</' . $HTMLelement . '>';

		return $return;

	}

}