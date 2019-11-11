<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceSlider.php
 * @author     Santino Lange <sl@l8m.com>
 * @version    $Id: TmceSlider.php 163 2014-10-21 14:23:11Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceSlider
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceSlider extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	private $_id = FALSE;

	/**
	 * Returns a TmceSlider
	 *
	 * @return string
	 */
	public function tmceSlider()
	{
		$display = '<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">';

		$sliderCollection = Doctrine_Query::create()
			->from('Default_Model_MediaImageM2nAction mia')
			->leftJoin('mia.Action a')
			->addWhere('a.resource = ?', array(L8M_Acl_CalledFor::resource()))
			->orderBy('mia.position ASC')
			->execute()
		;

		$display .= '<ol class="carousel-indicators">';

		$css = ' class="active"';

		for ($i = 0; $i < $sliderCollection->count(); $i++) {

			$display .= '<li data-target="#carousel-example-generic" data-slide-to="' . $i . '"' . $css .'></li>';

			if ($css != NULL) {
				$css = NULL;
			}

		}


		$display .= '</ol>';
		$display .= '<div class="carousel-inner" role="listbox">';

		$i = 1;
		foreach ($sliderCollection as $sliderModel) {

			$css = NULL;

			if ($i == 1) {
				$css = ' active';
			}

			$backgroundImageUrl = $sliderModel->MediaImage->getLink();

			$display .= '<div class="item' . $css .'" style="background-image:url(' . $backgroundImageUrl . ');">';
			$display .= '</div>';

			$i++;

		}
		$display .= '</div>';

		$display .= '
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>';
		$display .= '</div>';


		if ($sliderCollection->count() == 0) {
			$display = NULL;
		}
		return $display;

	}

}