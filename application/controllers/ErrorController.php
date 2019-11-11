<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/ErrorController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ErrorController.php 387 2015-07-13 16:35:17Z nm $
 */

/**
 *
 *
 * ErrorController
 *
 *
 */
class ErrorController extends L8M_Controller_Action
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 * An array with names of actions for which the AjaxContext switch will
	 * automatically be enabled.
	 *
	 * @var array
	 */
	public $ajaxable = array(
		'error'=>array(
			'html',
		),
		'error403'=>array(
			'html',
		),
		'error404'=>array(
			'html',
		),
		'error503'=>array(
			'html',
		),
		'error-hacking-attempt'=>array(
			'html',
		),
	);

	/**
	 * An array with names of actions for which the ActionContext switch will
	 * automatically be enabled.
	 *
	 * @var array
	 */
	public $contexts = array(
	);

	/**
	 *
	 *
	 * Initialization Method
	 *
	 *
	 */

	/**
	 * Initializes ErrorController instance.
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->_helper->ajaxContext->initContext();

		if (!$this->getRequest()->isXmlHttpRequest()) {
			$this->view->placeholder('bodyClass')->set('wide');
		} else {

			/**
			 * @todo don't know why it works not without
			 */
			Zend_Layout::getMvcInstance()->disableLayout();
		}

		$this->view->layout()->robots = 'noindex, nofollow';
		$this->_helper->layout->setLayout('errors');

		/**
		 * init parent
		 */
		parent::init();

		/**
		 * checking for exception and setup process
		 */
		if (isset($this->view->layout()->systemSetupProcessConfirmed) &&
			$this->view->layout()->systemSetupProcessConfirmed &&
			isset($this->view->layout()->setupWithoutDatabase) &&
			$this->view->layout()->setupWithoutDatabase) {

			$this->_helper->layout->setLayoutPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'scripts');
		}
	}

	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Error action.
	 *
	 * @return void
	 */
	public function errorAction ()
	{

		$errors = $this->_getParam('error_handler');

		if ($errors &&
			(
				$errors instanceof Exception ||
				$errors instanceof ArrayObject
			)) {

			Zend_Registry::get('Zend_Log')->exception($errors->exception);

			/**
			 * development
			 */
			if (L8M_Config::getOption('phpSettings.display_errors')) {

				$this->view->exception = $errors->exception;
				$this->view->exceptionType = $errors->type;
				$this->_forward('error503', 'error', 'default');
			}
		}
	}

	/**
	 * Error403 action. This action is called when the current user does not
	 * have enough privileges as needed for the request.
	 *
	 * @return void
	 */
	public function error403Action()
	{
		if (!$this->getRequest()->isXmlHttpRequest()) {
			$this->getResponse()->setHttpResponseCode(403);
		} else {
			$this->view->ajaxResponse = '';
		}
	}

	/**
	 * Error404 action. This action is called when no route could be found for
	 * the current request.
	 *
	 * @return void
	 */
	public function error404Action ()
	{
		if (!$this->getRequest()->isXmlHttpRequest()) {
			$this->getResponse()->setHttpResponseCode(404);
		} else {
			$this->view->ajaxResponse = '';
		}
	}

	/**
	 * Error503 action. This action is called when processing of the request
	 * resulted in an exception being thrown.
	 *
	 * @return void
	 */
	public function error503Action ()
	{
		if (!$this->getRequest()->isXmlHttpRequest()) {
			$this->getResponse()->setHttpResponseCode(503);
		} else {
			$this->view->ajaxResponse = '';
		}
	}

	/**
	 * ErrorHackingAttempt action. This action is called when identity is hacked.
	 *
	 * @return void
	 */
	public function errorHackingAttemptAction ()
	{

	}
}