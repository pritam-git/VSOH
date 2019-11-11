<?php

/**
 * Default_Model_Base_BackgroundImage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $short
 * @property string $name
 * @property integer $action_id
 * @property integer $media_image_id
 * @property string $background_attachment
 * @property string $background_size
 * @property Default_Model_Action $Action
 * @property Default_Model_MediaImage $MediaImage
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_BackgroundImage extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('background_image');
        $this->hasColumn('id', 'integer', 11, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '11',
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
        $this->hasColumn('action_id', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             ));
        $this->hasColumn('media_image_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '11',
             ));
        $this->hasColumn('background_attachment', 'string', 45, array(
             'type' => 'string',
             'length' => '45',
             ));
        $this->hasColumn('background_size', 'string', 45, array(
             'type' => 'string',
             'length' => '45',
             ));


        $this->index('media_image_id_idx', array(
             'fields' => 
             array(
              0 => 'media_image_id',
             ),
             ));
        $this->index('action_id_idx', array(
             'fields' => 
             array(
              0 => 'action_id',
             ),
             ));
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Default_Model_Action as Action', array(
             'local' => 'action_id',
             'foreign' => 'id'));

        $this->hasOne('Default_Model_MediaImage as MediaImage', array(
             'local' => 'media_image_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}