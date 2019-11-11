<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/TranslatorController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TranslatorController.php 303 2015-03-31 11:47:30Z nm $
 */


/**
 *
 *
 * System_TranslatorController
 *
 *
 */
class System_TranslatorController extends L8M_Controller_Action
{
	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private $_modelListName = 'Default_Model_Translator';
	private $_modelListShort = 'trans';
	private $_modelListConfig = array();
	private $_modelListUntranslatedTitle = 'Translator';

	/**
	 * Store modelList.
	 *
	 * @var L8M_ModelForm_List
	 */
	private $_modelList = NULL;

	/**
	 *
	 *
	 * Initialization Function
	 *
	 *
	 */

	/**
	 * Initializes System_TranslatorController.
	 *
	 * @return void
	 */
	public function init ()
	{

		/**
		 * set headline
		 */
		$this->_helper->layout()->headline = $this->view->translate('Administration') . ' - ModelList';
		$this->_helper->layout()->headline .= ': ' . $this->view->translate($this->_modelListUntranslatedTitle);

		/**
		 * pass through parent to prevent errors
		 */
		parent::init();

		/**
		 * start model list
		 */
		$this->_modelList = new L8M_ModelForm_List($this->_modelListName, $this);
		$this->_modelList
			->setDefault('listTitle', $this->view->translate($this->_modelListUntranslatedTitle))
			->disableSubLinks()
			->disableButtonAdd()
//			->disableButtonDelete()
//			->addWhere('short', 'guest', FALSE, 'aa', 'Role', 'r')
//			->addWhereDqlString('aa.is_action_method = ? AND aa.resource LIKE ? ', array(TRUE, 'default.%'))
			->setButton('Update', array('action'=>'update', 'controller'=>'translator', 'module'=>'system'), 'update', FALSE)
//			->disableSaveWhere()
			->showListTranslateColumn('text', 'Text', 125, TRUE)
//			->useDbWhere(FALSE)
//			->showAjax();
//			->doNotRedirect()
//			->setDeleteOldList()
		;

		$this->_modelListConfig = array(
			'order'=>array(
			),
			'addIgnoredColumns'=>array(
			),
			'addIgnoredM2nRelations'=>array(
			),
			'ignoreColumnRelation'=>array(
			),
			'ignoreColumnInMultiRelation'=>array(
			),
			'relationM2nValuesDefinition'=>array(
			),
			'mediaDirectory'=>array(
			),
			'mediaRole'=>array(
			),
			'columnLabels'=>array(
			),
			'buttonLabel'=>'Save',
			'columnTypes'=>array(
				'text'=>'textarea',
			),
			'addStaticFormElements'=>array(
			),
			'M2NRelations'=>array(
			),
			'replaceColumnValuesInMultiRelation'=>array(
			),
			'relationColumnInMultiRelation'=>array(
			),
			'multiRelationCondition'=>array(
			),
			'tinyMCE'=>array(
			),
			'setFormLanguage'=>L8M_Locale::getDefaultSystem(),
			'action'=>$this->_request->getActionName(),
			//'debug'=>TRUE,
		);

		$this->view->modelFormListButtons = $this->_modelList->getButtons(NULL, $this->_modelListShort, $this->_modelListConfig);
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
		if ($this->_modelListName) {
			$this->_forward('list');
		}
	}

	/**
	 * List action.
	 *
	 * @return void
	 */
	public function listAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('List');

		/**
		 * start model list
		 */
		$this->_modelList->listCollection($this->_modelListShort);
	}

	/**
	 * Create action.
	 *
	 * @return void
	 */
	public function createAction ()
	{

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Add');

		/**
		 * start model list
		 */
		$this->_modelList->createModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforeSave'=>array(
			),
			'addStandardColumnValues'=>array(
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
			),
		)));
	}

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function deleteAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline =  $this->view->translate('Delete');

		/**
		 * start model list
		 */
		$this->_modelList->deleteModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforePreDelete'=>array(
			),
			'doBefore'=>array(
			),
		)));
	}

	/**
	 * Edit action.
	 *
	 * @return void
	 */
	public function editAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Edit');

		/**
		 * start model list
		 */
		$this->_modelList->editModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
			'doBeforeFormOutput'=>array(
			),
			'doBeforeSave'=>array(
			),
			'addGeneratedColumnValues'=>array(
			),
			'addGeneratedValues'=>array(
			),
			'doAfterSave'=>array(
			),
		)));
	}

	public function updateAction ()
	{
		set_time_limit(0);

		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Update');

		$xmlInfos = array();
		$availableUpdates = 0;
		$updateCounter = 0;
		$untranslatedCounter = 0;
		$untranslatedLangCodes = array();
		$untranslatedShorts = array();

		if (L8M_Environment::ENVIRONMENT_DEVELOPMENT == L8M_Environment::getInstance()->getEnvironment()) {
			$standardXmlFile = 'http://l8m.localheinz/api/update-translation';
		} else {
			$standardXmlFile = 'http://www.l8m.com/api/update-translation/short/' . L8M_Config::getOption('l8m.project.short') . '/api-key/' . L8M_Config::getOption('l8m.project.api_key');
		}

		$xmlString = trim(file_get_contents($standardXmlFile));
		if (substr($xmlString, 0, 3) == "\xEF\xBB\xBF") {
			$xmlString = substr($xmlString, 3);
		}

		/**
		 * load xml
		 */
		$xml = simplexml_load_string($xmlString);
		if (!$xml) {

			/**
			 * maybe somting todo
			 */

		} else {

			$xmlInfos['serverip'] = $xml->serverip;
			$xmlInfos['apikey'] = $xml->apikey;
			$xmlInfos['short'] = $xml->short;

			$availableUpdates = count($xml->translations);
			foreach ($xml->translations as $xmlTranslations) {
				$short = (string) $xmlTranslations->short;

				$translatorModel = Doctrine_Query::create()
					->from('Default_Model_Translator m')
					->addWhere('m.short = ? ', array($short))
					->execute()
					->getFirst()
				;

				if (!$translatorModel) {
					$translatorModel = new Default_Model_Translator();
					$translatorModel->short = $short;
				}
				$translatorModel->untranslated = FALSE;

				$didSomthing = FALSE;
				$isUntranslated = $translatorModel->untranslated;

				foreach ($xmlTranslations->translation as $xmlTranslation) {
					$langKey = (string) $xmlTranslation['lang'];
					$text = (string) $xmlTranslation->text;

					if (!isset($translatorModel->Translation[$langKey])) {
						$translatorModel->Translation[$langKey]->text = $text;
						$didSomthing = TRUE;
					} else
					if (!trim($translatorModel->Translation[$langKey])) {
						$translatorModel->Translation[$langKey]->text = $text;
						$didSomthing = TRUE;
					} else
					if (strpos($translatorModel->Translation[$langKey]->text, $langKey . '[') === 0) {
						$translatorModel->Translation[$langKey]->text = $text;
						$didSomthing = TRUE;
					}

					if (strpos($translatorModel->Translation[$langKey]->text, $langKey . '[') === 0) {
						$untranslatedCounter++;
						if (!in_array($langKey, $untranslatedLangCodes)) {
							$untranslatedLangCodes[] = $langKey;
						}
						if (!in_array($langKey, $untranslatedShorts)) {
							$untranslatedShorts[] = $short;
						}
						$translatorModel->untranslated = TRUE;
					}
				}
				$translatorModel->comment = 'By L8M Translation-Updater ' . date('Y-m-d H:i:s');
				if ($didSomthing == TRUE ||
					$isUntranslated != $translatorModel->untranslated) {

					$updateCounter++;
					$translatorModel->save();
				}
			}
		}

		$this->view->xmlInfos = $xmlInfos;
		$this->view->availableUpdates = $availableUpdates;
		$this->view->updateCounter = $updateCounter;
		$this->view->untranslatedCounter = $untranslatedCounter;
		$this->view->untranslatedLangCodes = $untranslatedLangCodes;
		$this->view->untranslatedShorts = $untranslatedShorts;
	}

	/**
	 * PDF action.
	 *
	 * @return void
	 */
	public function exportAction ()
	{
		/**
		 * set subheadline
		 */
		$this->_helper->layout()->subheadline = $this->view->translate('Export');

		/**
		 * this can go on for 5 minutes
		 */
		set_time_limit(300);

		/**
		 * start model list
		 */
		$this->_modelList->exportModel($this->_modelListShort, array_merge($this->_modelListConfig, array(
		)));
	}
}