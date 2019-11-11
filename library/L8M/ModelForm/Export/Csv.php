<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/ModelForm/Export/Csv.php
 * @author	   Norbert Marks <nm@l8m.com>
 * @version    $Id: Csv.php 7 2014-03-11 16:18:40Z nm $
 */

class L8M_ModelForm_Export_Csv
{
	private $_data = array();

	public function __construct(array $options = null)
	{
		if(is_array($options)) {
			$this->setOptions($options);
		}
	}

	public function createCsv() {

		// check necessary params
		if (empty($this->_data)) {
			throw new Exception('missed params for creating the csv');
		}

		/**
		 * save and output the csv file
		 */
		if (isset($this->_data['filename']) &&
		$this->_data['filename']) {

			$filenamePD = $this->_data['filename'];
			if (substr($filenamePD, -4) != '.csv') {
				$filenamePD .= '.csv';
			}
		} else {
			$filenamePD = 'list.csv';
		}

		// Download CSV as file
		if (ob_get_contents()) {
// 			$this->Error('Some data has already been output, can\'t send CSV file');
		}

		/**
		 * create CSV data
		 */
		$content = NULL;
		$def = array();
		// helper to count length
		$content = NULL;

		// set labels
		foreach ($this->_data['definition'] as $definition) {
			$def[] = strtolower($definition['title']);
		}
		$content.= implode(';', str_replace('"', ' ', $def)) . '' . chr(13) . chr(10);

		// set values
		foreach ($this->_data['datas'] as $row) {
			$array = $row['cell'];
			$content.= '"' . implode('";"', str_replace('"', ' ', $array)) . '"' . chr(13) . chr(10);
		}

		header('Content-Description: File Transfer');
		if (headers_sent()) {
			$this->Error('Some data has already been output to browser, can\'t send CSV file');
		}
		header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		// force download dialog
		header('Content-Type: application/octet-stream');
		// use the Content-Disposition header to supply a recommended filename
		header('Content-Disposition: attachment; filename="'.basename($filenamePD).'";');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . mb_strlen($content, 'UTF-8'));

		$fh = fopen('php://output', 'w+');
		fwrite($fh, pack("CCC",0xef,0xbb,0xbf));
		fwrite($fh, $content);
		fclose($fh);

		return;
	}

	public function setData(array $data) {
		$this->_data = $data;
		return $this;
	}

	public function getData() {
		return $this->_data;
	}
}