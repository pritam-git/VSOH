<?php

/**
 * Default_Model_Base_Refund
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $short
 * @property decimal $value
 * @property string $name
 * @property Doctrine_Collection $Product
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_Refund extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('refund');
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
        $this->hasColumn('value', 'decimal', 10, array(
             'type' => 'decimal',
             'scale' => '2',
             'unsigned' => true,
             'length' => '10',
             ));
        $this->hasColumn('name', 'string', 80, array(
             'type' => 'string',
             'length' => '80',
             ));

        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Default_Model_Product as Product', array(
             'local' => 'id',
             'foreign' => 'refund_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}