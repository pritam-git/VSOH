<?php

/**
 * Default_Model_Base_Salutation
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property boolean $disabled
 * @property boolean $is_male
 * @property string $name
 * @property Doctrine_Collection $Entity
 * @property Doctrine_Collection $NewsletterSubscriber
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_Salutation extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('salutation');
        $this->hasColumn('id', 'integer', 11, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '11',
             ));
        $this->hasColumn('disabled', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('is_male', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('name', 'string', 120, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '120',
             ));

        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Default_Model_Entity as Entity', array(
             'local' => 'id',
             'foreign' => 'salutation_id'));

        $this->hasMany('Default_Model_NewsletterSubscriber as NewsletterSubscriber', array(
             'local' => 'id',
             'foreign' => 'salutation_id'));

        $i18n0 = new Doctrine_Template_I18n(array(
             'tableName' => 'salutation_translation',
             'fields' => 
             array(
              0 => 'name',
             ),
             ));
        $timestampable1 = new Doctrine_Template_Timestampable();
        $i18n0->addChild($timestampable1);
        $softdelete1 = new Doctrine_Template_SoftDelete();
        $i18n0->addChild($softdelete1);
        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($i18n0);
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}