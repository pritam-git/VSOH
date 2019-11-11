<?php

/**
 * Default_Model_Base_DatesQuestions
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $short
 * @property string $name
 * @property boolean $is_checkbox
 * @property boolean $is_required
 * @property integer $parent_question_id
 * @property string $title
 * @property Default_Model_DatesQuestions $ParentQuestion
 * @property Doctrine_Collection $DatesQuestions
 * @property Doctrine_Collection $DatesM2nQuestions
 * @property Doctrine_Collection $DatesParticipantsAnswers
 * @property Doctrine_Collection $RegionDatesM2nQuestions
 * @property Doctrine_Collection $RegionDatesParticipantsAnswers
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_DatesQuestions extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('dates_questions');
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
        $this->hasColumn('is_checkbox', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('is_required', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('parent_question_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
             ));
        $this->hasColumn('title', 'string', 120, array(
             'type' => 'string',
             'length' => '120',
             ));


        $this->index('question_id_idx', array(
             'fields' => 
             array(
              0 => 'parent_question_id',
             ),
             ));
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Default_Model_DatesQuestions as ParentQuestion', array(
             'local' => 'parent_question_id',
             'foreign' => 'id'));

        $this->hasMany('Default_Model_DatesQuestions as DatesQuestions', array(
             'local' => 'id',
             'foreign' => 'parent_question_id'));

        $this->hasMany('Default_Model_DatesM2nQuestions as DatesM2nQuestions', array(
             'local' => 'id',
             'foreign' => 'question_id'));

        $this->hasMany('Default_Model_DatesParticipantsAnswers as DatesParticipantsAnswers', array(
             'local' => 'id',
             'foreign' => 'question_id'));

        $this->hasMany('Default_Model_RegionDatesM2nQuestions as RegionDatesM2nQuestions', array(
             'local' => 'id',
             'foreign' => 'question_id'));

        $this->hasMany('Default_Model_RegionDatesParticipantsAnswers as RegionDatesParticipantsAnswers', array(
             'local' => 'id',
             'foreign' => 'question_id'));

        $i18n0 = new Doctrine_Template_I18n(array(
             'tableName' => 'dates_questions_translation',
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