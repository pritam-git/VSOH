<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/ContentBoxes.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ContentBoxes.php 96 2014-05-27 13:51:57Z nm $
 */

/**
 *
 *
 * System_View_Helper_ContentBoxes
 *
 *
 */
class Default_View_Helper_ContentBoxes extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a contentBoxes.
	 *
	 * @return string
	 */
	public function contentBoxes($breakAfter = 3)
	{

		$contentBoxCollection = Doctrine_Query::create()
			->from('Default_Model_ContentBox cb')
			->orderBy('cb.position ASC')
			->execute();

		$i = 1;

		/**
		 * prepare html output
		 */
		$content = NULL;

		foreach ($contentBoxCollection as $contentBoxModel) {

			$cssStyle = NULL;
			$actionResource = NULL;
			$url = NULL;

			if ($i % $breakAfter == 0) {
				$cssStyle = ' noborder';
			}

			$i++;

			$imgModel = $contentBoxModel->MediaImage->maxBox(280,150);
			$imgLink = $imgModel->getLink();

			if ($contentBoxModel->action_id != NULL) {
				$actionResource = explode('.', $contentBoxModel->Action->resource);
				$url = $this->view->url(array('module'=>$actionResource[0], 'controller'=>$actionResource[1], 'action'=>$actionResource[2]), NULL, TRUE);
			}

			$content .= $this->_renderBox($contentBoxModel->title, $contentBoxModel->content, $imgLink, $cssStyle, $url);
		}

		if ($content) {
			$content = '<div class="content-boxes">' . $content . '</div>';
		}

		return $content;

	}

	/**
	 * Render Box
	 *
	 * @param $title
	 * @param $content
	 * @param $imgLink
	 * @param $cssStyle
	 * @param $url
	 * @return String
	 */
	private function _renderBox($title, $content, $imgLink, $cssStyle, $url)
	{
		$returnValue = NULL;

		$urlLink = NULL;
		if ($url) {
			$urlLink = '<p class="read-more"><a href="' . $url . '" class="link">' . $this->view->translate('weiterlesen', 'de') . '</a></p>';
		}

		$returnValue .= '<div class="content-box' . $cssStyle . '">';
		$returnValue .= '<h2 class="index">' . $title . '</h2>';
		$returnValue .= '<img src="' . $imgLink . '" alt="' . $title . '" />';
		$returnValue .= '<div class="content-box-text">' . $content . '</div>';
		$returnValue .= $urlLink;
		$returnValue .= '</div>';

		return $returnValue;
	}
}