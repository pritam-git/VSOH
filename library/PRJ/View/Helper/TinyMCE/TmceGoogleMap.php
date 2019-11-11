<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceGoogleMap.php
 * @author     Santino Lange <sl@l8m.com>
 * @version    $Id: TmceGoogleMap.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceCamera
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceGoogleMap extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a tmceGoogleMap
	 *
	 * @return string
	 */
	public function tmceGoogleMap($address = FALSE) {

		if (!$address) {
			$address = L8M_Library::getUsableUrlStringOnly(str_replace('++', '+', str_replace('|', '', str_replace(' ', '+', PRJ_SiteConfig::getOption('google_map_address'))) . '+' . PRJ_SiteConfig::getOption('country')), '-', array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '-', '_', '+', ','));
		}

		$position = PRJ_Google::getCoordinates($address);

		$get = array(
			'f=q',
			'source=s_q',
			'hl=de',
			'geocode=',
			'q=' . $address,
			'sll=' . $position,
			'ie=UTF8',
			't=m',
			'z=14',
			'output=embed',
		);

		$src = 'https://maps.google.de/maps?' . implode('&amp;', $get);

		$display = NULL;

		$display.= '<div onclick="style.pointerEvents=\'none\'" class="iframe-overlay"></div>';

		$display.= '
			<iframe
				class="map"
				src="' . $src . '"
			>
			</iframe>
		';

		return $display;

	}

}