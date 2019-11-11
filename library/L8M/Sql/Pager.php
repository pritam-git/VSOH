<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Object/ObjectCollection.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Pager.php 268 2015-03-09 10:32:13Z nm $
 */

/**
 *
 *
 * L8M_Sql_ObjectCollection
 *
 *
 */
class L8M_Sql_Pager
{
	/**
	 * @var L8M_Sql $_query            L8M_Sql object related to the pager
	 */
	protected $_query;

	/**
	 * @var L8M_Sql $_countQuery        L8M_Sql object related to the counter of pager
	 */
	protected $_countQuery;

	/**
	 * @var integer $_countOfResults    Number of results found
	 */
	protected $_countOfResults;

	/**
	 * @var integer $_resultsInPage     Number of results found in page
	 */
	protected $_resultsInPage;

	/**
	 * @var integer $_maxPerPage        Maximum number of itens per page
	 */
	protected $_maxPerPage;

	/**
	 * @var integer $page               Current page
	 */
	protected $_page;

	/**
	 * @var integer $_lastPage          Last page (total of pages)
	 */
	protected $_lastPage;

	/**
	 * @var boolean $_executed          Pager was initialized (called "execute" at least once)
	 */
	protected $_executed;



	/**
	 * __construct
	 *
	 * @param L8M_Sql $query      Accepts a L8M_Sql object, which doesuses own sql.
	 * @param int $page           Current page
	 * @param int $maxPerPage     Maximum itens per page
	 * @return void
	 */
	public function __construct($query, $page, $maxPerPage = 0)
	{
		$this->_setExecuted(FALSE);

		$this->_setQuery($query);
		$this->_setPage($page);

		$this->setMaxPerPage($maxPerPage);
	}

	/**
	 * Create an L8M_Sql_Pager-Object
	 *
	 * @param L8M_Sql $query      Accepts a L8M_Sql object, which doesuses own sql.
	 * @param int $page           Current page
	 * @param int $maxPerPage     Maximum itens per page
	 * @return L8M_Sql_Pager
	 */
	public static function factory($query, $page, $maxPerPage = 0) {
		$returnValue = new L8M_Sql_Pager($query, $page, $maxPerPage);
		return $returnValue;
	}

	/**
	 * _adjustOffset
	 *
	 * Adjusts last page of L8M_Pager, offset and limit of L8M_Sql associated
	 *
	 * @return void
	 */
	protected function _adjustOffset()
	{
		// Define new total of pages
		$this->_lastPage = max(1, ceil($this->getNumResults() / $this->getMaxPerPage()));
	}

	/**
	 * getExecuted
	 *
	 * Returns the check if Pager was already executed at least once
	 *
	 * @return boolen        Pager was executed
	 */
	public function getExecuted()
	{
		return $this->_executed;
	}

	/**
	 * _setExecuted
	 *
	 * Defines if Pager was already executed
	 *
	 * @param $executed       Pager was executed
	 * @return void
	 */
	protected function _setExecuted($executed)
	{
		$this->_executed = $executed;
	}

	/**
	 * getNumResults
	 *
	 * Returns the number of results found
	 *
	 * @return int        the number of results found
	 */
	public function getNumResults()
	{
		$returnValue = FALSE;

		if ($this->getExecuted()) {
			return $this->_countOfResults;
		} else {
			throw new L8M_Exception(
				'Cannot retrieve the number of results of a not yet executed Pager query'
			);
		}

		return $returnValue;
	}

	/**
	 * getFirstPage
	 *
	 * Returns the first page
	 *
	 * @return int        first page
	 */
	public function getFirstPage()
	{
		return 1;
	}

	/**
	 * getLastPage
	 *
	 * Returns the last page (total of pages)
	 *
	 * @return int        last page (total of pages)
	 */
	public function getLastPage()
	{
		$returnValue = FALSE;

		if ($this->getExecuted()) {
			$returnValue = $this->_lastPage;
		} else {
			throw new L8M_Exception(
				'Cannot retrieve the last page number of a not yet executed Pager query'
			);
		}

		return $returnValue;
	}

	/**
	 * getLastPage
	 *
	 * Returns the current page
	 *
	 * @return int        current page
	 */
	public function getPage()
	{
		return $this->_page;
	}

	/**
	 * getNextPage
	 *
	 * Returns the next page
	 *
	 * @return int        next page
	 */
	public function getNextPage()
	{
		$returnValue = FALSE;

		if ($this->getExecuted()) {
			$returnValue = min($this->getPage() + 1, $this->getLastPage());
		} else {
			throw new L8M_Exception(
				'Cannot retrieve the last page number of a not yet executed Pager query'
			);
		}

		return $returnValue;
	}

	/**
	 * getPreviousPage
	 *
	 * Returns the previous page
	 *
	 * @return int        previous page
	 */
	public function getPreviousPage()
	{
		$returnValue = FALSE;

		if ($this->getExecuted()) {
			$returnValue = max($this->getPage() - 1, $this->getFirstPage());
		} else {
			throw new L8M_Exception(
				'Cannot retrieve the previous page number of a not yet executed Pager query'
			);
		}

		return $returnValue;
	}

	/**
	 * haveToPaginate
	 *
	 * Return TRUE if it's necessary to paginate or FALSE if not
	 *
	 * @return bool        TRUE if it is necessary to paginate, FALSE otherwise
	 */
	public function haveToPaginate()
	{
		$returnValue = FALSE;

		if ($this->getExecuted() &&
			$this->getNumResults() > $this->getMaxPerPage()) {

			$returnValue = TRUE;
		} else {
			throw new L8M_Exception(
				'Cannot know if it is necessary to paginate a not yet executed Pager query'
			);
		}

		return $returnValue;
	}

	/**
	 * setPage
	 *
	 * Defines the current page and automatically adjust offset and limits
	 *
	 * @param $page       current page
	 * @return void
	 */
	public function setPage($page)
	{
		$this->_setPage($page);
		$this->_setExecuted(FALSE);
	}

	/**
	 * _setPage
	 *
	 * Defines the current page
	 *
	 * @param $page       current page
	 * @return void
	 */
	private function _setPage($page)
	{
		$page = intval($page);
		$this->_page = ($page <= 0) ? 1 : $page;
	}

	/**
	 * getLastPage
	 *
	 * Returns the maximum number of itens per page
	 *
	 * @return int        maximum number of itens per page
	 */
	public function getMaxPerPage()
	{
		return $this->_maxPerPage;
	}

	/**
	 * setMaxPerPage
	 *
	 * Defines the maximum number of itens per page and automatically adjust offset and limits
	 *
	 * @param $max       maximum number of itens per page
	 * @return void
	 */
	public function setMaxPerPage($max)
	{
		if ($max > 0) {
			$this->_maxPerPage = $max;
		} else
		if ($max == 0) {
			$this->_maxPerPage = 25;
		} else {
			$this->_maxPerPage = abs($max);
		}

		$this->_setExecuted(FALSE);
	}

	/**
	 * getResultsInPage
	 *
	 * Returns the number of itens in current page
	 *
	 * @return int    Number of itens in current page
	 */
	public function getResultsInPage()
	{
		$page = $this->getPage();

		if ($page != $this->getLastPage()) {
			return $this->getMaxPerPage();
		}

		$offset = ($this->getPage() - 1) * $this->getMaxPerPage();

		return abs($this->getNumResults() - $offset);
	}

	/**
	 * getQuery
	 *
	 * Returns the L8M_Sql collector object related to the pager
	 *
	 * @return L8M_Sql    L8M_Sql object related to the pager
	 */
	public function getQuery()
	{
		return $this->_query;
	}

	/**
	 * _setQuery
	 *
	 * Defines the collector query to be used by pager
	 *
	 * @param L8M_Sql $query      Accepts a L8M_Sql object, which doesuses own sql.
	 * @return void
	 */
	protected function _setQuery($query)
	{
		if ($query instanceof L8M_Sql &&
			$query->usesOwnSql() == FALSE) {

			$this->_query = $query;
		} else {
			throw new L8M_Exception('L8M_Sql_Pager need to work with generated SQL due to speed and cache misconfiguration.');
		}
	}

	/**
	 * execute
	 *
	 * Executes the query, populates the collection and then return it
	 *
	 * @return L8M_Sql_ObjectCollection|array|boolean
	 */
	public function execute()
	{
		$returnValue = FALSE;
		$this->_countQuery = clone $this->_query;
		$this->_countOfResults = $this->_countQuery->getCount();

		if (!$this->_countQuery->hasExceptions()) {
			$returnValue = $this->_query
				->limit($this->_maxPerPage, (($this->_page - 1) * $this->_maxPerPage))
				->execute()
			;

			if (!$this->_query->hasExceptions()) {
				$this->_setExecuted(TRUE);
				$this->_resultsInPage = $returnValue->count();
				$this->_adjustOffset();
			}

		}

		return $returnValue;
	}

	/**
	 * Checks whether an error exists or not.
	 *
	 * @return boolean
	 */
	public static function hasExceptions() {
		$returnValue = FALSE;

		if ($this->_query->hasExceptions() ||
			$this->_countQuery->hasExceptions()) {

			$returnValue = TRUE;
		}

		return $returnValue;
	}
}