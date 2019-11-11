<?php

/**
 * Default_Model_Base_TeamMembers
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $short
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property integer $media_image_id
 * @property integer $position
 * @property string $ushort
 * @property string $title
 * @property string $function
 * @property string $description
 * @property Default_Model_MediaImage $MediaImage
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_TeamMembers extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('team_members');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('short', 'string', 120, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => '120',
             ));
        $this->hasColumn('name', 'string', 120, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '120',
             ));
        $this->hasColumn('email', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('phone', 'string', 80, array(
             'type' => 'string',
             'length' => '80',
             ));
        $this->hasColumn('media_image_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
             ));
        $this->hasColumn('position', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
             ));
        $this->hasColumn('ushort', 'string', 120, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '120',
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('function', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));


        $this->index('media_image_id_idx', array(
             'fields' => 
             array(
              0 => 'media_image_id',
             ),
             ));
        $this->index('position_idx', array(
             'fields' => 
             array(
              0 => 'position',
             ),
             ));
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Default_Model_MediaImage as MediaImage', array(
             'local' => 'media_image_id',
             'foreign' => 'id'));

        $i18n0 = new Doctrine_Template_I18n(array(
             'tableName' => 'team_members_translation',
             'fields' => 
             array(
              0 => 'ushort',
              1 => 'title',
              2 => 'function',
              3 => 'description',
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