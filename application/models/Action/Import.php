<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/Action/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 515 2016-09-21 09:33:29Z nm $
 */

/**
 *
 *
 * Default_Model_Action_Import
 *
 *
 */
class Default_Model_Action_Import extends L8M_Doctrine_Import_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	protected $_standsForClass = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Initializes instance.
	 *
	 * @return void
	 */
	protected function _init()
	{
		/**
		 * pass through to prevent failures
		 */
		parent::_init();

		/**
		 * retrieve class name
		 */
		$this->_retriveStandsForClassName();
		$modelName = $this->_standsForClass;

		/**
		 * retrieve last model
		 */
		$model = Doctrine_Query::create()
			->from($modelName . ' m')
			->limit(1)
			->orderBy('m.id DESC')
			->execute()
			->getFirst()
		;
		if ($model) {
			$i = $model->id + 1;
		} else {
			$i = 1;
		}

		/**
		 * retrieve last controller
		 */
		$subModel = Doctrine_Query::create()
			->from('Default_Model_Controller m')
			->limit(1)
			->orderBy('m.id DESC')
			->execute()
			->getFirst()
		;
		if ($subModel) {
			$j = $subModel->id + 1;
		} else {
			$j = 1;
		}

		/**
		 * retrieve guest id
		 */
		$roleModel = Doctrine_Query::create()
			->from('Default_Model_Role r')
			->addWhere('r.short = ? ', array('guest'))
			->limit(1)
			->execute()
			->getFirst()
		;
		if ($roleModel) {
			$roleId = $roleModel->id;
		} else {
			$roleId = 1;
		}

		/**
		 * update datas
		 */
		$this->_updateContent();

		$w = array();

		$w[] = array(
			'id'=>$i++,
			'name'=>'index',
			'role_id'=>$roleId,
			'controller_id'=>$j,
			'resource'=>'default.imprint.index',
			'is_action_method'=>TRUE,
			'content_partial'=>NULL,
			'is_allowed'=>TRUE,
			'content_de'=>'
				<div class="sysviewhelper l8m-object" style="width: 16px; height: 16px;" data-parameters="imprint" data-ignoredimension="true" data-rel="PRJ_View_Helper_TinyMCE_TmceSiteRights">###:PRJ_View_Helper_TinyMCE_TmceSiteRights:###</div>
				',
			'headline_de'=>'Impressum',
			'subheadline_de'=>NULL,
			'description_de'=>NULL,
			'keywords_de'=>NULL,
			'title_de'=>'Impressum',
			'content_en'=>'
				<div class="sysviewhelper l8m-object" style="width: 16px; height: 16px;" data-parameters="imprint" data-ignoredimension="true" data-rel="PRJ_View_Helper_TinyMCE_TmceSiteRights">###:PRJ_View_Helper_TinyMCE_TmceSiteRights:###</div>
				',
			'headline_en'=>'Imprint',
			'subheadline_en'=>NULL,
			'description_en'=>NULL,
			'keywords_en'=>NULL,
			'title_en'=>'Imprint',
			'Controller'=>array(
				'id'=>$j,
				'name'=>'imprint',
				'module_id'=>1,
			),
		);
		$xfg['imprint'] = $j++;

		$w[] = array(
			'id'=>$i++,
			'name'=>'index',
			'role_id'=>$roleId,
			'controller_id'=>$j,
			'resource'=>'default.privacy-policy.index',
			'is_action_method'=>TRUE,
			'content_partial'=>NULL,
			'is_allowed'=>TRUE,
			'content_de'=>'
				<div class="sysviewhelper l8m-object" style="width: 16px; height: 16px;" data-parameters="privacy" data-ignoredimension="true" data-rel="PRJ_View_Helper_TinyMCE_TmceSiteRights">###:PRJ_View_Helper_TinyMCE_TmceSiteRights:###</div>
				',
			'headline_de'=>'Datenschutz',
			'subheadline_de'=>NULL,
			'description_de'=>NULL,
			'keywords_de'=>NULL,
			'title_de'=>'Datenschutz',
			'content_en'=>'
				<div class="sysviewhelper l8m-object" style="width: 16px; height: 16px;" data-parameters="privacy" data-ignoredimension="true" data-rel="PRJ_View_Helper_TinyMCE_TmceSiteRights">###:PRJ_View_Helper_TinyMCE_TmceSiteRights:###</div>
				',
			'headline_en'=>'Privacy',
			'subheadline_en'=>NULL,
			'description_en'=>NULL,
			'keywords_en'=>NULL,
			'title_en'=>'Privacy',
			'Controller'=>array(
				'id'=>$j,
				'name'=>'privacy-policy',
				'module_id'=>1,
			),
		);
		$xfg['privacy'] = $j++;

		$w[] = array(
			'id'=>$i++,
			'name'=>'index',
			'role_id'=>$roleId,
			'controller_id'=>$j,
			'resource'=>'default.about-us.index',
			'is_action_method'=>TRUE,
			'content_partial'=>NULL,
			'is_allowed'=>TRUE,
			'content_de'=>NULL,
			'headline_de'=>'Über Uns',
			'subheadline_de'=>NULL,
			'description_de'=>NULL,
			'keywords_de'=>NULL,
			'title_de'=>'Über Uns',
			'content_en'=>NULL,
			'headline_en'=>'About Us',
			'subheadline_en'=>NULL,
			'description_en'=>NULL,
			'keywords_en'=>NULL,
			'title_en'=>'About Us',
			'Controller'=>array(
				'id'=>$j,
				'name'=>'about-us',
				'module_id'=>1,
			),
		);
		$xfg['about-us'] = $j++;

		$w[] = array(
			'id'=>$i++,
			'name'=>'index',
			'role_id'=>$roleId,
			'controller_id'=>$j,
			'resource'=>'default.membership.index',
			'is_action_method'=>TRUE,
			'content_partial'=>NULL,
			'is_allowed'=>TRUE,
			'content_de'=>NULL,
			'headline_de'=>'Mitgliedschaft',
			'subheadline_de'=>NULL,
			'description_de'=>NULL,
			'keywords_de'=>NULL,
			'title_de'=>'Mitgliedschaft',
			'content_en'=>NULL,
			'headline_en'=>'Membership',
			'subheadline_en'=>NULL,
			'description_en'=>NULL,
			'keywords_en'=>NULL,
			'title_en'=>'Membership',
			'Controller'=>array(
				'id'=>$j,
				'name'=>'membership',
				'module_id'=>1,
			),
		);
		$xfg['membership'] = $j++;

		$w[] = array(
			'id'=>$i++,
			'name'=>'index',
			'role_id'=>$roleId,
			'controller_id'=>$j,
			'resource'=>'default.partner.index',
			'is_action_method'=>TRUE,
			'content_partial'=>NULL,
			'is_allowed'=>TRUE,
			'content_de'=>NULL,
			'headline_de'=>'Partner',
			'subheadline_de'=>NULL,
			'description_de'=>NULL,
			'keywords_de'=>NULL,
			'title_de'=>'Partner',
			'content_en'=>NULL,
			'headline_en'=>'Partner',
			'subheadline_en'=>NULL,
			'description_en'=>NULL,
			'keywords_en'=>NULL,
			'title_en'=>'Partner',
			'Controller'=>array(
				'id'=>$j,
				'name'=>'partner',
				'module_id'=>1,
			),
		);
		$xfg['partner'] = $j++;

		$w[] = array(
			'id'=>$i++,
			'name'=>'index',
			'role_id'=>$roleId,
			'controller_id'=>$j,
			'resource'=>'default.links.index',
			'is_action_method'=>TRUE,
			'content_partial'=>NULL,
			'is_allowed'=>TRUE,
			'content_de'=>NULL,
			'headline_de'=>'Links',
			'subheadline_de'=>NULL,
			'description_de'=>NULL,
			'keywords_de'=>NULL,
			'title_de'=>'Links',
			'content_en'=>NULL,
			'headline_en'=>'Links',
			'subheadline_en'=>NULL,
			'description_en'=>NULL,
			'keywords_en'=>NULL,
			'title_en'=>'Links',
			'Controller'=>array(
				'id'=>$j,
				'name'=>'links',
				'module_id'=>1,
			),
		);
		$xfg['links'] = $j++;

		$this->setArray($w);
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{
		/**
		 * retrieve class name
		 */
		$modelName = $this->_standsForClass;

		/**
		 * check whether translatable or not
		 */
		$model = new $modelName();
		$modelRelations = $model->getTable()->getRelations();
		if (array_key_exists('Translation', $modelRelations)) {
			$transCols = $model->Translation->getTable()->getColumns();
			$transLangs = L8M_Locale::getSupported(TRUE);
			$translateable = TRUE;
		} else {
			$translateable = FALSE;
		}

		/**
		 * add data to collection
		 */
		$this->_dataCollection = new Doctrine_Collection($modelName);
		foreach($this->_data as $data) {
			$model = new $modelName();
			$model->merge($data);

			/**
			 * add translatables
			 */
			if ($translateable) {
				foreach ($transCols as $transCol => $colDefinition) {
					if ($transCol != 'id' &&
						$transCol != 'lang' &&
						$transCol != 'created_at' &&
						$transCol != 'updated_at' &&
						$transCol != 'deleted_at') {

						foreach ($transLangs as $transLang) {
							if (array_key_exists($transCol . '_' . $transLang, $data)) {
								$model->Translation[$transLang]->$transCol = $data[$transCol . '_' . $transLang];
							}
						}
					}
				}
			}

			/**
			 * just add data
			 */
			$this->_dataCollection->add($model, $data['id']);
		}
	}

	/**
	 * Retrieve stands for class name.
	 *
	 * @return void
	 */
	protected function _retriveStandsForClassName()
	{
		$name = get_class($this);
		$this->_standsForClass = substr($name, 0, strlen($name) - strlen('_Import'));
	}

	/**
	 * Update content for actions.
	 *
	 * @return void
	 */
	protected function _updateContent()
	{
		$contents = array(
			'index'=>array(
				'index'=>array(
					'content_partial'=>NULL,
					'content_de'=>'
						<p>Diese Plattform ist ein Kommunikationsmedium für <b>Opel</b> Betriebe, die Mitglied unseres Verbandes sind.</p>
						<p>Der Zugang zum geschlossenen Mitgleiderbereich ist nur für legitimierte Anwedner unter Angabe von Benutzernamen und Passwort möglich.</p>
						<p>Gerne stehen wir Ihnen für Fragen und Anregungen jderzeit gern zur Verfügung.</p>
					',
					'headline_de'=>'Willkommen',
					'subheadline_de'=>NULL,
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'Willkommen',
					'content_en'=>'
						<p>Diese Plattform ist ein Kommunikationsmedium für <b>Opel</b> Betriebe, die Mitglied unseres Verbandes sind.</p>
						<p>Der Zugang zum geschlossenen Mitgleiderbereich ist nur für legitimierte Anwedner unter Angabe von Benutzernamen und Passwort möglich.</p>
						<p>Gerne stehen wir Ihnen für Fragen und Anregungen jderzeit gern zur Verfügung.</p>
					',
					'headline_en'=>'Welcome',
					'subheadline_en'=>NULL,
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'Welcome',
				),
			),
			'newsletter'=>array(
				'index'=>array(
					'content_partial'=>NULL,
					'content_de'=>NULL,
					'headline_de'=>'Newsletter',
					'subheadline_de'=>NULL,
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'Newsletter',
					'content_en'=>NULL,
					'headline_en'=>'Newsletter',
					'subheadline_en'=>NULL,
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'Newsletter',
				),
				'subscribe'=>array(
					'content_partial'=>'content_left_newsletter',
					'content_de'=>'
						<h3>Aktuell informiert -<br />
						Test Newsletter</h3>
						<p>Sie interessieren sich für neue Produkte und aktuelle Angebote? Dann ist unser kostenloser Newsletter genau das Richtige für Sie.</p>
						<p>Ihr Test Team</p>
						',
					'headline_de'=>'Newsletter',
					'subheadline_de'=>'Anmelden',
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'Newsletter',
					'content_en'=>'
						<h3>Stay tuned -<br />
						Test Newsletter</h3>
						<p>Interested in new products and special offers? Then our free newsletter is just the thing for you.</p>
						<p>Your Test Team</p>
						',
					'headline_en'=>'Newsletter',
					'subheadline_en'=>'Subscribe',
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'Newsletter',
				),
				'unsubscribe'=>array(
					'content_partial'=>'content_left_newsletter',
					'content_de'=>'
						<h3>Aktuell informiert -<br />
						TestShop Newsletter</h3>
						<p>Um sich vom Newsletter ab zu melden, bestätigen Sie dies bitte im nebenstehend Formular, indem Sie Ihre E-Mail an geben und folgen den Anweisungen.</p>
						<p>Ihr Test Team</p>
						',
					'headline_de'=>'Newsletter',
					'subheadline_de'=>'Abmelden',
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'Newsletter',
					'content_en'=>'
						<h3>Stay tuned -<br />
						TestShop Newsletter</h3>
						<p>You need to confirm your unsubscription from our newsletter by typing in your email in the form and following the instructions.</p>
						<p>Your Test Team</p>
						',
					'headline_en'=>'Newsletter',
					'subheadline_en'=>'Unsubscribe',
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'Newsletter',
				),
			),
			'news'=>array(
				'index'=>array(
					'content_partial'=>NULL,
					'content_de'=>NULL,
					'headline_de'=>'News',
					'subheadline_de'=>NULL,
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'News',
					'content_en'=>NULL,
					'headline_en'=>'News',
					'subheadline_en'=>NULL,
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'News',
				),
				'page'=>array(
					'content_partial'=>NULL,
					'content_de'=>NULL,
					'headline_de'=>'News',
					'subheadline_de'=>NULL,
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'News',
					'content_en'=>NULL,
					'headline_en'=>'News',
					'subheadline_en'=>NULL,
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'News',
				),
				'detail'=>array(
					'content_partial'=>NULL,
					'content_de'=>NULL,
					'headline_de'=>'News',
					'subheadline_de'=>NULL,
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'News',
					'content_en'=>NULL,
					'headline_en'=>'News',
					'subheadline_en'=>NULL,
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'News',
				),
			),
			'blog'=>array(
				'index'=>array(
					'content_partial'=>NULL,
					'content_de'=>NULL,
					'headline_de'=>'Blog',
					'subheadline_de'=>NULL,
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'Blog',
					'content_en'=>NULL,
					'headline_en'=>'Blog',
					'subheadline_en'=>NULL,
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'Blog',
				),
				'detail'=>array(
					'content_partial'=>NULL,
					'content_de'=>NULL,
					'headline_de'=>'Blog',
					'subheadline_de'=>'Detail',
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'Blog',
					'content_en'=>NULL,
					'headline_en'=>'Blog',
					'subheadline_en'=>'Detail',
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'Blog',
				),
			),
			'contact'=>array(
				'index'=>array(
					'content_partial'=>'content_left_contact',
					'content_de'=>'
						<p>Sie benötigen unsere Adresse? Oder eine Telefonnummer?<br />Hier haben wir alle Kontaktmöglichkeiten für Sie aufgelistet.</p>
						<h3>Anschrift</h3><p>Test-Company-Name<br />Inh. Erika Mustermann<br />Musterstraße 12<br />12345 Musterstadt</p>
						<h3>Hotline</h3><p>030 12345678</p>
						<h3>E-Mail</h3><p><a href="mailto:test@l8m.com">test@l8m.com</a></p>
						<h3>Fax</h3><p>030 12345679</p>
						',
					'headline_de'=>'Kontakt',
					'subheadline_de'=>'Sie möchten Kontakt zu uns aufnehmen?',
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'Kontakt',
					'content_en'=>'
						<p>Do you need our address? Or a phone number?<br />Here we have listed all the contact information for you.</p>
						<h3>Address</h3><p>Test-Company-Name<br />Inh. Erika Mustermann<br />Musterstraße 12<br />12345 Musterstadt</p>
						<h3>Hotline</h3><p>030 12345678</p>
						<h3>E-mail</h3><p><a href="mailto:test@l8m.com">test@l8m.com</a></p>
						<h3>Fax</h3><p>030 12345679</p>
						',
					'headline_en'=>'Contact',
					'subheadline_en'=>'You would like to contact us?',
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'Contact',
				),
			),
			'team'=>array(
				'index'=>array(
					'content_partial'=>NULL,
					'content_de'=>NULL,
					'headline_de'=>'Team',
					'subheadline_de'=>NULL,
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'Team',
					'content_en'=>NULL,
					'headline_en'=>'Team',
					'subheadline_en'=>NULL,
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'Team',
				),
				'detail'=>array(
					'content_partial'=>NULL,
					'content_de'=>NULL,
					'headline_de'=>'Team',
					'subheadline_de'=>'Detail',
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'Team',
					'content_en'=>NULL,
					'headline_en'=>'Team',
					'subheadline_en'=>'Detail',
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'Team',
				),
			),
			'login'=>array(
				'index'=>array(
					'content_partial'=>NULL,
					'content_de'=>NULL,
					'headline_de'=>'Login',
					'subheadline_de'=>NULL,
					'description_de'=>NULL,
					'keywords_de'=>NULL,
					'title_de'=>'Login',
					'content_en'=>NULL,
					'headline_en'=>'Login',
					'subheadline_en'=>NULL,
					'description_en'=>NULL,
					'keywords_en'=>NULL,
					'title_en'=>'Login',
				),
			),
		);

		/**
		 * retrieve class name
		 */
		$modelName = $this->_standsForClass;

		/**
		 * check whether translatable or not
		 */
		$model = new $modelName();
		$modelRelations = $model->getTable()->getRelations();
		if (array_key_exists('Translation', $modelRelations)) {
			$transCols = $model->Translation->getTable()->getColumns();
			$transLangs = L8M_Locale::getSupported(TRUE);
			$translateable = TRUE;
		} else {
			$translateable = FALSE;
		}

		foreach ($contents as $controllerName => $controllerContents) {
			foreach ($controllerContents as $actionName => $data) {
				$actionModel = Doctrine_Query::create()
					->from('Default_Model_Action a')
					->leftJoin('a.Controller c')
					->leftJoin('c.Module m')
					->addWhere('m.name = ?', array('default'))
					->addWhere('c.name = ?', array($controllerName))
					->addWhere('a.name = ?', array($actionName))
					->execute()
					->getFirst()
				;

				if ($actionModel) {
					$actionModel->merge($data);

					/**
					 * add translatables
					 */
					if ($translateable) {
						foreach ($transCols as $transCol => $colDefinition) {
							if ($transCol != 'id' &&
								$transCol != 'lang' &&
								$transCol != 'created_at' &&
								$transCol != 'updated_at' &&
								$transCol != 'deleted_at') {

								foreach ($transLangs as $transLang) {
									if (array_key_exists($transCol . '_' . $transLang, $data)) {
										$actionModel->Translation[$transLang]->$transCol = $data[$transCol . '_' . $transLang];
									}
								}
							}
						}
					}
					$actionModel->save();
				}
			}
		}
	}
}