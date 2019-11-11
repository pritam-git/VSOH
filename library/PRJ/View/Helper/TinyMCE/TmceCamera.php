<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceCamera.php
 * @author     Santino Lange <sl@l8m.com>
 * @version    $Id: TmceCamera.php 163 2014-10-21 14:23:11Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceCamera
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceCamera extends L8M_View_Helper
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
	 * Returns a TmceCamera
	 *
	 * @return string
	 */
	public function tmceCamera($id = NULL)
	{

		$display = NULL;

		$cache = L8M_Cache::getCache('PRJ_Cache');

		if ($cache) {

			$content = $cache->load('tmcecamera');

			if ($content === FALSE) {
				$content = $this->camera($id);
				$cache->save($content, 'tmcecamera');
			}
			$display .= $content;
		} else {
			$display .= $this->camera($id);
		}

		return $display;
	}

	/**
	 * Returns a TmceCamera
	 *
	 * @return string
	 */
	public function camera($id = NULL)
	{

		$this->_createId($id);

		$display = NULL;

		$mvc = Zend_Layout::getMvcInstance()->getView();

		$resource = L8M_Acl_CalledFor::resource();

		$actionModel = Doctrine_Query::create()
			->from('Default_Model_Action a')
			->addWhere('a.resource = ?', array($resource))
			->limit(1)
			->execute()
			->getFirst()
		;

		$imageCollection = Doctrine_Query::create()
			->from('Default_Model_MediaImageM2nAction ma')
			->addWhere('ma.action_id = ?', array($actionModel->id))
			->orderBy('ma.position ASC')
			->execute()
		;

		$returnValue = NULL;

		$content = NULL;

		if (count($imageCollection) > 1) {

			$display.= $this->_getScript();

			foreach ($imageCollection as $imageModel) {

				$content.= $this->_renderImage($imageModel);

			}

			$display.= $this->_render($content);

		} else {

			foreach ($imageCollection as $imageModel) {

				$display.= $this->_renderImagePlain($imageModel);

			}

		}

		return $display;

	}

	private function _getScript () {

		ob_start();

?>

<script>

	jQuery(function() {
		var myCamera = jQuery('#<?php echo $this->_id; ?>');
		myCamera.camera({
			alignmen: 'topCenter',
			minHeight: '100px',
			loader : false,
			navigation: true,
			fx: 'simpleFade',
			navigationHover:false,
			thumbnails: false,
			playPause: false,
			pagination:false,
		});
	});

</script>

<?php

		return ob_get_clean();

	}

	private function _createId ($id = FALSE) {

		if (!$id) {

			$this->_id = md5(rand(10000, 99999));

		} else {

			$this->_id = $id;

		}

	}

	private function _render ($content) {

		$display = NULL;

		$display.= '<div class="camera_wrap camera_azure_skin" id="' . $this->_id . '">' . $content . '</div>';

		$display = '<div class="content content-bg">' . $display . '<br class="clear" /></div>';

		return $display;

	}

	private function _renderImage ($model) {

		$dataThumb = $model->MediaImage->maxBox(150, 150)->getLink();
		$dataSrc = $model->MediaImage->getLink();

		$display = NULL;

		if ($model->content) {

			$display = '<div class="camera_caption fadeFromBottom">' . $model->content . '</div>';

		}

		$display = '<div data-thumb="' . $dataThumb . '" data-src="' . $dataSrc . '">' . $display . '</div>';

		return $display;

	}

	private function _renderImagePlain ($model) {

		$dataThumb = $model->MediaImage->maxBox(150, 150)->getLink();
		$dataSrc = $model->MediaImage->getLink();

		$display = NULL;

		$display = '<img src="' . $dataSrc . '" />';

		return $display;

	}

}