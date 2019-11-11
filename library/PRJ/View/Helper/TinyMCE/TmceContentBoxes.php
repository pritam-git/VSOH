<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/TmceContentBoxes.php
 * @author     Santino Lange <sl@l8m.com>
 * @version    $Id: TmceContentBoxes.php 16 2014-07-10 11:36:38Z sl $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceContentBoxes
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceContentBoxes extends L8M_View_Helper
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
	public function tmceContentBoxes()
	{
		
		$display = NULL;

		$cache = L8M_Cache::getCache('PRJ_Cache');
		
		if ($cache) {
		
			$content = $cache->load('tmcecontentbox');
		
			if ($content === FALSE) {
				$content = $this->contentBoxes();
				$cache->save($content, 'tmcecontentbox');
			}
			$display .= $content;
		} else {
			$display .= $this->contentBoxes();
		}

		return $display;
		
	}

	/**
	 * Returns a contentBoxes.
	 *
	 * @return string
	 */
	public function contentBoxes()
	{

		$contentBoxCollection = Doctrine_Query::create()
			->from('Default_Model_ContentBox cb')
			->orderBy('cb.position ASC')
			->execute()
		;

		$i = 1;

		/**
		 * prepare html output
		 */
		$content = NULL;

		foreach ($contentBoxCollection as $contentBoxModel) {

			$actionResource = NULL;
			$url = NULL;

			$i++;

			$imgModel = $contentBoxModel->MediaImage->maxBox(190,150);
			$imgLink = $imgModel->getLink();

//			$rolloverImgLink = $contentBoxModel->RolloverMediaImage->maxBox(280,150);

			if ($contentBoxModel->action_id != NULL) {
				$actionResource = explode('.', $contentBoxModel->Action->resource);
				$url = $this->view->url(array('module'=>$actionResource[0], 'controller'=>$actionResource[1], 'action'=>$actionResource[2]), NULL, TRUE);
			}

			if ($contentBoxModel->product_group_id != NULL) {
				$url = $this->view->url(array('module'=>'shop', 'controller'=>'group', 'action'=>'index', 'productgroup'=>$contentBoxModel->ProductGroup->short), NULL, TRUE);
			}

			$content .= $this->_renderBox($contentBoxModel->title, $contentBoxModel->content, $imgLink, $url);
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
	private function _renderBox($title, $content, $imgLink, $url)
	{
		$returnValue = NULL;

		$urlLink = NULL;

		if ($url) {
			$urlLink = $this->_linkElement($url);
			$title = '<a href="' . $url . '">' . $title . '</a>';
		}

		$returnValue.= '<div class="content-box content">';
		$returnValue.= $this->_imageElement($imgLink, $url);
		$returnValue.= $urlLink;
		$returnValue.= '<h2 class="index">' . $title . '</h2>';
		$returnValue.= '<div class="content-box-text">' . $content . '</div>';
		$returnValue.= '</div>';

		return $returnValue;
	}

	private function _linkElement ($url, $content = NULL) {

		if (!$content) {
			$content = $this->view->translate('Weiterlesen', 'de');
		}

		$display = '<a class="button main" href="' . $url . '">' . $content . '</a>';

		return $display;

	}

	private function _imageElement($image, $url = FALSE) {

		$returnValue = NULL;

		if ($image) {
			
			$link = NULL;
			
			if ($url) {
				$link = '<a href="' . $url . '"></a>';
			}

			$style = 'style="background-image: url(' . $image . ');"';

			$returnValue .= '<div class="image" ' . $style . ' >' . $link . '</div>';

		}

		return $returnValue;


	}
}