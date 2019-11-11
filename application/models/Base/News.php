<?php

/**
 * Default_Model_Base_News
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $short
 * @property datetime $publish_datetime
 * @property integer $fr_media_id
 * @property integer $de_media_id
 * @property integer $fr_presentation_media_id
 * @property integer $de_presentation_media_id
 * @property boolean $published
 * @property string $ushort
 * @property string $uname
 * @property string $title
 * @property clob $description
 * @property clob $content
 * @property Default_Model_Media $FrMedia
 * @property Default_Model_Media $DeMedia
 * @property Default_Model_Media $FrPresentationMedia
 * @property Default_Model_Media $DePresentationMedia
 * @property Doctrine_Collection $NewsM2nDepartment
 * @property Doctrine_Collection $NewsM2nMediaImage
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_News extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('news');
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
        $this->hasColumn('publish_datetime', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('fr_media_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
             ));
        $this->hasColumn('de_media_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
             ));
        $this->hasColumn('fr_presentation_media_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
             ));
        $this->hasColumn('de_presentation_media_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
             ));
        $this->hasColumn('published', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('ushort', 'string', 120, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '120',
             ));
        $this->hasColumn('uname', 'string', 120, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '120',
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('description', 'clob', 65535, array(
             'type' => 'clob',
             'length' => '65535',
             ));
        $this->hasColumn('content', 'clob', 65535, array(
             'type' => 'clob',
             'length' => '65535',
             ));


        $this->index('fr_media_id_idx', array(
             'fields' => 
             array(
              0 => 'fr_media_id',
             ),
             ));
        $this->index('de_media_id_idx', array(
             'fields' => 
             array(
              0 => 'de_media_id',
             ),
             ));
        $this->index('fr_presentation_media_id_idx', array(
             'fields' => 
             array(
              0 => 'fr_presentation_media_id',
             ),
             ));
        $this->index('de_presentation_media_id_idx', array(
             'fields' => 
             array(
              0 => 'de_presentation_media_id',
             ),
             ));
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Default_Model_Media as FrMedia', array(
             'local' => 'fr_media_id',
             'foreign' => 'id'));

        $this->hasOne('Default_Model_Media as DeMedia', array(
             'local' => 'de_media_id',
             'foreign' => 'id'));

        $this->hasOne('Default_Model_Media as FrPresentationMedia', array(
             'local' => 'fr_presentation_media_id',
             'foreign' => 'id'));

        $this->hasOne('Default_Model_Media as DePresentationMedia', array(
             'local' => 'de_presentation_media_id',
             'foreign' => 'id'));

        $this->hasMany('Default_Model_NewsM2nDepartment as NewsM2nDepartment', array(
             'local' => 'id',
             'foreign' => 'news_id'));

        $this->hasMany('Default_Model_NewsM2nMediaImage as NewsM2nMediaImage', array(
             'local' => 'id',
             'foreign' => 'news_id'));

        $i18n0 = new Doctrine_Template_I18n(array(
             'tableName' => 'news_translation',
             'fields' => 
             array(
              0 => 'ushort',
              1 => 'uname',
              2 => 'title',
              3 => 'description',
              4 => 'content',
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