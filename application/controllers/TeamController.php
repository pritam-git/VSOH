<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/TeamController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TeamController.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * TeamController
 *
 *
 */
class TeamController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Initialization Methods
	 *
	 *
	 */
	public function init()
	{
		if ($this->getOption('l8m.team.enabled') == FALSE) {
			$this->_redirect($this->_helper->url('index', 'index', 'default'));
		}

		/**
		 * init parent
		 */
		parent::init();
	}

	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function indexAction ()
	{
		$page = $this->_request->getParam('page');

		$teamQuery = Doctrine_Query::create()
			->from('Default_Model_Team m')
			->orderBy('m.position ASC')
		;

		$teamPager = new Doctrine_Pager($teamQuery, $page, L8M_Config::getOption('l8m.team.maxPerPage'));
		$this->view->teamCollection = $teamPager
			->execute()
		;

		$this->view->createTeamDetailLink = L8M_Config::getOption('l8m.team.detail.enabled');
	}

	public function detailAction ()
	{
		if ($this->getOption('l8m.team.detail.enabled') == FALSE) {
			$this->_redirect($this->_helper->url('index', 'team', 'default'));
		}

		$teamModel = Doctrine_Query::create()
			->from('Default_Model_Team m')
			->where('m.short = ?', $this->_request->getParam('name'))
			->limit(1)
			->execute()
			->getFirst()
		;

		if (!$teamModel) {
			$this->_redirect($this->_helper->url('index', 'team', 'default'));
		} else {
			$this->view->teamModel = $teamModel;
		}
	}
}