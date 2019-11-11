<?php

/**
 * L8M 
 * 
 * 
 * @filesource /library/L8M/Doctrine/Import/PaymentType.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: PaymentType.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 * 
 * 
 * L8M_Doctrine_Import_PaymentType
 * 
 *
 */
class L8M_Doctrine_Import_PaymentType extends L8M_Doctrine_Import_Abstract 
{
	
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
		$this->setArray(array(
							  array('id'=>1,
								    'disabled'=>FALSE,
								    'name_de'=>'Lastschrift',
								    'name_en'=>'Direct Debit'),
							  array('id'=>2,
						   		    'disabled'=>FALSE,
								    'name_de'=>'Invoice',
								    'name_en'=>'Rechnung'),
							  array('id'=>3,
								    'name_en'=>'Saferpay',
								    'name_de'=>'Saferpay'),
							  array('id'=>4,
							        'disabled'=>FALSE,
								    'name_en'=>'SofortÜberweisung',
								    'name_de'=>'SofortÜberweisung')));
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
			$paymentType = L8M_Doctrine_Record::factory($this->getModelClassName());
			$paymentType->merge($data);
			$paymentType->Translation['en']->name = $data['name_en'];
			$paymentType->Translation['de']->name = $data['name_de']; 
			$this->_dataCollection->add($paymentType, $data['id']);
		} 
			
	}
	
}