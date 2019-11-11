<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceMediaImageCredits.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TmceMediaImageCredits.php 556 2018-01-18 19:43:01Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceMediaImageCredits
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceMediaImageCredits extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a TmceMediaImageCredits.
	 *
	 * @return string
	 */
	public function tmceMediaImageCredits($parameter = NULL)
	{
		$returnValue = $parameter;

		$mediaImageDataArray = Doctrine_Query::create()
			->from('Default_Model_MediaImage m')
			->select('DISTINCT m.author, m.copyright')
			->addWhere('m.author IS NOT NULL OR m.author <> ? OR m.copyright IS NOT NULL OR m.copyright <> ? ', array('', ''))
			->orderBy('m.author ASC, m.copyright ASC')
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute()
		;

		$creditsList = array();
		if (is_array($mediaImageDataArray) &&
			count($mediaImageDataArray) > 0) {

			foreach ($mediaImageDataArray as $mediaImageData) {
				if ($mediaImageData['m_author'] &&
					stripos($mediaImageData['m_copyright'], $mediaImageData['m_author']) !== FALSE) {

					$tmpCreditsText = $mediaImageData['m_copyright'];
				} else {
					if ($mediaImageData['m_author'] &&
						$mediaImageData['m_copyright']) {

						$tmpCreditsText = $mediaImageData['m_author'] . ' - ' . $mediaImageData['m_copyright'];
					} else
					if ($mediaImageData['m_author']) {
						$tmpCreditsText = $mediaImageData['m_author'];
					} else
					if ($mediaImageData['m_copyright']) {
						$tmpCreditsText = $mediaImageData['m_copyright'];
					}
				}

				$tmpCreditsText = trim($this->_replaceKnown($tmpCreditsText));

				if ($tmpCreditsText) {
					$creditsList[] = '<li>' . $tmpCreditsText . '</li>';
				}
			}

			if (count($creditsList)) {
				$returnValue = '<ul>' . implode('', $creditsList) . '</ul>';
			}
		}

		return $returnValue;
	}

	private function _replaceKnown($creditsText)
	{
		$replaceTextArray = array(
			'HAHN media group ag.'=>'<a href="http://www.hahn-media.ch" class="external">&copy; HAHN media group ag.</a>',
			'FreeGreatPicture.com'=>'<a href="http://www.freegreatpicture.com" class="external">&copy; FreeGreatPicture.com</a>',
			'fotolia'=>'<a href="http://www.fotolia.com" class="external">&copy; fotolia.com</a>',
			'shutterstock'=>'<a href="http://www.shutterstock.com" class="external">&copy; shutterstock.com</a>',
			'iStockphoto'=>'<a href="http://www.istockphoto.com" class="external">&copy; iStockphoto.com</a>',
		);

		foreach ($replaceTextArray as $replaceMask => $replaceText) {
			if ($creditsText == $replaceMask) {
				$creditsText = $replaceText;
			} else
			if (stripos($creditsText, $replaceMask) !== FALSE) {
				$creditsText = $replaceText;
			} else
			if (stripos($creditsText, $replaceMask . '.com') !== FALSE) {
				$creditsText = $replaceText;
			} else
			if (stripos($creditsText, 'www.' . $replaceMask . '.com') !== FALSE) {
				$creditsText = $replaceText;
			} else
			if (stripos($creditsText, 'http://' . $replaceMask . '.com') !== FALSE) {
				$creditsText = $replaceText;
			} else
			if (stripos($creditsText, 'http://www.' . $replaceMask . '.com') !== FALSE) {
				$creditsText = $replaceText;
			}
		}

		return $creditsText;
	}
}
