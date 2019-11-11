<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceSiteRights.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TmceSiteRights.php 5 2014-02-10 10:17:08Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceSiteRights
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceSiteRights extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a TmceSiteRights.
	 *
	 * @return string
	 */
	public function tmceSiteRights($parameter = NULL)
	{
		$returnValue = NULL;

		$model = Default_Model_SiteRights::getModelByShort($parameter);
		if ($model) {
			$returnValue = $this->_sysViewHelper($model->website_text_html);
		} else {
			$returnValue = vsprintf($this->view->translate('SiteRights-Parameter "%1s" not yet set.'), array($parameter));
		}

		return $returnValue;
	}

	private function _sysViewHelper($content)
	{

		/**
		 * SysViewHelper
		 */
		if (preg_match_all('|<div class="sysviewhelper l8m-object"[^>]+>###:(.*?):###</div>|is', $content, $match)) {
			$matchingPatterns = $match[0];
			$matchingStrings = $match[1];

			for ($i = 0; $i < count($matchingStrings); $i++) {
				$outputClassName = $matchingStrings[$i];
				if (substr($outputClassName, 0, strlen('PRJ_View_Helper_TinyMCE_Tmce')) == 'PRJ_View_Helper_TinyMCE_Tmce') {

					/**
					 * replacement pattern
					 */
					$replacePattern = $matchingPatterns[$i];

					/**
					 * clear data-rel
					*/
					if (preg_match('| data-rel="(.*?)"|is', $replacePattern, $partMatch)) {
						$replacePattern = str_replace($partMatch[0], '', $replacePattern);
					}

					/**
					 * clear style
					 */
					if (preg_match('| data-ignoredimension="(.*?)"|is', $replacePattern, $partMatch)) {
						$replacePattern = str_replace($partMatch[0], '', $replacePattern);
						if ($partMatch[1] == 'true') {
							if (preg_match('| style="(.*?)"|is', $replacePattern, $partMatch)) {
								$completeStyle = $partMatch[0];
								$newCompleteStyle = $completeStyle;
								$onlyStyle = $partMatch[1];
								if (preg_match('|height:(.*?);|is', $onlyStyle, $partMatch)) {
									$newCompleteStyle = str_replace($partMatch[0], '', $newCompleteStyle);
								}
								if (preg_match('|width:(.*?);|is', $onlyStyle, $partMatch)) {
									$newCompleteStyle = str_replace($partMatch[0], '', $newCompleteStyle);
								}
								$newCompleteStyle = str_replace('" "', '""', $newCompleteStyle);
								if ($newCompleteStyle == ' style=""') {
									$newCompleteStyle = '';
								}
								$replacePattern = str_replace($completeStyle, $newCompleteStyle, $replacePattern);
							}
						}
					}

					/**
					 * parmeters
					 */
					$parameter = NULL;
					if (preg_match('| data-parameters="(.*?)"|is', $replacePattern, $partMatch)) {
						$parameter = $partMatch[1];
						$replacePattern = str_replace($partMatch[0], '', $replacePattern);
					}

					/**
					 * function view-helper
					 */
					$tmp = explode('_', $outputClassName);
					$outputFunction = L8M_Library::lcFirst($tmp[count($tmp) - 1]);
					$output = $this->view->$outputFunction($parameter);

					/**
					 * replace content with content
					*/
					$replaceWith = str_replace('###:' . $outputClassName . ':###', $output, $replacePattern);
					$content = str_replace($matchingPatterns[$i], $replaceWith, $content);
				}
			}
		}

		return $content;
	}
}