<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Doctrine/Import/MediaType.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: MediaType.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Doctrine_Import_MediaType
 *
 *
 */
class L8M_Doctrine_Import_MediaType extends L8M_Doctrine_Import_Abstract
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
							        'short'=>'file',
								    'name_en'=>'File',
								    'name_de'=>'Datei'),
							  array('id'=>2,
								    'disabled'=>FALSE,
								    'short'=>'image',
								    'name_en'=>'Image',
								    'name_de'=>'Bild'),
							  array('id'=>3,
								    'disabled'=>FALSE,
								    'short'=>'imageInstance',
								    'name_en'=>'Image Instance',
								    'name_de'=>'Bild Instanz'),
							  array('id'=>4,
								    'disabled'=>FALSE,
								    'short'=>'shockwave',
								    'name_en'=>'Shockwave Object',
								    'name_de'=>'Shockwave Objekt'),
							  ));
	}

	/**
	 * Takes $this->_data and converts it into a Doctrine_Collection.
	 *
	 * @return void
	 */
	protected function _generateDataCollection()
	{
		$this->_dataCollection = new Doctrine_Collection($this->getModelClassName());
		foreach($this->_data as $data) {
			$mediaType = L8M_Doctrine_Record::factory($this->getModelClassName());
			$mediaType->merge($data);
			$mediaType->Translation['en']->name = $data['name_en'];
			$mediaType->Translation['de']->name = $data['name_de'];
			$this->_dataCollection->add($mediaType, $data['id']);
		}
	}

}