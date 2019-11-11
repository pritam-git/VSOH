<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Mobile/Detector/Easy.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Easy.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Mobile_Detector_Easy
 *
 *
 */
class L8M_Mobile_Detector_Easy extends L8M_Mobile_Detector_Abstract
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * stores whether mobile device is a tablet or not
	 *
	 * @var $_isTablet boolean
	 */
	private $_isTablet = FALSE;

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes L8M_Mobile_Detector_Easy instance.
	 *
	 * @return void
	 */
	public function init()
	{

	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns TRUE if it is a mobile device.
	 * Code of: http://detectmobilebrowser.com/
	 *
	 * @return bool
	 */
	public function isMobileDevice()
	{
		$returnValue = FALSE;

		if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
			$useragent = $_SERVER['HTTP_USER_AGENT'];
		} else {
			$_SERVER['HTTP_USER_AGENT'] = NULL;
			$useragent = 'unknown';
		}
//		$useragent = 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0_2 like Mac OS X; de-de) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A400 Safari/6531.22.7';
//		$useragent = 'Mozilla/5.0 (Linux; Android 4.2.2; GT-I9505 Build/JDQ39) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.63 Mobile Safari/537.36 OPR/15.0.1162.61541';

		if (preg_match ( '/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent ) ||
			preg_match ( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i', substr ( $useragent, 0, 4 ) )) {

			$returnValue = TRUE;

			/**
			 * do we have to ignore tablets?
			 */
			if (L8M_Config::getOption('mobile.detector.easy.ignoreTablet')) {
				if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== FALSE) {
					$returnValue = FALSE;
				} else
				if (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== FALSE &&
					stripos($_SERVER['HTTP_USER_AGENT'], 'mobile') === FALSE){

					$returnValue = FALSE;
				}
			}
		}

		return $returnValue;
	}

	/**
	 * Returns TRUE if it is a tablet device.
	 * Code of: http://detectmobilebrowser.com/
	 *
	 * @return bool
	 */
	public function isTabletDevice()
	{
		$returnValue = FALSE;

		if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
			$useragent = $_SERVER['HTTP_USER_AGENT'];
		} else {
			$_SERVER['HTTP_USER_AGENT'] = NULL;
			$useragent = 'unknown';
		}
//		$useragent = 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0_2 like Mac OS X; de-de) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A400 Safari/6531.22.7';
//		$useragent = 'Mozilla/5.0 (Linux; Android 4.2.2; GT-I9505 Build/JDQ39) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.63 Mobile Safari/537.36 OPR/15.0.1162.61541';

		if (preg_match ( '/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent ) ||
			preg_match ( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i', substr ( $useragent, 0, 4 ) )) {

			/**
			 * is it a tablet
			 */
			if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== FALSE) {
				$returnValue = TRUE;
			} else
			if (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== FALSE &&
				stripos($_SERVER['HTTP_USER_AGENT'], 'mobile') === FALSE){

				$returnValue = TRUE;
			}
		}

		return $returnValue;
	}

	/**
	 * Returns device name.
	 *
	 * @return string
	 */
	public function getDeviceName($realName = TRUE)
	{
		$deviceName = NULL;
		if ($this->isMobileDevice()) {
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'iPhone') !== FALSE) {
				$deviceName = 'iPhone';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'iPad') !== FALSE) {
				$deviceName = 'iPad';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'BlackBerry') !== FALSE) {
				$deviceName = 'BlackBerry';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'HTC') !== FALSE) {
				$deviceName = 'HTC';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'LG') !== FALSE) {
				$deviceName = 'LG';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'MOT') !== FALSE) {
				$deviceName = 'MOT';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'Nokia') !== FALSE) {
				$deviceName = 'Nokia';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'Palm') !== FALSE) {
				$deviceName = 'Palm';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'SAMSUNG') !== FALSE) {
				$deviceName = 'SAMSUNG';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'SonyEricson') !== FALSE) {
				$deviceName = 'SonyEricson';
			} else
			if (stripos($_SERVER ['HTTP_USER_AGENT'], 'PDA') !== FALSE) {
				$deviceName = 'PDA';
			} else {
				$deviceName = 'unknown';
			}
		}

		if (!$realName) {
			$deviceName = strtolower($deviceName);
		}
		return $deviceName;
	}
}
