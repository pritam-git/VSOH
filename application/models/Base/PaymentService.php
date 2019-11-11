<?php

/**
 * Default_Model_Base_PaymentService
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $short
 * @property string $name
 * @property boolean $visible
 * @property integer $percent
 * @property decimal $fixed
 * @property boolean $cant_be_null
 * @property string $class_name
 * @property boolean $need_bank_account_infos
 * @property boolean $need_creditcard_infos
 * @property boolean $has_internal_review
 * @property boolean $has_payment_after_order
 * @property boolean $payment_after_delivery
 * @property integer $payment_days
 * @property integer $media_image_id
 * @property integer $position
 * @property string $title
 * @property clob $content
 * @property Default_Model_MediaImage $MediaImage
 * @property Doctrine_Collection $ProductOrder
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_PaymentService extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('payment_service');
        $this->hasColumn('id', 'integer', 11, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '11',
             ));
        $this->hasColumn('short', 'string', 80, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => '80',
             ));
        $this->hasColumn('name', 'string', 80, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '80',
             ));
        $this->hasColumn('visible', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('percent', 'integer', 2, array(
             'type' => 'integer',
             'length' => '2',
             ));
        $this->hasColumn('fixed', 'decimal', 10, array(
             'type' => 'decimal',
             'scale' => '2',
             'length' => '10',
             ));
        $this->hasColumn('cant_be_null', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('class_name', 'string', 120, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '120',
             ));
        $this->hasColumn('need_bank_account_infos', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('need_creditcard_infos', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('has_internal_review', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('has_payment_after_order', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('payment_after_delivery', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('payment_days', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
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
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('content', 'clob', 65535, array(
             'type' => 'clob',
             'length' => '65535',
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
        $this->hasOne('Default_Model_MediaImage as MediaImage', array(
             'local' => 'media_image_id',
             'foreign' => 'id'));

        $this->hasMany('Default_Model_ProductOrder as ProductOrder', array(
             'local' => 'id',
             'foreign' => 'payment_service_id'));

        $i18n0 = new Doctrine_Template_I18n(array(
             'tableName' => 'payment_service_translation',
             'fields' => 
             array(
              0 => 'title',
              1 => 'content',
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