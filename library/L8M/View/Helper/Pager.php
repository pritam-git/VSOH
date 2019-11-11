<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/View/Helper/Pager.php
 * @author	 Norbert Marks <nm@l8m.com>
 * @version	$Id: Pager.php 411 2015-09-14 10:44:02Z nm $
 */

/**
 *
 *
 * L8M_View_Helper_Pager
 *
 *
 */
class L8M_View_Helper_Pager extends Zend_View_Helper_Abstract
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Renders a paginator for doctrine Pager.
	 *
	 * @param Doctrine_Pager $pager
	 * @param integer $pagesBeforeAfter
	 * @param array $urlVars
	 * @param string $cssStyleClass
	 * @param string $pageKeyName
	 * @param string $first
	 * @param string $last
	 * @param string $previous
	 * @param string $next
	 * @param strin  $paginationName
	 *
	 * @return string
	 */
	public function pager($pager = NULL, $pagesBeforeAfter = 1, $urlVars = array(), $cssStyleClass = NULL, $pageKeyName = 'page', $first = '|<', $last = '>|', $previous = '<', $next = '>', $resetLinks = TRUE, $paginationName = NULL, $addPrevNextToHeader = TRUE)
	{
		$firstPage = $pager->getFirstPage();
		$lastPage = $pager->getLastPage();
		$previousPage = $pager->getPreviousPage();
		$nextPage = $pager->getNextPage();
		$currentPage = $pager->getPage();

		$start = $currentPage - $pagesBeforeAfter;
		$end = $currentPage + $pagesBeforeAfter;

		$showFirst = TRUE;
		$showLast = TRUE;

		if ($addPrevNextToHeader) {
			if ($currentPage != $previousPage) {
				$this->view->layout()->relPrev = $this->view->url($urlVars + array($pageKeyName=>$previousPage), NULL, $resetLinks);
			}
			if ($currentPage != $nextPage) {
				$this->view->layout()->relNext = $this->view->url($urlVars + array($pageKeyName=>$nextPage), NULL, $resetLinks);
			}
		}

		if ($start <= $firstPage) {
			$start = $firstPage;
			$showFirst = FALSE;
		}

		if ($end >= $lastPage) {
			$end = $lastPage;
			$showLast = FALSE;
		}

		if ($start == $end) {
			return NULL;
		}

		if ($cssStyleClass) {
			$cssStyleClass = ' ' . $cssStyleClass;
		}

		/**
		 * start html output
		 */
		ob_start()
?>
<div class="paginator<?php echo $cssStyleClass; ?>">
	<ul>
<?php
		if ($paginationName !== NULL) {
?>
		<li class="pagination-name"><?php echo $this->view->translate($paginationName); ?></li>
<?php
		}
		if ($showFirst) {

?>
		<li class="first"><a href="<?php echo $this->view->url($urlVars + array($pageKeyName=>$firstPage), NULL, $resetLinks); ?>"><?php echo $first; ?></a></li>
		<li class="previous"><a href="<?php echo $this->view->url($urlVars + array($pageKeyName=>$previousPage), NULL, $resetLinks); ?>"><?php echo $previous; ?></a></li>
<?php

		}

		for ($i = $start; $i <= $end; $i++) {

			if ($currentPage == $i) {
				$cssActive = ' class="active"';
			} else {
				$cssActive = '';
			}

?>
		<li><a href="<?php echo $this->view->url($urlVars + array($pageKeyName=>$i), NULL, $resetLinks); ?>"<?php echo $cssActive; ?>><?php echo $i; ?></a></li>
<?php

		}

		if ($showLast) {

?>
		<li><a class="next" href="<?php echo $this->view->url($urlVars + array($pageKeyName=>$nextPage), NULL, $resetLinks); ?>"><?php echo $next; ?></a></li>
		<li><a class="last" href="<?php echo $this->view->url($urlVars + array($pageKeyName=>$lastPage), NULL, $resetLinks); ?>"><?php echo $last; ?></a></li>
<?php

		}

?>
	</ul>
</div>
<?php
		$content = ob_get_clean();
		return $content;
	}

}