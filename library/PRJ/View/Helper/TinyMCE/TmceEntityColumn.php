<?php

/**
 * L8M
 *
 *
 * @filesource /library/PRJ/View/Helper/TinyMCE/TmceEntityColumn.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: TmceEntityColumn.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * PRJ_View_Helper_TinyMCE_TmceEntityColumn
 *
 *
 */
class PRJ_View_Helper_TinyMCE_TmceEntityColumn extends L8M_View_Helper
{

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */
	protected $_allowedColumns = array(
		'salutation_id',
		'firstname',
		'lastname',
		'street',
		'street_number',
		'address_line_1',
		'address_line_2',
		'zip',
		'city',
		'country',
		'billing_firstname',
		'$billing_lastname',
		'billing_street',
		'billing_street_number',
		'billing_address_line_1',
		'billing_address_line_2',
		'billing_zip',
		'billing_city',
		'billing_country',
		'email',
		'mobile',
		'phone',
		'fax',
		'www',
	);

	/**
	 * Returns a TmceEntityColumn.
	 *
	 * @return string
	 */
	public function tmceEntityColumn($parameter = NULL)
	{
		$returnValue = NULL;

		if (Zend_Auth::getInstance()->hasIdentity()) {
			$entityModel = Zend_Auth::getInstance()->getIdentity();
			$columns = $entityModel->getTable()->getColumns();
			$parameter = trim($parameter);

			if (array_key_exists($parameter, $columns) &&
				in_array($parameter, $this->_allowedColumns)) {

				if ($parameter == 'salutation_id' &&
					$entityModel->salutation_id) {

					$returnValue = $entityModel->Salutation->name;
				} else
				if ($parameter == 'email' &&
					$entityModel->email) {

					$returnValue = '<a href="mailto:' . $entityModel->email . '" class="external">' . $entityModel->email . '</a>';
				} else
				if ($parameter == 'www' &&
					$entityModel->www) {

					$wwwText = $entityModel->www;
					if (stripos($wwwText, 'http://www.') === 0) {
						$wwwText = str_ireplace('http://', '', $wwwText);
						$wwwLink = $wwwText;
					} else
					if (stripos($wwwText, 'http://') === 0) {
						$wwwText = str_ireplace('http://', 'www.', $wwwText);
						$wwwLink = str_ireplace('http://', 'http://www.', $wwwText);
					} else
					if (stripos($wwwText, 'www.') === 0) {
						$wwwLink = str_ireplace('www.', 'http://www.', $wwwText);
					} else {
						$wwwText = 'www.' . $wwwText;
						$wwwLink = 'http://www.' . $wwwText;
					}
					$returnValue = '<a href="' . $wwwLink . '" class="external">' . $wwwText . '</a>';
				} else {
					$returnValue = $entityModel->$parameter;
				}
			}
		}
		return $returnValue;
	}

}