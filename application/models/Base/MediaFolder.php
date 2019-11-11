<?php

/**
 * Default_Model_Base_MediaFolder
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $media_folder_id
 * @property Default_Model_MediaFolder $MediaFolder
 * @property Doctrine_Collection $Media
 * @property Doctrine_Collection $RememberMediaFolder
 * 
 * @package    L8M
 * @subpackage Models (Default Module)
 * @author     Norbert Marks <nm@l8m.com>
 * @version    SVN: $Id: Builder.php 7 2014-03-11 16:18:40Z nm $
 */
abstract class Default_Model_Base_MediaFolder extends Default_Model_Base_Abstract
{
    public function setTableDefinition()
    {
        $this->setTableName('media_folder');
        $this->hasColumn('id', 'integer', 11, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '11',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('media_folder_id', 'integer', 11, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '11',
             ));


        $this->index('media_folder_id_idx', array(
             'fields' => 
             array(
              0 => 'media_folder_id',
             ),
             ));
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Default_Model_MediaFolder as MediaFolder', array(
             'local' => 'media_folder_id',
             'foreign' => 'id'));

        $this->hasMany('Default_Model_Media as Media', array(
             'local' => 'id',
             'foreign' => 'media_folder_id'));

        $this->hasMany('Default_Model_RememberMediaFolder as RememberMediaFolder', array(
             'local' => 'id',
             'foreign' => 'media_folder_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}