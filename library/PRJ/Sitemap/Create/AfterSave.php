<?php

/**
 * L8M
 *
 *
 * @filesource library/PRJ/InfoPage/Create/AfterSave.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: AfterSave.php 169 2014-09-02 11:16:55Z nm $
 */


/**
 *
 *
 * PRJ_InfoPage_Create_AfterSave
 *
 *
 */
class PRJ_Sitemap_Create_AfterSave
{
	private $_goOn = FALSE;
	private $_exception = NULL;

	/**
	 * After Save
	 *
	 * @param integer $ID
	 * @param string $modelName
	 * @param array $formValues
	 */
	public function afterSave($ID, $modelName, $formValues)
	{

			PRJ_Sitemap::writeXML();
			$this->_goOn = TRUE;
	}

	/**
	 * Flags whether to go on or not.
	 *
	 * @return boolean
	 */
	public function goOn()
	{
		return $this->_goOn;
	}

	/**
	 * Returns internal error.
	 *
	 * @return Exception
	 */
	public function getException()
	{
		return $this->_exception;
	}
}