<?php

/**
 * Helper for rendering menus from navigation containers
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class L8M_View_Helper_IdMenu extends Zend_View_Helper_Navigation_HelperAbstract
{
	/**
	 * CSS class to use for the ul element
	 *
	 * @var string
	 */
	protected $_ulClass = 'navigation';

	/**
	 * ID to user for the ul element
	 */
	protected $_ulID = 'ulmenu';

	/**
	 * ID attribute to use for prepending before the 'li' element standard menu ID when rendering
	 */
	protected $_liID = NULL;

	/**
	 * Whether only active branch should be rendered
	 *
	 * @var bool
	 */
	protected $_onlyActiveBranch = false;

	/**
	 * Whether only active branch with tree should be rendered
	 *
	 * @var bool
	 */
	protected $_onlyActiveBranchTree = false;

	/**
	 * Whether parents should be rendered when only rendering active branch
	 *
	 * @var bool
	 */
	protected $_renderParents = true;

	/**
	 * Partial view script to use for rendering menu
	 *
	 * @var string|array
	 */
	protected $_partial = null;

	/**
	 * Create HtmlEntities in label
	 *
	 * @var string|array
	 */
	protected $_htmlEntities = TRUE;
	/**
	 * View helper entry point:
	 * Retrieves helper and optionally sets container to operate on
	 *
	 * @param  Zend_Navigation_Container $container  [optional] container to
	 *                                               operate on
	 * @return Zend_View_Helper_Navigation_Menu      fluent interface,
	 *                                               returns self
	 */
	public function idMenu(Zend_Navigation_Container $container = null)
	{
		if (null !== $container) {
			$this->setContainer($container);
		}

		return $this;
	}

	// Accessors:

	/**
	 * Sets CSS class to use for the first 'ul' element when rendering
	 *
	 * @param  string $ulClass                   CSS class to set
	 * @return Zend_View_Helper_Navigation_Menu  fluent interface, returns self
	 */
	public function setUlClass($ulClass)
	{
		if (is_string($ulClass)) {
			$this->_ulClass = $ulClass;
		}

		return $this;
	}

	/**
	 * Sets ID attribute to use for prepending before the 'li' element standard menu ID when rendering
	 *
	 * @param  string $liIdPrepend               CSS class to set
	 * @return Zend_View_Helper_Navigation_Menu  fluent interface, returns self
	 */
	public function setLiID($liIdPrepend)
	{
		if (is_string($liIdPrepend)) {
			$this->_liID = $liIdPrepend;
		}

		return $this;
	}

	/**
	 * Sets ID to use for the first 'ul' element when rendering
	 *
	 * @param  string $ulID                   CSS id to set
	 * @return Zend_View_Helper_Navigation_Menu  fluent interface, returns self
	 */
	public function setUlID($ulID)
	{
		if (is_string($ulID)) {
			$this->_ulID = $ulID;
		}

		return $this;
	}

	/**
	 * Returns CSS class to use for the first 'ul' element when rendering
	 *
	 * @return string  CSS class
	 */
	public function getUlClass()
	{
		return $this->_ulClass;
	}

	/**
	 * Sets a flag indicating whether only active branch should be rendered
	 *
	 * @param  bool $flag                        [optional] render only active
	 *                                           branch. Default is true.
	 * @return Zend_View_Helper_Navigation_Menu  fluent interface, returns self
	 */
	public function setOnlyActiveBranch($flag = true)
	{
		$this->_onlyActiveBranch = (bool) $flag;
		return $this;
	}

	/**
	 * Sets a flag indicating whether only active branch with tree should be rendered
	 *
	 * @param  bool $flag                        [optional] render only active
	 *                                           branch. Default is true.
	 * @return Zend_View_Helper_Navigation_Menu  fluent interface, returns self
	 */
	public function setOnlyActiveBranchWithTree($flag = true)
	{
		$this->_onlyActiveBranchTree = (bool) $flag;
		return $this;
	}

	/**
	 * Sets a flag indicating whether menu should activate php function htmlentities() or not
	 *
	 * @param  bool $flag                        [optional] render only active
	 *                                           branch. Default is true.
	 * @return Zend_View_Helper_Navigation_Menu  fluent interface, returns self
	 */
	public function setHtmlEntities($flag = true)
	{
		$this->_htmlEntities = (bool) $flag;
		return $this;
	}

	/**
	 * Returns a flag indicating whether only active branch should be rendered
	 *
	 * By default, this value is false, meaning the entire menu will be
	 * be rendered.
	 *
	 * @return bool  whether only active branch should be rendered
	 */
	public function getOnlyActiveBranch()
	{
		return $this->_onlyActiveBranch;
	}

	/**
	 * Enables/disables rendering of parents when only rendering active branch
	 *
	 * See {@link setOnlyActiveBranch()} for more information.
	 *
	 * @param  bool $flag                        [optional] render parents when
	 *                                           rendering active branch.
	 *                                           Default is true.
	 * @return Zend_View_Helper_Navigation_Menu  fluent interface, returns self
	 */
	public function setRenderParents($flag = true)
	{
		$this->_renderParents = (bool) $flag;
		return $this;
	}

	/**
	 * Returns flag indicating whether parents should be rendered when rendering
	 * only the active branch
	 *
	 * By default, this value is true.
	 *
	 * @return bool  whether parents should be rendered
	 */
	public function getRenderParents()
	{
		return $this->_renderParents;
	}

	/**
	 * Sets which partial view script to use for rendering menu
	 *
	 * @param  string|array $partial             partial view script or null. If
	 *                                           an array is given, it is
	 *                                           expected to contain two values;
	 *                                           the partial view script to use,
	 *                                           and the module where the script
	 *                                           can be found.
	 * @return Zend_View_Helper_Navigation_Menu  fluent interface, returns self
	 */
	public function setPartial($partial)
	{
		if (null === $partial || is_string($partial) || is_array($partial)) {
			$this->_partial = $partial;
		}

		return $this;
	}

	/**
	 * Returns partial view script to use for rendering menu
	 *
	 * @return string|array|null
	 */
	public function getPartial()
	{
		return $this->_partial;
	}

	// Public methods:

	/**
	 * Returns an HTML string containing an 'a' element for the given page if
	 * the page's href is not empty, and a 'span' element if it is empty
	 *
	 * Overrides {@link Zend_View_Helper_Navigation_Abstract::htmlify()}.
	 *
	 * @param  Zend_Navigation_Page $page  page to generate HTML for
	 * @return string                      HTML string for the given page
	 */
	public function htmlify(Zend_Navigation_Page $page)
	{
		/**
		 * get label and title for translating
		 */
		$label = $page->getLabel();
		$title = $page->getTitle();

		/**
		 * translate label and title?
		 */
		$doNotTranslate = $page->get('doNotTranslate');
		if ($doNotTranslate === NULL ||
			$doNotTranslate === FALSE) {

			if ($this->getUseTranslator() && $t = $this->getTranslator()) {
				if (is_string($label) && !empty($label)) {
					$label = $t->translate($label, $page->get('defaultLanguage'));
				}
				if (is_string($title) && !empty($title)) {
					$title = $t->translate($title, $page->get('defaultLanguage'));
				}
			}
		}

		/**
		 * get attribs for element
		 */
		$attribs = array(
			'id'     => $this->_liID . $page->getId(),
			'title'  => $title,
			'class'  => $page->getClass(),
			'style'  => $page->get('style')
		);

		/**
		 * does page have a href?
		 */
		$href = $page->getHref();
		if ($href) {
			$element = 'a';
			$attribs['href'] = $href;
			$attribs['target'] = $page->getTarget();
		} else {
			$element = 'span';
		}

		$uri = $page->get('uri');
		$target = $page->getTarget();

		if ($uri) {
			$attribs['href'] = $uri;
		}
		if ($target) {
			$attribs['target'] = $target;
		}

		/**
		 * check whether to create html entities or not
		 */
		if ($this->_htmlEntities) {
			$label = $this->view->escape($label);
		}

		/**
		 * return menu item
		 */
		return '<' . $element . $this->_htmlAttribs($attribs) . '>' . $label . '</' . $element . '>';
	}

	/**
	 * Normalizes given render options
	 *
	 * @param  array $options  [optional] options to normalize
	 * @return array           normalized options
	 */
	protected function _normalizeOptions(array $options = array())
	{
		if (isset($options['indent'])) {
			$options['indent'] = $this->_getWhitespace($options['indent']);
		} else {
			$options['indent'] = $this->getIndent();
		}

		if (isset($options['ulClass']) && $options['ulClass'] !== null) {
			$options['ulClass'] = (string) $options['ulClass'];
		} else {
			$options['ulClass'] = $this->getUlClass();
		}

		if (array_key_exists('minDepth', $options)) {
			if (null !== $options['minDepth']) {
				$options['minDepth'] = (int) $options['minDepth'];
			}
		} else {
			$options['minDepth'] = $this->getMinDepth();
		}

		if ($options['minDepth'] < 0 || $options['minDepth'] === null) {
			$options['minDepth'] = 0;
		}

		if (array_key_exists('maxDepth', $options)) {
			if (null !== $options['maxDepth']) {
				$options['maxDepth'] = (int) $options['maxDepth'];
			}
		} else {
			$options['maxDepth'] = $this->getMaxDepth();
		}

		if (!isset($options['onlyActiveBranch'])) {
			$options['onlyActiveBranch'] = $this->getOnlyActiveBranch();
		}

		if (!isset($options['renderParents'])) {
			$options['renderParents'] = $this->getRenderParents();
		}

		return $options;
	}

	// Render methods:

	/**
	 * Renders the deepest active menu within [$minDepth, $maxDeth], (called
	 * from {@link renderMenu()})
	 *
	 * @param  Zend_Navigation_Container $container  container to render
	 * @param  array                     $active     active page and depth
	 * @param  string                    $ulClass    CSS class for first UL
	 * @param  string                    $indent     initial indentation
	 * @param  int|null                  $minDepth   minimum depth
	 * @param  int|null                  $maxDepth   maximum depth
	 * @return string                                rendered menu
	 */
	protected function _renderDeepestMenu(Zend_Navigation_Container $container,
										  $ulClass,
										  $indent,
										  $minDepth,
										  $maxDepth)
	{
		if (!$active = $this->findActive($container, $minDepth - 1, $maxDepth)) {
			return '';
		}

		// special case if active page is one below minDepth
		if ($active['depth'] < $minDepth) {
			if (!$active['page']->hasPages()) {
				return '';
			}
		} else if (!$active['page']->hasPages()) {
			// found pages has no children; render siblings
			$active['page'] = $active['page']->getParent();
		} else if (is_int($maxDepth) && $active['depth'] +1 > $maxDepth) {
			// children are below max depth; render siblings
			$active['page'] = $active['page']->getParent();
		}

		$ulClass = $ulClass ? ' class="' . $ulClass . '"' : '';
		$html = $indent . '<ul' . $ulClass . '>' . self::EOL;

		foreach ($active['page'] as $subPage) {
			if (!$this->accept($subPage)) {
				continue;
			}

			$subPageCssClass = $subPage->getClass();

			if ($subPage->isActive(TRUE)) {
				if ($subPageCssClass) {
					$subPageCssClass = ' ' . $subPageCssClass;
				}
				$liClass = ' class="active ' . $subPageCssClass . '"';
			} else {
				if ($subPageCssClass) {
					$liClass = ' class="' . $subPageCssClass . '"';
				}
			}

			$html .= $indent . '	<li' . $liClass . '>' . self::EOL;
			$html .= $indent . '		' . $this->htmlify($subPage) . self::EOL;
			$html .= $indent . '	</li>' . self::EOL;
		}

		$html .= $indent . '</ul>';

		return $html;
	}

	/**
	 * Renders a normal menu (called from {@link renderMenu()})
	 *
	 * @param  Zend_Navigation_Container $container   container to render
	 * @param  string                    $ulClass     CSS class for first UL
	 * @param  string                    $indent      initial indentation
	 * @param  int|null                  $minDepth    minimum depth
	 * @param  int|null                  $maxDepth    maximum depth
	 * @param  bool                      $onlyActive  render only active branch?
	 * @return string
	 */
	protected function _renderMenu(Zend_Navigation_Container $container,
								   $ulClass,
								   $indent,
								   $minDepth,
								   $maxDepth,
								   $onlyActive)
	{
		/**
		 * cache html output
		 */
		$html = '';

		/**
		 * goOn
		 */
		$goOn = TRUE;

		/**
		 * save parentId
		 */
		$parentSavedShortId = '';

		/**
		 * remove other trees if only active branche tree is selected
		 */
		if ($this->_onlyActiveBranchTree &&
			$container->hasPages()) {

			/**
			 * search for parent of active container
			 */
			$parentPage = NULL;
			$rootPage = NULL;
			$currentPage = $container->findOneByActive(TRUE);

			/**
			 * do we have a current active page?
			 */
			if ($currentPage) {
				do {
					if ($rootPage != NULL) {
						$parentPage = $currentPage;
						$currentPage = $rootPage;
					}
					if ($currentPage instanceof Zend_Navigation_Page_Mvc ||
						$currentPage instanceof L8M_Navigation_Page_Mvc) {

						$rootPage = $currentPage->getParent();
					} else {
						$rootPage = NULL;
					}
				} while ($rootPage != NULL);
				$currentPage = $parentPage;

				/**
				 * build new container and set as container
				 */
				$tmpContainer = new Zend_Navigation();
				if ($currentPage) {
					$tmpContainer->addPage($currentPage);
				}
				$container = $tmpContainer;
			} else {
				$goOn = FALSE;
			}
		}

		/**
		 * we should exit, if not allowed to go on
		 */
		if ($goOn) {

			/**
			 * find deepest active
			 */
			$found = $this->findActive($container, $minDepth, $maxDepth);
			if ($found) {
				$foundPage = $found['page'];
				$foundDepth = $found['depth'];
			} else {
				$foundPage = NULL;
			}

			/**
			 * create iterator
			 */
			$iterator = new RecursiveIteratorIterator($container,
								RecursiveIteratorIterator::SELF_FIRST);
			if (is_int($maxDepth)) {
				$iterator->setMaxDepth($maxDepth);
			}

			/**
			 * iterate container
			 */
			$prevDepth = -1;
			foreach ($iterator as $page) {

				/**
				 * continue loop var - better overview
				 */
				$continueActionInLoop = TRUE;

				/**
				 * retrieve datas
				 */
				$depth = $iterator->getDepth();
				$isActive = $page->isActive(TRUE);

				/**
				 * check depth and actives
				 */
				if ($depth < $minDepth ||
					!$this->accept($page)) {

					/**
					 * page is below minDepth or not accepted by acl/visibilty
					 */
					$continueActionInLoop = FALSE;
				} else
				if ($onlyActive &&
					!$isActive) {

					/**
					 * page is not active itself, but might be in the active branch
					 */
					$accept = FALSE;
					if ($foundPage) {
						if ($foundPage->hasPage($page)) {

							/**
							 * accept if page is a direct child of the active page
							 */
							$accept = TRUE;
						} else
						if ($foundPage->getParent()->hasPage($page)) {

							/**
							 * page is a sibling of the active page...
							 */
							if (!$foundPage->hasPages() ||
								is_int($maxDepth) && $foundDepth + 1 > $maxDepth) {

								/**
								 * accept if active page has no children, or the
								 * children are too deep to be rendered
								 */
								$accept = TRUE;
							}
						}
					}

					if (!$accept) {
						$continueActionInLoop = FALSE;
					}
				}

				/**
				 * should we go on with loop action?
				 */
				if ($continueActionInLoop) {

					/**
					 * make sure indentation is correct
					 */
					$depth -= $minDepth;
					$myIndent = $indent . str_repeat('        ', $depth);

					/**
					 * check depth
					 */
					if ($depth > $prevDepth) {

						/**
						 * enter deeper menu detpth, so
						 * start new ul tag
						 */
						if ($ulClass &&
							$depth ==  0) {

							$ulClass = ' class="' . $ulClass . '"';
						} else {
							$ulClass = '';
						}
						$html .= $myIndent . '<ul id="' . $this->_ulID . $parentSavedShortId . '" ' . $ulClass . '>' . self::EOL;
					} else
					if ($depth < $prevDepth) {

						/**
						 * coming from deeper menu depth, so
						 * close li/ul tags until we're at current depth
						 */
						for ($i = $prevDepth; $i > $depth; $i--) {
							$ind = $indent . str_repeat('        ', $i);
							$html .= $ind . '    </li>' . self::EOL;
							$html .= $ind . '</ul>' . self::EOL;
						}

						/**
						 * close previous li tag
						 */
						$html .= $myIndent . '    </li>' . self::EOL;
					} else {

						/**
						 * close previous li tag
						 */
						$html .= $myIndent . '    </li>' . self::EOL;
					}

					/**
					 * render li tag and page
					 */
					/**
					 * save the parent Id
					 */
					$parentSavedShortId = '-' . $page->getId();

					/**
					 * retrieve css class
					 */
					$subPageCssClass = $page->getClass();

					$liClass = NULL;
					if ($isActive) {
						if ($subPageCssClass) {
							$subPageCssClass = ' ' . $subPageCssClass;
						}
						$liClass = ' class="active ' . $subPageCssClass . '"';
					} else {
						if ($subPageCssClass) {
							$liClass = ' class="' . $subPageCssClass . '"';
						}
					}

					$html .= $myIndent . '    <li id="' . $this->_liID . 'menu-' . $page->getId() . '"' . $liClass . '>' . self::EOL
						   . $myIndent . '        ' . $this->htmlify($page) . self::EOL;

					/**
					 * store as previous depth for next iteration
					 */
					$prevDepth = $depth;
				}
			}

			if ($html) {

				/**
				 * done iterating container; close open ul/li tags
				 */
				for ($i = $prevDepth+1; $i > 0; $i--) {
					$myIndent = $indent . str_repeat('        ', $i-1);
					$html .= $myIndent . '    </li>' . self::EOL
						   . $myIndent . '</ul>' . self::EOL;
				}
				$html = rtrim($html, self::EOL);
			}
		}

		return $html;
	}

	/**
	 * Renders helper
	 *
	 * Renders a HTML 'ul' for the given $container. If $container is not given,
	 * the container registered in the helper will be used.
	 *
	 * Available $options:
	 *
	 *
	 * @param  Zend_Navigation_Container $container  [optional] container to
	 *                                               create menu from. Default
	 *                                               is to use the container
	 *                                               retrieved from
	 *                                               {@link getContainer()}.
	 * @param  array                     $options    [optional] options for
	 *                                               controlling rendering
	 * @return string                                rendered menu
	 */
	public function renderMenu(Zend_Navigation_Container $container = null,
							   array $options = array())
	{
		if (null === $container) {
			$container = $this->getContainer();
		}

		$options = $this->_normalizeOptions($options);

		if ($options['onlyActiveBranch'] &&
			!$options['renderParents']) {

			$html = $this->_renderDeepestMenu($container,
											  $options['ulClass'],
											  $options['indent'],
											  $options['minDepth'],
											  $options['maxDepth']);
		} else {
			$html = $this->_renderMenu($container,
									   $options['ulClass'],
									   $options['indent'],
									   $options['minDepth'],
									   $options['maxDepth'],
									   $options['onlyActiveBranch']);
		}

		return $html;
	}

	/**
	 * Renders the inner-most sub menu for the active page in the $container
	 *
	 * This is a convenience method which is equivalent to the following call:
	 * <code>
	 * renderMenu($container, array(
	 *     'indent'           => $indent,
	 *     'ulClass'          => $ulClass,
	 *     'minDepth'         => null,
	 *     'maxDepth'         => null,
	 *     'onlyActiveBranch' => true,
	 *     'renderParents'    => false
	 * ));
	 * </code>
	 *
	 * @param  Zend_Navigation_Container $container  [optional] container to
	 *                                               render. Default is to render
	 *                                               the container registered in
	 *                                               the helper.
	 * @param  string                    $ulClass    [optional] CSS class to
	 *                                               use for UL element. Default
	 *                                               is to use the value from
	 *                                               {@link getUlClass()}.
	 * @param  string|int                $indent     [optional] indentation as
	 *                                               a string or number of
	 *                                               spaces. Default is to use
	 *                                               the value retrieved from
	 *                                               {@link getIndent()}.
	 * @return string                                rendered content
	 */
	public function renderSubMenu(Zend_Navigation_Container $container = null,
								  $ulClass = null,
								  $indent = null)
	{
		return $this->renderMenu($container, array(
			'indent'		   => $indent,
			'ulClass'		  => $ulClass,
			'minDepth'		 => null,
			'maxDepth'		 => null,
			'onlyActiveBranch' => true,
			'renderParents'	=> false
		));
	}

	/**
	 * Renders the given $container by invoking the partial view helper
	 *
	 * The container will simply be passed on as a model to the view script
	 * as-is, and will be available in the partial script as 'container', e.g.
	 * <code>echo 'Number of pages: ', count($this->container);</code>.
	 *
	 * @param  Zend_Navigation_Container $container  [optional] container to
	 *                                               pass to view script. Default
	 *                                               is to use the container
	 *                                               registered in the helper.
	 * @param  string|array             $partial     [optional] partial view
	 *                                               script to use. Default is to
	 *                                               use the partial registered
	 *                                               in the helper. If an array
	 *                                               is given, it is expected to
	 *                                               contain two values; the
	 *                                               partial view script to use,
	 *                                               and the module where the
	 *                                               script can be found.
	 * @return string                                helper output
	 */
	public function renderPartial(Zend_Navigation_Container $container = null,
								  $partial = null)
	{
		if (null === $container) {
			$container = $this->getContainer();
		}

		if (null === $partial) {
			$partial = $this->getPartial();
		}

		if (empty($partial)) {
			require_once 'Zend' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Exception.php';
			$e = new Zend_View_Exception(
				'Unable to render menu: No partial view script provided'
			);
			$e->setView($this->view);
			throw $e;
		}

		$model = array(
			'container' => $container
		);

		if (is_array($partial)) {
			if (count($partial) != 2) {
				require_once 'Zend' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Exception.php';
				$e = new Zend_View_Exception(
					'Unable to render menu: A view partial supplied as '
					.  'an array must contain two values: partial view '
					.  'script and module where script can be found'
				);
				$e->setView($this->view);
				throw $e;
			}

			return $this->view->partial($partial[0], $partial[1], $model);
		}

		return $this->view->partial($partial, null, $model);
	}

	/**
	 * Finds the deepest active page in the given container
	 *
	 * @param  Zend_Navigation_Container $container  container to search
	 * @param  int|null                  $minDepth   [optional] minimum depth
	 *                                               required for page to be
	 *                                               valid. Default is to use
	 *                                               {@link getMinDepth()}. A
	 *                                               null value means no minimum
	 *                                               depth required.
	 * @param  int|null                  $minDepth   [optional] maximum depth
	 *                                               a page can have to be
	 *                                               valid. Default is to use
	 *                                               {@link getMaxDepth()}. A
	 *                                               null value means no maximum
	 *                                               depth required.
	 * @return array                                 an associative array with
	 *                                               the values 'depth' and
	 *                                               'page', or an empty array
	 *                                               if not found
	 */
	public function findActive(Zend_Navigation_Container $container,
							   $minDepth = null,
							   $maxDepth = -1)
	{
		if (!is_int($minDepth)) {
			$minDepth = $this->getMinDepth();
		}
		if ((!is_int($maxDepth) || $maxDepth < 0) && null !== $maxDepth) {
			$maxDepth = $this->getMaxDepth();
		}

		$found  = null;
		$foundDepth = -1;
		$iterator = new RecursiveIteratorIterator($container,
				RecursiveIteratorIterator::CHILD_FIRST);

		if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'PRJ' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'Action' . DIRECTORY_SEPARATOR . 'Var.php') &&
			class_exists('PRJ_Controller_Action_Var')) {

			$paramVar = new PRJ_Controller_Action_Var();
			$reqParams = array();
			if ($paramVar instanceof L8M_Controller_Action_Var) {
				$front = Zend_Controller_Front::getInstance();
				$reqParams = $front->getRequest()->getParams();
			} else {
				$paramVar = NULL;
			}
		} else {
			$paramVar = NULL;
		}
		foreach ($iterator as $page) {
			$currDepth = $iterator->getDepth();
			if ($currDepth < $minDepth || !$this->accept($page)) {
				// page is not accepted
				continue;
			}

			if (($page instanceof Zend_Navigation_Page_Mvc || $page instanceof L8M_Navigation_Page_Mvc) &&
				$paramVar &&
				$paramVar->checkController($page->getAction(), $page->getController(), $page->getModule(), L8M_Locale::getLang()) &&
				$currDepth > $foundDepth) {

				$askForParam = $paramVar->getParam($page->getAction(), $page->getController(), $page->getModule(), L8M_Locale::getLang());
				$pageParams = $page->getParams();

				if (isset($reqParams['module']) &&
					$reqParams['module'] == $page->getModule() &&
					isset($reqParams['controller']) &&
					$reqParams['controller'] == $page->getController() &&
					isset($reqParams['action']) &&
					$reqParams['action'] == $page->getAction() &&
					isset($reqParams[$askForParam]) &&
					isset($pageParams[$reqParams[$askForParam]])) {

					$page->setActive();
					$found = $page;
					$foundDepth = $currDepth;
				}
			} else

			if ($page->isActive(false) && $currDepth > $foundDepth) {
				// found an active page at a deeper level than before
				$found = $page;
				$foundDepth = $currDepth;
			}
		}

		if (is_int($maxDepth) && $foundDepth > $maxDepth) {
			while ($foundDepth > $maxDepth) {
				if (--$foundDepth < $minDepth) {
					$found = null;
					break;
				}

				$found = $found->getParent();
				if (!$found instanceof Zend_Navigation_Page) {
					$found = null;
					break;
				}
			}
		}

		if ($found) {
			return array('page' => $found, 'depth' => $foundDepth);
		} else {
			return array();
		}
	}

	// Zend_View_Helper_Navigation_Helper:

	/**
	 * Renders menu
	 *
	 * Implements {@link Zend_View_Helper_Navigation_Helper::render()}.
	 *
	 * If a partial view is registered in the helper, the menu will be rendered
	 * using the given partial script. If no partial is registered, the menu
	 * will be rendered as an 'ul' element by the helper's internal method.
	 *
	 * @see renderPartial()
	 * @see renderMenu()
	 *
	 * @param  Zend_Navigation_Container $container  [optional] container to
	 *                                               render. Default is to
	 *                                               render the container
	 *                                               registered in the helper.
	 * @return string                                helper output
	 */
	public function render(Zend_Navigation_Container $container = null)
	{
		$partial = $this->getPartial();
		if ($partial) {
			return $this->renderPartial($container, $partial);
		} else {
			return $this->renderMenu($container);
		}
	}
}
