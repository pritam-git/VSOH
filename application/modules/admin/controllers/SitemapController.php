<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/admin/controllers/SitemapController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: SitemapController.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * Admin_SitemapController
 *
 *
 */
class Admin_SitemapController extends L8M_Controller_Action
{

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes Admin_SitemapController.
	 *
	 * @return void
	 */
	public function init ()
	{
		$this->_helper->layout()->headline = 'Verwaltung';

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

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Sitemap');

		/**
		 * create sitemap
		 */
		$this->view->sitemapWritten = PRJ_Sitemap::writeXML();
	}
}