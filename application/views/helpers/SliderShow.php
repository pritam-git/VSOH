<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/SliderShow.php
 * @author     Norbert Marks <nm@noctronic.de>
 * @version    $Id: SliderShow.php 4 2013-01-23 13:15:12Z nm $
 */

/**
 *
 *
 * System_View_Helper_SliderShow
 *
 *
 */
class Default_View_Helper_SliderShow extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a SliderShow.
	 *
	 * @param Default_Model_Gallery $galleryModel
	 *
	 * @return string
	 */
	public function sliderShow($galleryModel)
	{
		ob_start();


		$galleryPath = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'galleries' . DIRECTORY_SEPARATOR . $galleryModel->short;

		$fi = new FilesystemIterator($galleryPath, FilesystemIterator::SKIP_DOTS);
		$files = array();
		foreach ($fi as $file) {
			if ($fi->isFile()) {
				$files[rawurlencode($file->getFilename())] = rawurlencode($file->getFilename());
			}
		}
		sort($files);
		
		echo '<ul class="pgwSlideshow">';
		foreach ($files as $file) {
			echo '<li><img src="/galleries/' . $galleryModel->short . '/' . $file .'"></li>';
		}
		echo '</ul>';

?>
<?php

		return ob_get_clean();

	}

}