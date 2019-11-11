<?php

/**
 * Default_Model_Base_ShippingCost
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $short
 * @property string $name
 * @property decimal $cost
 * @property integer $factor
 * @property boolean $from_factor
 * @property boolean $till_factor
 * @property boolean $equal_factor
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_ShippingCost extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('shipping_cost');
        $this->hasColumn('id', 'integer', 11, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '11',
             ));
        $this->hasColumn('short', 'string', 45, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => '45',
             ));
        $this->hasColumn('name', 'string', 150, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '150',
             ));
        $this->hasColumn('cost', 'decimal', 10, array(
             'type' => 'decimal',
             'scale' => '2',
             'length' => '10',
             ));
        $this->hasColumn('factor', 'integer', 11, array(
             'type' => 'integer',
             'length' => '11',
             ));
        $this->hasColumn('from_factor', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('till_factor', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('equal_factor', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));

        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}