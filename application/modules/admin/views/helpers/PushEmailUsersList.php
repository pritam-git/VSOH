<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/views/helpers/PushEmailUsersList.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: PushEmailUsersList.php 7 2019-05-10 15:11:40Z dp $
 */

/**
 *
 *
 * Admin_View_Helper_PushEmailUsersList
 *
 *
 */
class Admin_View_Helper_PushEmailUsersList extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Wraps push email users list HTML
	 *
	 * @param array $usersList
	 * @return string
	 */
	public function pushEmailUsersList($usersList)
	{
		if(isset($usersList[0])){
			ob_start();
			$tableHeaders = array_keys($usersList[0]);

			?>
			<!-- begin -->
			<h3><?= $this->view->translate('Sending PushMails To'); ?>:</h3>

			<table id="pushMailUsersTable" class="text-left col-xs-12">
				<tr>
			<?php
			foreach($tableHeaders as $headerText) {
			?>
					<th class="py-10 px-15"><?= str_replace('_', ' ', $headerText); ?></th>
			<?php
			}
			?>
				</tr>
			<?php
			foreach($usersList as $userDetails) {
			?>
				<tr>
			<?php
				foreach($userDetails as $detail) {
			?>
					<td class="py-10 px-15"><?= ($detail) ? $detail : '-'; ?></td>
			<?php
				}
			?>
				</tr>
			<?php
			}
			?>
			</table>
			<style>
				#pushMailUsersTable {
					font-family: arial, sans-serif;
					width: 100%;
					border-radius: 3px;
				}

				#pushMailUsersTable th {
					color: #fff;
					text-align: left;
				}

				#pushMailUsersTable td {
					border: 1px solid #dddddd;
					text-align: left;
				}

				#pushMailUsersTable tr {
					background-color: #ffffff;
				}

				#pushMailUsersTable tr:first-child {
					background: #337ab7;
					border: 1px solid #337ab7;
				}

			</style>
		<!-- end -->
		<?php
			return ob_get_clean();
		}
	}
}