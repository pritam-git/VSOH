<?php

/**
 * Default_Model_Base_SurveyM2nContractType
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $survey_id
 * @property integer $contract_type_id
 * @property Default_Model_Survey $Survey
 * @property Default_Model_ContractType $ContractType
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_SurveyM2nContractType extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('survey_m2n_contract_type');
        $this->hasColumn('id', 'integer', 11, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '11',
             ));
        $this->hasColumn('survey_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '11',
             ));
        $this->hasColumn('contract_type_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'notnull' => true,
             'length' => '11',
             ));


        $this->index('survey_id_idx', array(
             'fields' => 
             array(
              0 => 'survey_id',
             ),
             ));
        $this->index('contract_type_id_idx', array(
             'fields' => 
             array(
              0 => 'contract_type_id',
             ),
             ));
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Default_Model_Survey as Survey', array(
             'local' => 'survey_id',
             'foreign' => 'id'));

        $this->hasOne('Default_Model_ContractType as ContractType', array(
             'local' => 'contract_type_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}