<?php

/**
 * Default_Model_Base_RegionDates
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @property integer $id
 * @property string $short
 * @property datetime $publish_datetime
 * @property date $closed_registration_date
 * @property string $place
 * @property datetime $start_datetime
 * @property datetime $end_datetime
 * @property clob $subject_of_negotiations
 * @property clob $comment
 * @property boolean $publish_fr
 * @property boolean $publish_de
 * @property integer $fr_media_id
 * @property integer $de_media_id
 * @property integer $fr_presentation_media_id
 * @property integer $de_presentation_media_id
 * @property integer $media_image_id
 * @property boolean $is_bookable
 * @property integer $region_id
 * @property string $ushort
 * @property string $uname
 * @property string $title
 * @property clob $description
 * @property clob $content
 * @property Default_Model_Media $FrMedia
 * @property Default_Model_Media $DeMedia
 * @property Default_Model_Media $FrPresentationMedia
 * @property Default_Model_Media $DePresentationMedia
 * @property Default_Model_Region $Region
 * @property Default_Model_Media $MediaImage
 * @property Doctrine_Collection $RegionDatesM2nDepartment
 * @property Doctrine_Collection $RegionDatesM2nQuestions
 * @property Doctrine_Collection $RegionDatesParticipantsAnswers
 * @property Doctrine_Collection $RegionDatesParticipants
 * @property Doctrine_Collection $RegionDatesM2nContractType
 * @property Doctrine_Collection $RegionDatesM2nBrand
 * @property Doctrine_Collection $RegionDatesM2nMediaImage
 *
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_RegionDates extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('region_dates');
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
        $this->hasColumn('closed_registration_date', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('place', 'string', 80, array(
             'type' => 'string',
             'length' => '80',
             ));
        $this->hasColumn('start_datetime', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('end_datetime', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('subject_of_negotiations', 'clob', 65535, array(
             'type' => 'clob',
             'length' => '65535',
             ));
        $this->hasColumn('comment', 'clob', 65535, array(
             'type' => 'clob',
             'length' => '65535',
             ));
        $this->hasColumn('publish_fr', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('publish_de', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
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
        $this->hasColumn('media_image_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
             ));
        $this->hasColumn('is_bookable', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('region_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '11',
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
        $this->index('region_id_idx', array(
             'fields' =>
             array(
              0 => 'region_id',
             ),
             ));
        $this->index('media_image_id_idx', array(
             'fields' =>
             array(
              0 => 'media_image_id',
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

        $this->hasOne('Default_Model_Region as Region', array(
             'local' => 'region_id',
             'foreign' => 'id'));

        $this->hasOne('Default_Model_Media as MediaImage', array(
             'local' => 'media_image_id',
             'foreign' => 'id'));

        $this->hasMany('Default_Model_RegionDatesM2nDepartment as RegionDatesM2nDepartment', array(
             'local' => 'id',
             'foreign' => 'region_date_id'));

        $this->hasMany('Default_Model_RegionDatesM2nQuestions as RegionDatesM2nQuestions', array(
             'local' => 'id',
             'foreign' => 'region_date_id'));

        $this->hasMany('Default_Model_RegionDatesParticipantsAnswers as RegionDatesParticipantsAnswers', array(
             'local' => 'id',
             'foreign' => 'region_date_id'));

        $this->hasMany('Default_Model_RegionDatesParticipants as RegionDatesParticipants', array(
             'local' => 'id',
             'foreign' => 'region_date_id'));

        $this->hasMany('Default_Model_RegionDatesM2nContractType as RegionDatesM2nContractType', array(
             'local' => 'id',
             'foreign' => 'region_dates_id'));

        $this->hasMany('Default_Model_RegionDatesM2nBrand as RegionDatesM2nBrand', array(
             'local' => 'id',
			 'foreign' => 'region_date_id'));

		$this->hasMany('Default_Model_RegionDatesM2nMediaImage as RegionDatesM2nMediaImage', array(
			'local' => 'id',
			'foreign' => 'region_date_id'));

        $i18n0 = new Doctrine_Template_I18n(array(
             'tableName' => 'region_dates_translation',
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