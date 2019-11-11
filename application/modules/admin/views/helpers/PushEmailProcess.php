<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/views/helpers/PushEmailProcess.php
 * @author     Krishna Bhatt <nm@l8m.com>
 * @version    $Id: PushEmailProcess.php 7 2019-01-03 14:18:40Z nm $
 */

/**
 *
 *
 * Admin_View_Helper_PushEmailProcess
 *
 *
 */
class Admin_View_Helper_PushEmailProcess extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Wraps push email process HTML
	 *
	 * @param int $rowCount
	 * @return string
	 */
	public function pushEmailProcess($rowCount)
	{
		ob_start();
		?>
		<!-- begin -->
		<h3><?php echo $this->view->translate('Sending PushMails.'); ?></h3>

		<p>
			<?php echo $this->view->translate('Mail sending in-progress'); ?>
		</p>

		<div class="progress" style="height: 30px;">
			<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
			     aria-valuemin="0" aria-valuemax="100" style="line-height: 30px;">
				0% Complete
			</div>
		</div>

		<h4 class="pull-right">
			Status : <span id="completed-step">0</span>/<?php echo $rowCount; ?>
		</h4>
		<!-- end -->
		<?php
		return ob_get_clean();
	}
}