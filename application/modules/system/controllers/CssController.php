<?php

/**
 * L8M
 *
 *
 * @filesource /application/modules/system/controllers/CssController.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: CssController.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * System_CssController
 *
 *
 */
class System_CssController extends L8M_Controller_Action
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
		$this->_redirect($this->_helper->url('generate-iconized-list-css'));
	}

	/**
	 * GenerateIconizedListCss action.
	 *
	 * @return void
	 */
	public function generateIconizedListCssAction()
	{

		/**
		 * modules
		 */
		$modules = array(
			'default',
			'system',
		);


		/**
		 * list icons
		 */
		$listIcons = array();

		if (is_array($modules) &&
			count($modules)>0) {

			/**
			 * filter
			 */
			$filter = new Zend_Filter();
			$filter
				->addFilter(new Zend_Filter_Word_CamelCaseToDash())
				->addFilter(new Zend_Filter_Word_UnderscoreToDash())
			;


			/**
			 * iterate over modules
			 */
			foreach ($modules as $module) {

				/**
				 * iconPath
				 */
				$iconPath = PUBLIC_PATH
						  . DIRECTORY_SEPARATOR
						  . 'img'
						  . DIRECTORY_SEPARATOR
						  . $module
						  . DIRECTORY_SEPARATOR
						  . 'icon'
				;

				if (file_exists($iconPath) &&
					is_dir($iconPath)) {

					/**
					 * icons
					 */
					$icons = array();


					/**
					 * @todo scan recursively and create packages
					 */
					$directoryIterator = new DirectoryIterator($iconPath);
					foreach ($directoryIterator as $file) {

						/* @var $file DirectoryIterator */
						if ($file->isFile() &&
							preg_match('/^(.+)\.png$/', $file->getFilename(), $match)) {

							$iconClass = $filter->filter($match[1]);
							$iconPath = $file->getPathname();

							$icons[] = array(
								'class'=>$iconClass,
								'value'=>'<code>li.' . $this->view->escape($iconClass) . '</code>',
								'filePath'=>$this->_getRelativePath($iconPath, PUBLIC_PATH),
							);

						}
					}

					/**
					 * icons have been found in the designated icon path
					 */
					if (is_array($icons) &&
						count($icons) > 0) {

						/**
						 * css path
						 */
						$cssPath = PUBLIC_PATH
								 . DIRECTORY_SEPARATOR
								 . 'css'
								 . DIRECTORY_SEPARATOR
								 . $module
								 . DIRECTORY_SEPARATOR
								 . 'screen'
								 . DIRECTORY_SEPARATOR
								 . 'iconized'
								 . DIRECTORY_SEPARATOR
								 . 'sprites.css'
						;

						/**
						 * actual css
						 */
						$css = $this->view->partial('css-screen-iconized-sprites.phtml', array(
							'icons'=>$icons,
							'filePath'=>$this->_getRelativePath($cssPath, PUBLIC_PATH),
						));

						/**
						 * css file
						 */
						$cssFile = fopen($cssPath, 'w');
						fwrite($cssFile, $css);
						fclose($cssFile);

						$listIcons[$module] = $icons;
					}

				}

			}

		}

		$this->view->listIcons = $listIcons;

	}

	/**
	 *
	 *
	 * Helper Methods
	 *
	 *
	 */

	/**
	 * Returns relative path.
	 *
	 * @param string $filePath
	 * @param string $basePath
	 */
	protected function _getRelativePath($filePath = NULL, $basePath = BASE_PATH)
	{
		if (!$filePath ||
			!is_string($filePath) ||
			!$basePath ||
			!is_string($basePath)) {
			throw new L8M_Application_Builder_Exception('File and base paths need to be specified as strings.');
		}

		$filePath = str_replace(
			$basePath,
			'',
			$filePath
		);

		$filePath = preg_replace('@\\\@', '/', $filePath);

		return $filePath;
	}


}