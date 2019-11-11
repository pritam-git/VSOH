<?php
/**
 * L8M
 *
 *
 * @filesource library/PRJ/Controller/Action/Var.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Var.php 63 2014-05-07 15:39:56Z nm $
 */


/**
 *
 *
 * PRJ_Controller_Action_Var
 *
 *
 */
class PRJ_Controller_Action_Var extends L8M_Controller_Action_Var
{
	public function __construct()
	{
		$this->setResourceParts(
			array(
				'default.news.page'=>array(
					'param'=>'page',
				),
			)
		);
	}
}