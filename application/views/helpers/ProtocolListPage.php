<?php

/**
 * L8M
 *
 *
 * @filesource /application/views/helpers/ProtocolListPage.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: ProtocolListPage.php 7 2019-02-08 16:50:40Z nm $
 */

/**
 *
 *
 * System_View_Helper_ProtocolListPage
 *
 *
 */
class Default_View_Helper_ProtocolListPage extends Zend_View_Helper_Abstract
{
	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Wraps type selection process HTML
	 *
	 * @param Doctrine_Object_Collection $models
	 * @return string
	 */
	public function protocolListPage($models, $pager)
	{
		ob_start();
		?>
		<div class="row">
			<div class="col-md-12 searchBlock">
				<?= $this->view->searchForm; ?>
			</div>
		</div>
		<?php
		if ($models->count() > 0) {
			$controller = L8M_Acl_CalledFor::controller();

			foreach ($models as $ModelValue) {
				echo($this->view->imageBoxItem($ModelValue, TRUE));
			}
			echo '</div>';

			//pager url parameters
			$urlParams = array(
				'module'=>'default',
				'controller'=>$controller,
				'action'=>'index'
			);

			//if search string  is set, add it in array
			if(isset($this->view->searchString))
				$urlParams['searchString'] = $this->view->searchString;

			//if region available  is set, add it in array
			if(isset($this->view->region))
				$urlParams['region'] = $this->view->region;

			if(isset($this->view->searchString)) {
				echo $this->view->pager($pager, 1, $urlParams, 'pagination', 'page', $this->view->translate('First'), $this->view->translate('Last'), '<', '>');
			} else {
				echo $this->view->pager($pager, 1, $urlParams, 'pagination', 'page', $this->view->translate('First'), $this->view->translate('Last'), '<', '>');
			}
		} else {
			echo '<h4 class="mt-15 color3 font-normal">' . $this->view->translate('Kein Protokoll verfügbar.', 'de') . '</h4>';
		}

		$this->view->headScript()->captureStart();
		?>

		$(document).ready(function() {
			var searchString = '<?= isset($this->view->searchString) ? $this->view->searchString : ''; ?>';
			if(searchString != '') {
				$('#searchProtocolInput').val(searchString);
			}
		});

		<?php
		$this->view->headScript()->captureEnd();
		return ob_get_clean();
	}
}