<?php

/**
 * Default_Model_Base_Flyer
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $short
 * @property integer $media_image_id
 * @property integer $media_id
 * @property boolean $catalog
 * @property boolean $hidden
 * @property string $title
 * @property Default_Model_Media $Media
 * @property Default_Model_MediaImage $MediaImage
 * @property Doctrine_Collection $FlyerImages
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_Flyer extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('flyer');
        $this->hasColumn('id', 'integer', 11, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '11',
             ));
        $this->hasColumn('short', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('media_image_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '11',
             ));
        $this->hasColumn('media_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
             ));
        $this->hasColumn('catalog', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('hidden', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));


        $this->index('media_image_idx', array(
             'fields' => 
             array(
              0 => 'media_image_id',
             ),
             ));
        $this->index('media_id_idx', array(
             'fields' => 
             array(
              0 => 'media_id',
             ),
             ));
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Default_Model_Media as Media', array(
             'local' => 'media_id',
             'foreign' => 'id'));

        $this->hasOne('Default_Model_MediaImage as MediaImage', array(
             'local' => 'media_image_id',
             'foreign' => 'id'));

        $this->hasMany('Default_Model_FlyerImages as FlyerImages', array(
             'local' => 'id',
             'foreign' => 'flyer_id'));

        $i18n0 = new Doctrine_Template_I18n(array(
             'tableName' => 'flyer_translation',
             'fields' => 
             array(
              0 => 'title',
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