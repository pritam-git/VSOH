<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/Google.php
 * @author     Santino Lange <sl@lmoc.m8>
 * @version    $Id: Google.php 9 2014-06-26 09:16:42Z nm $
 */

/**
 *
 *
 * PRJ_Google
 *
 *
 */
class PRJ_Google
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

	static public function getCoordinates($address){

		$address = str_replace(' ', '+', $address);
		$url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address='. $address;
		$opts = array(
			'http'=>array(
				'timeout'=>'2',
			)
		);
		$context = stream_context_create($opts);


		$response = file_get_contents($url, FALSE, $context);
		$json = json_decode($response,TRUE);

		if (isset($json['status']) &&
			$json['status'] == 'ZERO_RESULTS') {

			$lat = Default_Model_MetaConfiguration::getModelByShort('place_location_latitude');
			$lan = Default_Model_MetaConfiguration::getModelByShort('place_location_longitude');
			if (!$lat ||
				!$lan) {

				$lat = 0;
				$lan = 0;
			}
			$returnValue = $lat . ',' . $lan;
		} else {
			$returnValue = $json['results'][0]['geometry']['location']['lat'] . ',' . $json['results'][0]['geometry']['location']['lng'];
		}

		return $returnValue;

	}


}