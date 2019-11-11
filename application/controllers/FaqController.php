<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/FaqController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: FaqController.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * FaqController
 *
 *
 */
class FaqController extends L8M_Controller_Action
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
		if ($this->getOption('l8m.faq.enabled') == FALSE) {
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
		/**
		 * retrieve faq id
		 */
		$paramFaqId = $this->getRequest()->getParam('id');

		/**
		 * query the faqs
		 */
		$faqs = Doctrine_Query::create()
			->from('Default_Model_Faq f')
			->select('f.id, ft.question')
			->leftJoin('f.Translation ft')
			->where('ft.lang = ?', $this->_getLanguage())
			->orderBy('ft.question ASC')
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute()
		;

		/**
		 * query the faq
		 */
		$faq = Doctrine_Query::create()
			->from('Default_Model_Faq f')
			->select('f.id, ft.answer, ft.question')
			->leftJoin('f.Translation ft')
			->where('ft.lang = ?', $this->_getLanguage())
			->addwhere('f.id = ?', $paramFaqId)
			->orderBy('ft.question ASC')
			->limit(1)
			->setHydrationMode(Doctrine_Core::HYDRATE_SCALAR)
			->execute()
		;

		/**
		 * set view vars
		 */
		$this->view->faqId = $paramFaqId;
		$this->view->faqs = $faqs;
		$this->view->faq = $faq;
	}
}