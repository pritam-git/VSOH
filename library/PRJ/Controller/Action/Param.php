<?php
/**
 * L8M
 *
 *
 * @filesource library/PRJ/Controller/Action/Param.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Param.php 43 2014-04-22 12:01:51Z nm $
 */


/**
 *
 *
 * PRJ_Controller_Action_Param
 *
 *
 */
class PRJ_Controller_Action_Param extends L8M_Controller_Action_Param
{
	public function __construct()
	{
		$this->setResourceParts(
			array(
				'default.team'=>array(
					'action'=>'detail',
					'param'=>'name',
					'role'=>'guest',
				),
				'default.news'=>array(
					'action'=>'detail',
					'param'=>'short',
					'role'=>'guest',
				),
				'default.blog'=>array(
					'action'=>'detail',
					'param'=>'short',
					'role'=>'guest',
				),
			)
		);
	}
}