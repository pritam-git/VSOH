<?php

/**
 * Default_Model_Base_ShippingCountry
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $country_id
 * @property decimal $costs
 * @property boolean $consistent_costs
 * @property Default_Model_Country $Country
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_ShippingCountry extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('shipping_country');
        $this->hasColumn('id', 'integer', 11, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '11',
             ));
        $this->hasColumn('country_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '11',
             ));
        $this->hasColumn('costs', 'decimal', 7, array(
             'type' => 'decimal',
             'scale' => '2',
             'length' => '7',
             ));
        $this->hasColumn('consistent_costs', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));


        $this->index('country_id_idx', array(
             'fields' => 
             array(
              0 => 'country_id',
             ),
             ));
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Default_Model_Country as Country', array(
             'local' => 'country_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}