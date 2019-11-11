<?php

/**
 * Default_Model_Base_Survey
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @property integer $id
 * @property string $short
 * @property string $name
 * @property clob $survey_data
 * @property string $survey_language
 * @property integer $sent_mail_count
 * @property string $title
 * @property string $description
 * @property datetime $start_datetime
 * @property datetime $end_datetime
 * @property Doctrine_Collection $SurveyAnswers
 * @property Doctrine_Collection $SurveyM2nDepartment
 * @property Doctrine_Collection $SurveyM2nBrand
 * @property Doctrine_Collection $SurveyM2nContractType
 * @property Doctrine_Collection $SurveyM2nRegion
 *
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_Survey extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('survey');
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
        $this->hasColumn('survey_data', 'clob', null, array(
             'type' => 'clob',
             'notnull' => true,
             ));
        $this->hasColumn('survey_language', 'string', 2, array(
             'type' => 'string',
             'length' => '2',
             ));
        $this->hasColumn('sent_mail_count', 'integer', 10, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '10',
             ));
        $this->hasColumn('title', 'string', 45, array(
             'type' => 'string',
             'length' => '45',
             ));
        $this->hasColumn('description', 'string', 120, array(
             'type' => 'string',
             'length' => '120',
             ));
        $this->hasColumn('start_datetime', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('end_datetime', 'datetime', null, array(
             'type' => 'datetime',
             ));

        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Default_Model_SurveyAnswers as SurveyAnswers', array(
             'local' => 'id',
             'foreign' => 'survey_id'));

        $this->hasMany('Default_Model_SurveyM2nDepartment as SurveyM2nDepartment', array(
             'local' => 'id',
             'foreign' => 'survey_id'));

        $this->hasMany('Default_Model_SurveyM2nBrand as SurveyM2nBrand', array(
             'local' => 'id',
             'foreign' => 'survey_id'));

        $this->hasMany('Default_Model_SurveyM2nContractType as SurveyM2nContractType', array(
             'local' => 'id',
             'foreign' => 'survey_id'));

        $this->hasMany('Default_Model_SurveyM2nRegion as SurveyM2nRegion', array(
             'local' => 'id',
             'foreign' => 'survey_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}