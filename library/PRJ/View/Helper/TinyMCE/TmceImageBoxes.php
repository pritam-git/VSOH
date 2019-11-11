<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceImageBoxes.php
 * @author     Santino Lange <sl@l8m.com>
 * @version    $Id: TmceImageBoxes.php 6 2013-10-10 14:22:50Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceImageBoxes
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceImageBoxes extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a list with all images from the gallery
	 *
	 * @return string
	 */
	public function tmceImageBoxes()
	{
		$content = NULL;
		
		$this->view->headScript()->appendFile('/js/jquery/plugins/colorbox/colorbox/jquery.colorbox.js', 'text/javascript');
		$this->view->headLink()->appendStylesheet('/css/default/colorbox.css', 'all');

		$resource = L8M_Acl_CalledFor::resource();
		
		$infoPageParentModel = Doctrine_Query::create()
			->from('Default_Model_Action p')
			->addWhere('p.resource = ? ', array($resource))
			->limit(1)
			->execute()
			->getFirst()
		;

		if ($infoPageParentModel) {

			$collection = Doctrine_Query::create()
				->from('Default_Model_MediaImageM2nAction p')
				->addWhere('p.action_id = ? ', array($infoPageParentModel->id))
				->orderBy('p.position ASC')
				->execute()
			;

			if ($collection->count() > 0) {

				$content .= $this->_script();

				$content .= '<div class="content-boxes thumbnails">';
				
				foreach ($collection as $model) {

					$image = NULL;
					if ($model->media_image_id) {
						$fullImage = $model->MediaImage->getLink();
						$image = $model->MediaImage->maxBox(500, 500)->getLink();
						$image = 'style="background-image: url(' .  $image . ');"';

						$content .= '<div class="content-box content" ' . $image . ' data-colorbox-href="' . $fullImage . '" >';
						$content .= '</div>';
					
					}

				}
				$content .= '</div>';
				$content .= '<br class="clear"/>';
			}
		}

		return $content;
	}
	
	private function _script() {
		
		ob_start();
?>
		<script>
			$(document).ready(function(){
				$("main div.content-boxes.thumbnails div.content-box").colorbox({
					rel:'group1',
					maxHeight: "90%",
					maxWidth: "90%",
					current: 'Bild {current} von {total}',
					href: function() {
						return $(this).attr("data-colorbox-href");
					}
				});
			});
		</script>
<?php

		return ob_get_clean();
		
	}

}