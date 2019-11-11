<?php

/**
 * Default_Model_Base_EmailTemplatePart
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $short
 * @property string $name
 * @property clob $content_plain
 * @property clob $content_html
 * @property Doctrine_Collection $EmailTemplateM2nEmailTemplatePart
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_EmailTemplatePart extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('email_template_part');
        $this->hasColumn('id', 'integer', 11, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '11',
             ));
        $this->hasColumn('short', 'string', 45, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => '45',
             ));
        $this->hasColumn('name', 'string', 45, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '45',
             ));
        $this->hasColumn('content_plain', 'clob', 65535, array(
             'type' => 'clob',
             'length' => '65535',
             ));
        $this->hasColumn('content_html', 'clob', 65535, array(
             'type' => 'clob',
             'length' => '65535',
             ));

        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Default_Model_EmailTemplateM2nEmailTemplatePart as EmailTemplateM2nEmailTemplatePart', array(
             'local' => 'id',
             'foreign' => 'email_template_part_id'));

        $i18n0 = new Doctrine_Template_I18n(array(
             'tableName' => 'email_template_part_translation',
             'fields' => 
             array(
              0 => 'content_plain',
              1 => 'content_html',
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