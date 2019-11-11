<?php

/**
 * Default_Model_Base_ModelMarkedForEditor
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $model_name_id
 * @property integer $referenced_id
 * @property boolean $active
 * @property integer $entity_id
 * @property string $identifier
 * @property Default_Model_ModelName $ModelName
 * @property Default_Model_Entity $Entity
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_ModelMarkedForEditor extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('model_marked_for_editor');
        $this->hasColumn('id', 'integer', 11, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '11',
             ));
        $this->hasColumn('model_name_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '11',
             ));
        $this->hasColumn('referenced_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '11',
             ));
        $this->hasColumn('active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('entity_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '11',
             ));
        $this->hasColumn('identifier', 'string', 120, array(
             'type' => 'string',
             'length' => '120',
             ));


        $this->index('entity_id_idx', array(
             'fields' => 
             array(
              0 => 'entity_id',
             ),
             ));
        $this->index('model_name_id_idx', array(
             'fields' => 
             array(
              0 => 'model_name_id',
             ),
             ));
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Default_Model_ModelName as ModelName', array(
             'local' => 'model_name_id',
             'foreign' => 'id'));

        $this->hasOne('Default_Model_Entity as Entity', array(
             'local' => 'entity_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}