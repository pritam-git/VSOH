<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/Bank.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Bank.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_Bank
 *
 *
 */
class L8M_Doctrine_Import_Bank extends L8M_Doctrine_Import_Abstract
{

	/**
	 *
	 *
	 * Class Constants
	 *
	 *
	 */
	CONST URL_BANK_DOWNLOAD = 'http://www.bundesbank.de/download/zahlungsverkehr/bankleitzahlen/';

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
		parent::_init();
		/**
		 * file
		 *
		 * @todo retrieve from server of the Bundesbank
		 *       http://www.bundesbank.de/download/zahlungsverkehr/bankleitzahlen/20091206/blz_20090907.txt
		 */
		$fileDirectory = APPLICATION_PATH . '..' . DIRECTORY_SEPARATOR . 'externals' .
											  	   DIRECTORY_SEPARATOR . 'bundesbank' .
											  	   DIRECTORY_SEPARATOR . 'txt' .
											  	   DIRECTORY_SEPARATOR;
		$fileName = 'blz_20091207.txt';

		$this->setFile($fileDirectory . $fileName);
	}

	/**
	 * Converts a single file row into a data array.
	 *
	 * @param  string $row
	 * @return array
	 */
	protected function _convertFileRowIntoData($row = NULL)
	{
		if (is_string($row)) {
			$data = array();
			$data['code'] = trim(substr($row, 0, 8));
			$data['is_leading'] = substr($row, 8, 1)=='1' ? TRUE : FALSE;
			$data['name'] = utf8_encode(trim(substr($row, 9, 58)));
			$data['zip'] = trim(substr($row, 67, 5));
			$data['city'] = utf8_encode(trim(substr($row, 72, 35)));
			$data['short'] = utf8_encode(trim(substr($row, 107, 27)));
			$data['pan'] = trim(substr($row, 134, 5));
			$data['bic'] = trim(substr($row, 139, 11));
			$data['check_digit_calc_method'] = trim(substr($row, 150, 2));
			$data['record_number'] = trim(substr($row, 152, 6));
			$data['change_flag'] = trim(substr($row, 158, 1));
			$data['will_be_deleted'] = trim(substr($row, 159, 1));
			$data['successor_code'] = trim(substr($row, 160, 8));
			return $data;
		}
		return NULL;
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{
		$this->_dataCollection = new Doctrine_Collection($this->getModelClassName());
		foreach($this->_data as $data) {
			$bank = L8M_Doctrine_Record::factory($this->getModelClassName());
			$bank->merge($data);
			$this->_dataCollection->add($bank);

		}
	}

}