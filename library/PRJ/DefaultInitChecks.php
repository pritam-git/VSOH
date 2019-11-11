<?php

/**
 * PRJ
 *
 *
 * @filesource /library/PRJ/DefaultInitChecks.php
 * @author     Krishna Bhatt <krishna.patel@bcssarl.com>
 * @version    $Id: DefaultInitChecks.php 16 2019-04-04 14:43:56Z sl $
 */

/**
 *
 *
 * PRJ_DefaultInitChecks
 *
 *
 */

class PRJ_DefaultInitChecks
{
	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */

	/**
	 *
	 * Initial checks for the default module
	 * 1. Check if user language is changed or not.
	 *
	 */
	public static function check()
	{
		$loginUser = NULL;

		/*
		 * view from MVC
		 */
		$viewFromMVC = Zend_Layout::getMvcInstance()->getView();

		/*
		 * language selection check
		 */
		if (Zend_Auth::getInstance()->hasIdentity()) {
			//get login user model
			$loginUser = Zend_Auth::getInstance()->getIdentity();

			//check if user's spoken_language is match with the current selected language?
			if (strtolower($loginUser->spoken_language) !== strtolower(L8M_Locale::getLang())) {
				//save the changed language to the user's spoken language.
				$loginUser->spoken_language = L8M_Locale::getLang();
				$loginUser->save();
			}
		}

		/**
		 * brand-switch selection, list of brands
		 * check for brand session if enabled.
		 */
		$brandSession = new Zend_Session_Namespace('brand');
		if (L8M_Config::getOption('l8m.brandSwitch.enabled')) {
			$isShowSelection = TRUE;
//			unset($brandSession->id);
			if (isset($brandSession->id)) {
				if (Zend_Auth::getInstance()->hasIdentity()) {
					//check if user is member of the selected brand?
					$entityBrands = $loginUser->EntityM2nBrand;
					foreach ($entityBrands as $brandValue) {
						if ($brandValue->brand_id == $brandSession->id) {
							$isShowSelection = FALSE;
							break;
						}
					}
				} else
					$isShowSelection = FALSE;
			}

			if ($isShowSelection) {
				//unset the brand session if found
				if (isset($brandSession->id)) {
					unset($brandSession->id);
				}

				//get brand collection
				$brandCollection = Default_Model_Brand::createQuery();
				if (Zend_Auth::getInstance()->hasIdentity()) {
					$brandCollection
						->innerJoin('m.EntityM2nBrand eb')
						->addWhere('eb.entity_id = ?', array($loginUser->id));
				}

				if ($brandCollection->count() <= 0)
					$isShowSelection = FALSE;
				else
					$viewFromMVC->brandCollection = $brandCollection->execute();

			}

			$viewFromMVC->isShowSelection = $isShowSelection;
		} else {
			//unset the brand session if found
			if (isset($brandSession->id)) {
				unset($brandSession->id);
			}
		}
	}
}