<?php

/**
 * Default_Model_Base_SiteRights
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $short
 * @property string $name
 * @property clob $email_text_plain
 * @property clob $email_text_html
 * @property clob $website_text_html
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_SiteRights extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('site_rights');
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
        $this->hasColumn('email_text_plain', 'clob', 65535, array(
             'type' => 'clob',
             'length' => '65535',
             ));
        $this->hasColumn('email_text_html', 'clob', 65535, array(
             'type' => 'clob',
             'length' => '65535',
             ));
        $this->hasColumn('website_text_html', 'clob', 65535, array(
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
        $i18n0 = new Doctrine_Template_I18n(array(
             'tableName' => 'site_rights_translation',
             'fields' => 
             array(
              0 => 'email_text_plain',
              1 => 'email_text_html',
              2 => 'website_text_html',
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