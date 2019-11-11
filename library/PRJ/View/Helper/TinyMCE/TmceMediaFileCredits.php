<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceMediaFileCredits.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TmceMediaFileCredits.php 500 2016-07-19 12:38:29Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceMediaFileCredits
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceMediaFileCredits extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a TmceMediaFileCredits.
	 *
	 * @return string
	 */
	public function tmceMediaFileCredits($parameter = NULL)
	{
		$returnValue = NULL;

		$mediaImageDataArray = Doctrine_Query::create()
			->from('Default_Model_MediaFile m')
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
		$creditsText = '&copy; ' . $creditsText;

		return $creditsText;
	}
}