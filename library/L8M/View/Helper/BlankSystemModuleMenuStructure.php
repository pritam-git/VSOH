<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/BlankSystemModuleMenuStructure.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: BlankSystemModuleMenuStructure.php 370 2015-06-22 16:33:52Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_BlankSystemModuleMenuStructure
 *
 *
 */
class L8M_View_Helper_BlankSystemModuleMenuStructure extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
	private static $_generatedModuleMenuStructure = NULL;

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Create ModuleMenuStructure
	 *
	 * @return array
	 */
	public function blankSystemModuleMenuStructure()
	{
		$returnValue = array();

		if (self::$_generatedModuleMenuStructure === NULL) {
			if (Zend_Auth::getInstance()->hasIdentity()) {
				$entityModel = Zend_Auth::getInstance()->getIdentity();
				$roleID = $entityModel->Role->id;
				$roleShort = $entityModel->Role->short;
			} else {
				if (L8M_Doctrine_Database::databaseExists() &&
					L8M_Doctrine::isEnabled() &&
					L8M_Doctrine_Table::tableExists('role')) {

					$roleModel = Doctrine_Query::create()
						->from('Default_Model_Role m')
						->addWhere('m.short = ? ', array('guest'))
						->limit(1)
						->execute()
						->getFirst()
					;
					if ($roleModel) {
						$roleID = $roleModel->id;
						$roleShort = $roleModel->short;
					} else {
						$roleID = 1;
						$roleShort = 'guest';
					}
				} else {
					$roleID = 1;
					$roleShort = 'admin';
				}
			}

			$linkArray = array();
			$linkArray[] = array(
				'link'=>$this->view->url(array('module'=>'default'), NULL, TRUE),
				'module'=>'default',
				'css'=>'default',
				'name'=>L8M_Translate::string('Homepage'),
				'border-left'=>FALSE,
			);

			if (Zend_Auth::getInstance()->hasIdentity()) {
				$linkArray[] = array(
					'border'=>TRUE,
				);

				/**
				 * check other modules
				 */
				if (L8M_Doctrine::isEnabled() == TRUE &&
					class_exists('Default_Model_Base_Module', TRUE) &&
					L8M_Doctrine_Database::databaseExists() &&
					L8M_Doctrine_Table::tableExists('module')) {

					$systemModules = array(
						'default',
						'admin',
						'system',
						'system-model-list',
					);

					$wordFilter = new Zend_Filter_Word_DashToSeparator(' ');

					/**
					 * check for setup
					 */
					$walkTroughModules = TRUE;
					if (isset($this->view->layout()->systemSetupProcessConfirmed) &&
						$this->view->layout()->systemSetupProcessConfirmed) {

						if (isset($this->view->layout()->setupWithoutDatabase) &&
							$this->view->layout()->setupWithoutDatabase) {

							$walkTroughModules = FALSE;
						}
					}

					/**
					 * modules
					 */
					if ($walkTroughModules) {
						$modules = Zend_Controller_Front::getInstance()->getControllerDirectory();
						foreach($modules as $moduleShort => $moduleControllerPath) {
							if (!in_array($moduleShort, $systemModules)) {
								$actionModel = L8M_Acl_Resource::existsInDatabaseAndReturn($moduleShort, 'index', 'index');
								if ($actionModel) {
									if (L8M_Acl_Resource::checkAction($actionModel)) {
										$linkArray[] = array(
											'link'=>$this->view->url(array('module'=>$moduleShort), NULL, TRUE),
											'module'=>$moduleShort,
											'css'=>$moduleShort,
											'name'=>L8M_Translate::string(ucwords($wordFilter->filter($moduleShort))),
										);
									}
								}
							}
						}
					}
				}

				$linkArray[] = array(
					'border'=>TRUE,
				);

				if ($roleShort == 'supervisor' ||
					$roleShort == 'admin') {

					$linkArray[] = array(
						'link'=>$this->view->url(array('module'=>'admin'), NULL, TRUE),
						'module'=>'admin',
						'css'=>'admin',
						'name'=>L8M_Translate::string('Administration'),
					);
				}

				if ($roleShort == 'admin') {
					$linkArray[] = array(
						'link'=>$this->view->url(array('module'=>'system'), NULL, TRUE),
						'module'=>'system',
						'css'=>'system',
						'name'=>L8M_Translate::string('System'),
					);
				}

				$linkArray[] = array(
					'border'=>TRUE,
				);

				$linkArray[] = array(
					'link'=>$this->view->url(array('module'=>'default', 'controller'=>'logout'), NULL, TRUE),
					'module'=>'',
					'css'=>'logout',
					'name'=>'Logout',
				);
			}
			self::$_generatedModuleMenuStructure = $linkArray;
		}

		if (is_array(self::$_generatedModuleMenuStructure)) {
			$returnValue = self::$_generatedModuleMenuStructure;
		}

		return $returnValue;
	}
}