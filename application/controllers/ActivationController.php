<?php

/**
 * L8M
 *
 *
 * @filesource /application/controllers/ActivationController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ActivationController.php 531 2017-06-07 07:37:06Z rq $
 */

/**
 *
 *
 * ActivationController
 *
 *
 */
class ActivationController extends L8M_Controller_Action
{

	/**
	 *
	 *
	 * Initialization Methods
	 *
	 *
	 */

	/**
	 * Initializes ActivationController. As we want to enable URLs like
	 * http://www.l8m.de/activation/1fe2e1c21a8d1c0eca6c087115b7f0f3 we
	 * check here for the passed parameter.
	 *
	 * @return void
	 */
	public function init()
	{
		if ($this->getOption('authentication.registration.enabled') == FALSE) {
			$this->_redirect($this->_helper->url('index', 'index', 'default'));
		}

		/**
		 * action
		 */
		$action = $this->getRequest()->getActionName();

		/**
		 * is action an an MD5 hash, i.e., possibly an activation code?
		 */
		if (preg_match('/^[0-9a-f]{32}$/i', $action)) {
			$this->_redirect($this->_helper->url(
				'activate',
				'activation',
				'default',
				array(
					'code'=>$action,
				)
			));
		} else
		/**
		 * is action none of the allowed?
		 */
		if (!in_array($action, array(
			'activate',
			'expired',
			'unknown',
			'used',
		))) {
			$this->_redirect($this->_helper->url(
				'error404',
				'error',
				'default'
			));
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
	 * Activate action. This function is normally forwarded to by the
	 * initialization function
	 *
	 * @todo   retrieve activation with passed code that has not been used yet
	 * @todo   activate specified activation target
	 * @todo   forward to activation action of appropriate controller
	 * @return void
	 */
	public function activateAction()
	{

		/**
		 * code
		 */
		$code = $this->getRequest()->getParam('code');

		/**
		 * activation code is not in the right format
		 */
		if (!Default_Service_Activation::isCode($code)) {
			$this->_redirect($this->_helper->url(
				'unknown',
				NULL,
				NULL,
				array(
					'code'=>$this->getRequest()->getParam('code'),
				)
			));
		}
		/**
		 * attempt to retrieve activation
		 */
		$activation = Doctrine_Core::getTable('Default_Model_Activation')->findOneByActivationCode($code);
		/**
		 * activation has been found
		 */
		if ($activation instanceof Default_Model_Activation) {
			/**
			 * activation has been used already
			 */
			if ($activation->used_at != NULL) {
				$this->_redirect($this->_helper->url(
					'used',
					'activation',
					'default',
					array(
						'code'=>$code,
					)
				));
			}
			/**
			 * activation has expired
			 */
			if ($activation->expires_at <= date('Y-m-d H:i:s')) {
				$this->_redirect($this->_helper->url(
					'expired',
					'activation',
					'default',
					array(
						'code'=>$code,
					)
				));
			}

			/**
			 * target
			 */
			$target = Doctrine_Core::getTable($activation->target)->findOneById($activation->target_id);
			if ($target instanceof Doctrine_Record) {

				/**
				 * activate target
				 */
				$target->activated_at = date('Y-m-d H:i:s');
				$target->disabled = FALSE;
				$target->save();
				/**
				 * flag activation
				 *
				 * @todo consider also setting the disabled field
				 */
				$activation->used_at = $target->activated_at;
				$activation->save();
				/**
				 * redirect
				 */
				if (isset($activation->redirect_url)) {
					$this->_redirect($activation->redirect_url);
				}
			} else {
				/**
				 * target can not be activated
				 */
				$this->_redirect($this->_helper->url(
					'unknown',
					'activation',
					'default',
					array(
						'code'=>$code,
					)
				));
			}
		} else {
			/**
			 * no activation has been found
			 */
			$this->_redirect($this->_helper->url(
					'unknown',
					'activation',
					'default',
					array(
						'code'=>$code,
					)
				));
		}
	}

	/**
	 * Expired action. This action is forwarded to if an activation has already
	 * expired.
	 *
	 * @todo   consider giving an allowance for re-generating an activation code
	 *		   for a certain time after it has expired, which means that we need
			   to provide a form here
	 * @return void
	 */
	public function expiredAction()
	{

	}

	/**
	 * Unknown action. This action is forwared to if an activation code is not
	 * known.
	 *
	 * @return void
	 */
	public function unknownAction()
	{

	}

	/**
	 * Used action. This action is forwarded to if an activation has already
	 * been used.
	 *
	 * @return void
	 */
	public function usedAction()
	{

	}

}