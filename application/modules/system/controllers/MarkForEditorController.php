<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/MarkForEditorController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MarkForEditorController.php 201 2014-10-14 14:19:03Z nm $
 */

/**
 *
 *
 * System_MarkForEditorController
 *
 *
 */
class System_MarkForEditorController extends L8M_Controller_Action
{

	/**
	 *
	 *
	 * Initialization Methods
	 *
	 *
	 */


	/**
	 *
	 *
	 * Action Methods
	 *
	 *
	 */

	/**
	 * Renew action.
	 *
	 * @return void.
	 */
	public function renewAction()
	{
		if ($this->getRequest()->isXmlHttpRequest() || TRUE) {
			$modelName = $this->_request->getParam('model', NULL, FALSE);
			$ID = $this->_request->getParam('id', NULL, FALSE);
			$identifier = $this->_request->getParam('identifier', NULL, FALSE);

			$data = array(
				'newIdentifier'=>'',
				'error'=>'',
			);
			if (L8M_ModelForm_MarkedForEditor::isValid($modelName, $ID, $identifier)) {
				$data['newIdentifier'] = L8M_ModelForm_MarkedForEditor::renewIdentifier($modelName, $ID, $identifier);
			} else {
				$data['error'] = L8M_Translate::string('It is currently not permitted to edit the record.');
			}

			Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(TRUE);
			Zend_Layout::getMvcInstance()->disableLayout();

			/**
			 * json
			 */
			$bodyData = Zend_Json_Encoder::encode($data);

			$this->getResponse()
				->setHeader('Content-Type', 'application/json')
				->setBody($bodyData)
			;
		}
	}
}