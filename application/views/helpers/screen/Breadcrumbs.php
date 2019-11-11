<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/views/helpers/Screen/Breadcrumbs.php
 * @author     Andreas Möller <am@l8m.com>
 * @version    $Id: Breadcrumbs.php 279 2015-09-09 10:20:47Z rq $
 */

/**
 *
 *
 * Default_View_Helper_Screen_Breadcrumbs
 *
 *
 */
class Default_View_Helper_Screen_Breadcrumbs extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns a breadcrumb.
	 *
	 * @return string
	 */
	public function breadcrumbs()
	{

		$actModule = L8M_Acl_CalledFor::module();
		$actController = L8M_Acl_CalledFor::controller();
		$actAction = L8M_Acl_CalledFor::action();
		$detailController = array('archive-commissions', 'commissions');
		$detailAction = array("detail");

		$breadcrumbs = new Mandala_Bootstrap_Breadcrumbs();

		$breadcrumbs->addElement(
			$this->view->translate('Startseite', 'de'),
			$this->view->url(
				array(
					'module'=>'default',
					'controller'=>'index',
					'action'=>'index',
				),
				NULL,
				TRUE
			)
		);

		if ($actModule == 'shop' &
			$actController != 'cart' &&
			$actController != 'search') {

			$breadcrumbs->addElement(
				$this->view->translate('Shop', 'de'),
				$this->view->url(
				array(
					'module'=>'shop',
					'controller'=>'index',
					'action'=>'index',
				),
				NULL,
				TRUE
				)
			);

			if ($actController == 'group' ||
				$actController == 'article') {

				$elementArray = array();

				if ($actController == 'article') {

					$productConfigurationModel = Default_Model_ProductConfiguration::getModelByUshort($this->view->productarticle);

					$elementArray[] = array(
						'title'=>$productConfigurationModel->name,
						'url'=>$this->view->url(array('module'=>'shop', 'controller'=>'article', 'action'=>'index', 'productarticle'=>$productConfigurationModel->ushort)),
					);

					$productGroupParam = $productConfigurationModel->ProductGroup->ushort;

				} else {
					$productGroupParam = $this->view->productgroupParam;
				}

				$productGroupModel = Default_Model_ProductGroup::getModelByUshort($productGroupParam);

				$elementArray[] = array(
					'title'=>$productGroupModel->title,
					'url'=>$this->view->url(array('module'=>'shop', 'controller'=>'group', 'action'=>'index', 'productgroup'=>($productGroupModel instanceof Default_Model_ProductGroup) ? $productGroupModel->ushort : $this->view->productgroupParam)),
				);

				while ($productGroupModel instanceof Default_Model_ProductGroup &&
						$productGroupModel->product_group_id != NULL) {

					$productGroupModel = Default_Model_ProductGroup::getModelByID($productGroupModel->product_group_id);

					$elementArray[] = array(
						'title'=>$productGroupModel->title,
						'url'=>$this->view->url(array('module'=>'shop', 'controller'=>'group', 'action'=>'index', 'productgroup'=>$productGroupModel->ushort), NULL, TRUE),
					);
				}

				$elementArray = array_reverse($elementArray);

				foreach ($elementArray as $element) {
					$breadcrumbs->addElement($element['title'], $element['url']);
				}

			}

		} else
		if ($actController == 'cart') {

			$breadcrumbs->addElement(
				$this->view->translate('Warenkorb', 'de'),
				$this->view->url(
					array(
						'module'=>'shop',
						'controller'=>'cart',
						'action'=>'index',
					),
					NULL,
					TRUE
				)
			);
		} else
		if ($actController == 'user' &&
			$actAction != 'register') {

			$menu = array(
				'accountoverview'=>$this->view->translate('Mein Profil', 'de'),
				'orders'=>$this->view->translate('Bestellvorgänge', 'de'),
				'saved-offers'=>$this->view->translate('Gespeicherte Angebote', 'de'),
				'favorites'=>$this->view->translate('Meine Favoriten', 'de'),
				'addressbook'=>$this->view->translate('Adressbuch', 'de'),
				'personal-data'=>$this->view->translate('Persönliche Daten', 'de'),
				'settings'=>$this->view->translate('Persönliche Einstellungen', 'de'),
				'order-media'=>$this->view->translate('Druckdaten hochldaen', 'de'),
				'registration-complete'=>$this->view->translate('Registrierung abgeschlossen', 'de'),
				'retrieve-password'=>$this->view->translate('Passwort vergessen', 'de'),
				'reset-password'=>$this->view->translate('Passwort zurücksetzen', 'de'),
				'enable-account'=>$this->view->translate('Benutzerkonto aktivieren', 'de'),
				'account-activated'=>$this->view->translate('Benutzerkonto aktiviert', 'de'),
			);

			$breadcrumbs->addElement(
				$menu['accountoverview'],
				$this->view->url(
				array(
					'module'=>'default',
					'controller'=>'user',
					'action'=>'accountoverview',
				),
				NULL,
				TRUE
				)
			);

			if ($actAction != 'accountoverview') {

				$breadcrumbs->addElement(
					$menu[$actAction],
					$this->view->url(
					array(
						'module'=>'default',
						'controller'=>'user',
						'action'=>$actAction,
					),
					NULL,
					TRUE
					)
				);

			}
		} else
		if ($actController == 'user' &&
			$actAction == 'register') {

			$breadcrumbs->addElement(
				$this->view->translate('Registrierung', 'de'),
				$this->view->url(
				array(
					'module'=>'default',
					'controller'=>'user',
					'action'=>'register',
				),
				NULL,
				TRUE
				)
			);

		} else
		if ($actController == 'search') {

			$breadcrumbs->addElement(
				$this->view->translate('Suche', 'de'),
					$this->view->url(
					array(
						'module'=>'shop',
						'controller'=>'search',
						'action'=>'index',
					),
					NULL,
					TRUE
				)
			);

			if (isset($this->view->searchkey) &&
				strlen($this->view->searchkey) > 0) {

				$breadcrumbs->addElement(
					vsprintf($this->view->translate('Suchergebnisse für "%s"', 'de'), array($this->view->searchkey)),
					$this->view->url(
						array(
							'module'=>'shop',
							'controller'=>'search',
							'action'=>'index',
						),
						NULL,
						TRUE
					)
				);

			}

		}  else
		if (in_array($actController, $detailController) && in_array($actAction, $detailAction)) {
			$actionModel = Default_Model_Action::getModelByResource(L8M_Acl_CalledFor::module() . '.' . L8M_Acl_CalledFor::controller() . '.index');
			if($actionModel) {
				$breadcrumbs->addElement(
					$actionModel->title,
					$this->view->url(
						array(
							'module' => L8M_Acl_CalledFor::module(),
							'controller' => L8M_Acl_CalledFor::controller(),
							'action' => 'index',
						),
						NULL,
						TRUE
					)
				);
			}
			$elementArray = array();
			$elementArray[] = array(
				'title'=>$this->view->headline,
				'url'=>$this->view->url(array('module'=>'default', 'controller'=>$actController, 'action'=>'protocol', 'short'=>$this->view->protocol)),
			);
			$elementArray[] = array(
				'title'=>$this->view->title,
				'url'=>$this->view->url(array('module'=>'default', 'controller'=>$actController, 'action'=>'detail', 'short'=>$this->view->short)),
			);
			foreach ($elementArray as $element) {
				$breadcrumbs->addElement($element['title'], $element['url']);
			}
		} else {

			if(L8M_Acl_CalledFor::action() == 'index') {
				$breadcrumbs->addElement(
					$this->view->layout()->title,
					$this->view->url(
						array(
							'module'=>L8M_Acl_CalledFor::module(),
							'controller'=>L8M_Acl_CalledFor::controller(),
							'action'=>L8M_Acl_CalledFor::action(),
						),
						NULL,
						TRUE
					)
				);
			} else {
				$actionModel = Default_Model_Action::getModelByResource(L8M_Acl_CalledFor::module() . '.' . L8M_Acl_CalledFor::controller() . '.index');
				if($actionModel) {
					$breadcrumbs->addElement(
						$actionModel->title,
						$this->view->url(
							array(
								'module' => L8M_Acl_CalledFor::module(),
								'controller' => L8M_Acl_CalledFor::controller(),
								'action' => 'index',
							),
							NULL,
							TRUE
						)
					);
				}
				$breadcrumbs->addElement(
					$this->view->layout()->title,
					$this->view->url(
						array(
							'module'=>L8M_Acl_CalledFor::module(),
							'controller'=>L8M_Acl_CalledFor::controller(),
							'action'=>L8M_Acl_CalledFor::action(),
						),
						NULL,
						TRUE
					)
				);
			}


		}

		return $breadcrumbs;

	}
}