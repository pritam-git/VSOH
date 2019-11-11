<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/SocialNetwork.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: SocialNetwork.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_SocialNetwork
 *
 *
 */
class L8M_Doctrine_Import_SocialNetwork extends L8M_Doctrine_Import_Abstract
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
							  array(
							  		'id'=>1,
							        'disabled'=>FALSE,
							        'short'=>'twitter',
								    'name'=>'Twitter',
								    'www'=>'http://www.twitter.com',
							        'account_www'=>'http://www.twitter.com/l8m',
							  		),
							  ));
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
			$socialNetwork = L8M_Doctrine_Record::factory($this->getModelClassName());
			$socialNetwork->merge($data);
			$this->_dataCollection->add($socialNetwork, $data['id']);
		}
	}

}