<?php

/**
 * L8M
 *
 *
 * @filesource /application/models/SiteRights/Import.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Import.php 385 2015-07-08 10:16:17Z nm $
 */

/**
 *
 *
 * Default_Model_SiteRights_Import
 *
 *
 */
class Default_Model_SiteRights_Import extends L8M_Doctrine_Import_Abstract
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

		$w = array();

		$w[] = array(
			'id'=>$i++,
			'short'=>'imprint',
			'name'=>'imprint',
			'email_text_plain_de'=>NULL,
			'email_text_html_de'=>NULL,
			'website_text_html_de'=>'
				<p>
					Auf dieser Seite sollte ein Impressum hinterlegt sein. Probieren Sie doch den <a href="http://www.e-recht24.de/impressum-generator.html" class="external">Impressum Generator</a> von eRecht24 aus.
				</p>
			',
			'email_text_plain_en'=>NULL,
			'email_text_html_en'=>NULL,
			'website_text_html_en'=>'
				<p>
					On this page, an imprint should be stored. Why not try out the <a href="http://www.e-recht24.de/impressum-generator.html" class="external">imprint generator</a> of eRecht24.
				</p>
			',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'privacy',
			'name'=>'privacy',
			'email_text_plain_de'=>NULL,
			'email_text_html_de'=>NULL,
			'website_text_html_de'=>'
				<p>
					Auf dieser Seite sollte alles zum Datenschutz hinterlegt sein. Probieren Sie doch den <a href="http://www.e-recht24.de/impressum-generator.html" class="external">Impressum Generator</a> von eRecht24 aus.
				</p>
			',
			'email_text_plain_en'=>NULL,
			'email_text_html_en'=>NULL,
			'website_text_html_en'=>'
				<p>
					On this page everything should be stored on data protection. Why not try out the <a href="http://www.e-recht24.de/impressum-generator.html" class="external">imprint generator</a> of eRecht24.
				</p>
			',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'terms-and-condition',
			'name'=>'terms-and-condition',
			'email_text_plain_de'=>NULL,
			'email_text_html_de'=>NULL,
			'website_text_html_de'=>'
				<p>
					Auf dieser Seite sollte alles zu den AGB hinterlegt sein. Probieren Sie doch den <a href="http://www.e-recht24.de/impressum-generator.html" class="external">Impressum Generator</a> von eRecht24 aus.
				</p>
			',
			'email_text_plain_en'=>NULL,
			'email_text_html_en'=>NULL,
			'website_text_html_en'=>'
				<p>
					On this page everything should be stored on terms and conditions. Why not try out the <a href="http://www.e-recht24.de/impressum-generator.html" class="external">imprint generator</a> of eRecht24.
				</p>
			',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'cancellation-terms',
			'name'=>'cancellation-terms',
			'email_text_plain_de'=>NULL,
			'email_text_html_de'=>NULL,
			'website_text_html_de'=>'
				<p>
					Auf dieser Seite sollte alles zur Widerrufsbelehrung hinterlegt sein. Probieren Sie doch den <a href="http://www.e-recht24.de/impressum-generator.html" class="external">Impressum Generator</a> von eRecht24 aus.
				</p>
			',
			'email_text_plain_en'=>NULL,
			'email_text_html_en'=>NULL,
			'website_text_html_en'=>'
				<p>
					On this page everything should be stored on cancellation terms. Why not try out the <a href="http://www.e-recht24.de/impressum-generator.html" class="external">imprint generator</a> of eRecht24.
				</p>
			',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'provider-information',
			'name'=>'provider-information',
			'email_text_plain_de'=>NULL,
			'email_text_html_de'=>NULL,
			'website_text_html_de'=>'
				<p>
					Auf dieser Seite sollten alle Informationen zum Anbieter hinterlegt sein. Probieren Sie doch den <a href="http://www.e-recht24.de/impressum-generator.html" class="external">Impressum Generator</a> von eRecht24 aus.
				</p>
			',
			'email_text_plain_en'=>NULL,
			'email_text_html_en'=>NULL,
			'website_text_html_en'=>'
				<p>
					On this page everything should be stored on the provider information. Why not try out the <a href="http://www.e-recht24.de/impressum-generator.html" class="external">imprint generator</a> of eRecht24.
				</p>
			',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'shipping',
			'name'=>'shipping',
			'email_text_plain_de'=>NULL,
			'email_text_html_de'=>NULL,
			'website_text_html_de'=>'
				<p>
					Auf dieser Seite sollte alles zum Versand hinterlegt sein. Probieren Sie doch den <a href="http://www.e-recht24.de/impressum-generator.html" class="external">Impressum Generator</a> von eRecht24 aus.
				</p>
			',
			'email_text_plain_en'=>NULL,
			'email_text_html_en'=>NULL,
			'website_text_html_en'=>'
				<p>
					On this page everything should be stored on shipping. Why not try out the <a href="http://www.e-recht24.de/impressum-generator.html" class="external">imprint generator</a> of eRecht24.
				</p>
			',
		);

		$w[] = array(
			'id'=>$i++,
			'short'=>'terms-of-payment',
			'name'=>'terms-of-payment',
			'email_text_plain_de'=>NULL,
			'email_text_html_de'=>NULL,
			'website_text_html_de'=>'
				<p>
					Auf dieser Seite sollte alles zu den Zahlungsbedingungen hinterlegt sein. Probieren Sie doch den <a href="http://www.e-recht24.de/impressum-generator.html" class="external">Impressum Generator</a> von eRecht24 aus.
				</p>
			',
			'email_text_plain_en'=>NULL,
			'email_text_html_en'=>NULL,
			'website_text_html_en'=>'
				<p>
					On this page everything should be stored on terms of payment. Why not try out the <a href="http://www.e-recht24.de/impressum-generator.html" class="external">imprint generator</a> of eRecht24.
				</p>
			',
		);

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
}