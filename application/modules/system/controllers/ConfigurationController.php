<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/ConfigurationController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: ConfigurationController.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_ConfigurationController
 *
 *
 */
class System_ConfigurationController extends L8M_Controller_Action
{

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
	public function indexAction()
	{
		$editableValues = array(
			'authentication.registration.enabled'=>array(
				'type'=>'boolean',
				'isArray'=>FALSE,
				'label'=>'User-Registration Enabled',
			),
			'mobile.enabled'=>array(
				'type'=>'boolean',
				'isArray'=>FALSE,
				'label'=>'Mobile-Detector Enabled',
			),
			'layout.screen.headTitle.prepend'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'Prepend Head-Title',
			),
			'layout.screen.headTitle.append'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'Append Head-Title',
			),
			'locale.default'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'Default Language',
			),
			'locale.supported'=>array(
				'type'=>'text',
				'isArray'=>TRUE,
				'label'=>'Supported Language',
			),
			'L8M_JQuery_Form_Element_Date.notSelectableDay'=>array(
				'type'=>'text',
				'isArray'=>TRUE,
				'label'=>'FormElement Date - not selectable day',
			),
			'L8M_JQuery_Form_Element_Date.notSelectableDate'=>array(
				'type'=>'text',
				'isArray'=>TRUE,
				'label'=>'FormElement Date - not selectable date',
			),
			'resources.mail.transport.password'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Transport Password',
			),
			'resources.mail.transport.username'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Transport Login',
			),
			'resources.mail.transport.host'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Transport Host',
			),
			'resources.mail.defaultFrom.email'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Default-From E-Mail'
			),
			'resources.mail.defaultFrom.name'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Default-From Name',
			),
			'resources.mail.defaultTo.email'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Default-To E-Mail',
			),
			'resources.mail.defaultTo.name'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Default-To Name',
			),
			'resources.mail.defaultReplyTo.email'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Default-Reply-To E-Mail',
			),
			'resources.mail.defaultReplyTo.name'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Default-Reply-To Name',
			),
			'resources.mail.defaultToStore.email'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Default-Reply-To-Store E-Mail',
			),
			'resources.mail.organisation'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'E-Mail - Default-Reply-To-Store Name',
			),
			'resources.multidb.default.host'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'Database Host',
			),
			'resources.multidb.default.username'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'Database Login',
			),
			'resources.multidb.default.password'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'Database Password',
			),
			'resources.multidb.default.dbname'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'Database Name',
			),
			'paypal.API_USERNAME'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'PayPal - API-Username',
			),
			'paypal.API_PASSWORD'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'PayPal - API-Password',
			),
			'paypal.API_SIGNATURE'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'PayPal - API-Signature',
			),
			'paypal.EMAIL'=>array(
				'type'=>'text',
				'isArray'=>FALSE,
				'label'=>'PayPal - E-Mail',
			),

		);

		$formConfigValues = array();

		// fill an array with all items from a directory
		$applicationIniPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'application.ini';
		$applicationIniPath2 = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'myApplication.ini';

		if (file_exists($applicationIniPath2)) {
			$applicationIniPath = $applicationIniPath2;
		}

		// open application.ini
		$handle = fopen($applicationIniPath, 'r');

		$fileContentArray = array();
		$maxLabelLength = 0;

		// walk through all lines of application.ini
		while (!feof($handle)) {
			$buffer = str_replace(PHP_EOL, '', fgets($handle));
			$fileContentArray[] = $buffer;

			// save state
			if (strlen($buffer) > 0 &&
				$buffer{0} == '[') {

				$environment = substr(trim($buffer), 1);
				$environment = substr($environment, 0, strlen($environment) - 1);
//				echo $state . '<br/>';
			}
			// save the searched values
			else
			if (strlen($buffer) > 0 &&
				$buffer{0} != ';' &&
				$buffer{0} != ' ') {

				foreach ($editableValues as $editableValue => $configArray) {
					$lineArray = explode('=', $buffer);
					$theValueLeft = trim(str_replace("\t", '', preg_replace("/\s+/", "", $lineArray[0])));
					$maxLabelLength = max($maxLabelLength, strlen($theValueLeft));

					unset($lineArray[0]);
					$theValueRight = implode('=', $lineArray);

					if ($theValueLeft == $editableValue) {
//						echo $buffer . '<br/>';

						$theValueRight = trim($theValueRight);
						if ($configArray['type'] == 'text' &&
							substr($theValueRight, 0, 1) == '"') {

							$theValueRight = substr($theValueRight, 1);
						}

						if ($configArray['type'] == 'text' &&
							substr($theValueRight, -1, 1) == '"') {

							$theValueRight = substr($theValueRight, 0, strlen($theValueRight) - 1);
						}

						$formConfigValues[$environment][$theValueLeft] = array(
							'value'=>$theValueRight,
							'type'=>$configArray['type'],
							'isArray'=>$configArray['isArray'],
							'label'=>$configArray['label'],
						);
					} else
					if ($theValueLeft == $editableValue . '[]' &&
						$configArray['isArray']) {

						$theValueLeft = $editableValue;

						$theValueRight = trim($theValueRight);
						if ($configArray['type'] == 'text' &&
							substr($theValueRight, 0, 1) == '"') {

							$theValueRight = substr($theValueRight, 1);
						}

						if ($configArray['type'] == 'text' &&
							substr($theValueRight, -1, 1) == '"') {

							$theValueRight = substr($theValueRight, 0, strlen($theValueRight) - 1);
						}

						if (!isset($formConfigValues[$environment])) {
							$formConfigValues[$environment] = array();
						}

						if (!isset($formConfigValues[$environment][$theValueLeft])) {
							$formConfigValues[$environment][$theValueLeft] = array();
						}

						if (!isset($formConfigValues[$environment][$theValueLeft]['values'])) {
							$formConfigValues[$environment][$theValueLeft]['values'] = array();
						}
						$formConfigValues[$environment][$theValueLeft]['values'][] = $theValueRight;
						$formConfigValues[$environment][$theValueLeft]['value'] = '';
						$formConfigValues[$environment][$theValueLeft]['type'] = $configArray['type'];
						$formConfigValues[$environment][$theValueLeft]['isArray'] = $configArray['isArray'];
						$formConfigValues[$environment][$theValueLeft]['label'] = $configArray['label'];
					}
				}
			}
		}
		fclose ($handle);

		$form = new System_Form_Configuration_Application();
		$form
			->setDecorators(
				array(
					new Zend_Form_Decorator_FormElements(),
					new Zend_Form_Decorator_HtmlTag(),
					//new L8M_Form_Decorator_Ajaxable(),
					new Zend_Form_Decorator_Form(),
					new L8M_Form_Decorator_ModelListFormBack($this->_helper->url('index', 'index', 'system')),
					new L8M_Form_Decorator(array('boxClass'=>'small l8m-model-form-base')),
				)
			)
		;
		$this->view->form = $form->buildMeUp($formConfigValues, $editableValues);
		$form->setAction($this->view->url(array('action'=>'index', 'controller'=>'configuration', 'module'=>'system'), NULL, TRUE));

		if ($form->isSubmitted() &&
			$form->isValid($this->getRequest()->getParams())) {

			$formValues = $form->getValues();

			// walk through all lines of application.ini
			$newFileContentArray = array();
			$valueUsedInNewConfiguration = array();
			$countLine = 0;
			foreach ($fileContentArray as $fileContentLine) {

				// save state
				if ($fileContentLine{0} == '[') {
					$environment = substr(trim($fileContentLine), 1);
					$environment = substr($environment, 0, strlen($environment) - 1);
	//				echo $state . '<br/>';
					$newFileContentArray[] = trim($fileContentLine);
				} else
				// save the searched values
				if (trim($fileContentLine) != '' &&
					$fileContentLine{0} != ';' &&
					$fileContentLine{0} != ' ') {

					$addNewLine = TRUE;
					foreach ($editableValues as $editableValue => $configArray) {
						if ($addNewLine) {
							$lineArray = explode('=', $fileContentLine);
							$theValueLeft = trim(str_replace("\t", '', preg_replace("/\s+/", "", $lineArray[0])));

							unset($lineArray[0]);
							$theValueRight = implode('=', $lineArray);

							if ($theValueLeft == $editableValue) {
								$formElementName = str_replace('-', '_', L8M_Library::getUsableUrlStringOnly($environment)) . '_' . strtolower(str_replace('.', '_', $theValueLeft));

								if (array_key_exists($formElementName, $formValues)) {
									if ($configArray['type'] == 'text') {
										$formValues[$formElementName] = '"' . $formValues[$formElementName] . '"';
									}

									$newFileContentArray[] = str_pad($theValueLeft, $maxLabelLength, ' ', STR_PAD_RIGHT) . ' = ' . $formValues[$formElementName];
									$addNewLine = FALSE;
								}
							} else
							if ($theValueLeft == $editableValue . '[]' &&
								$configArray['isArray'] &&
								!in_array($editableValue, $valueUsedInNewConfiguration)) {

								$formElementName = str_replace('-', '_', L8M_Library::getUsableUrlStringOnly($environment)) . '_' . strtolower(str_replace('.', '_', $editableValue));

								$countFormElment = 0;
								while (isset($formValues[$formElementName . '_' . $countFormElment])) {
									if (trim($formValues[$formElementName . '_' . $countFormElment])) {
										if ($configArray['type'] == 'text') {
											$formValues[$formElementName . '_' . $countFormElment] = '"' . $formValues[$formElementName . '_' . $countFormElment] . '"';
										}
										$newFileContentArray[] = str_pad($theValueLeft, $maxLabelLength, ' ', STR_PAD_RIGHT) . ' = ' . $formValues[$formElementName . '_' . $countFormElment];
									}
									$countFormElment++;
								}
								if (trim($formValues[$formElementName])) {
									if ($configArray['type'] == 'text') {
										$formValues[$formElementName] = '"' . $formValues[$formElementName] . '"';
									}
									$newFileContentArray[] = str_pad($theValueLeft, $maxLabelLength, ' ', STR_PAD_RIGHT) . ' = ' . $formValues[$formElementName];
								}

								$valueUsedInNewConfiguration[] = $editableValue;
								$addNewLine = FALSE;
							} else
							if (!array_key_exists($theValueLeft, $editableValues)) {
								if (substr($theValueLeft, -2) == '[]') {
									$possibleValueLeft = substr($theValueLeft, 0, strlen($theValueLeft) - 2);
									if (!array_key_exists($possibleValueLeft, $editableValues)) {
										$newFileContentArray[] = str_pad($theValueLeft, $maxLabelLength, ' ', STR_PAD_RIGHT) . ' = ' . trim($theValueRight);
										$addNewLine = FALSE;
									}
								} else {
									$newFileContentArray[] = str_pad($theValueLeft, $maxLabelLength, ' ', STR_PAD_RIGHT) . ' = ' . trim($theValueRight);
									$addNewLine = FALSE;
								}
							}
						}
					}
				} else {
					$newFileContentArray[] = trim($fileContentLine);
				}

				$countLine ++;
			}
			file_put_contents($applicationIniPath, implode(PHP_EOL, $newFileContentArray));
			$this->_redirect($this->_helper->url());
		}


	}
}