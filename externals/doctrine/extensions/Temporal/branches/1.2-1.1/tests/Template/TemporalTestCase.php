<?php
/**
 * Doctrine_Template_Temporal_TestCase
 *
 * @package     Doctrine
 * @author      Luke Scott <luscott@ucg.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.phpdoctrine.org
 * @since       1.2
 * @version     $Revision$
 */
class Doctrine_Template_Temporal_TestCase extends Doctrine_UnitTestCase
{
    private $_dates = array();

    public function prepareTables() {
        $this->tables[] = 'TemporalTestParent';
        $this->tables[] = 'TemporalTestParentSegment';
        $this->tables[] = 'TemporalTestChild';

        parent::prepareTables();
    }

    private function failExpectedException($exception_class) {
        $this->fail(
            "Did not receive an expected $exception_class, while temporal enforcement was set to: "
            . var_export(Doctrine_Template_Temporal::isTemporalEnforcementSet(), true)
        );
    }

    public function prepareData() {
        $this->setDateVariables();
        $this->reloadAll();
    }

    private function getParentRecord($name, $date = null) {
        $q = Doctrine_Temporal_Query::create($date)
            ->from      ('TemporalTestParent p')
            ->leftJoin  ('p.Segments s')
            ->where     ('p.name = ?', $name);
        return $q->fetchOne();
    }

    private function getChildRecord($name, $date = null) {
        $q = Doctrine_Temporal_Query::create($date)
            ->from      ('TemporalTestChild')
            ->where     ('name = ?', $name);
        return $q->fetchOne();
    }

    public function testFindRecordsByDateQuery() {
        $record = $this->getParentRecord('currentParent');
        $this->assertNotEqual(false, $record);
        $record = $this->getParentRecord('futureParent', $this->_dates[1]);
        $this->assertNotEqual(false, $record);
        $record = $this->getParentRecord('pastParent', $this->_dates[-2]);
        $this->assertNotEqual(false, $record);
    }

    public function testCreateNewTemporalSegment() {
        // current
        $parent = $this->getParentRecord('currentParent');
        $this->assertTrue($parent->exists());

        $segment = $parent->getCurrentSegment($this->_dates[1]); // record spans from 1 to 3
        $this->assertTrue($segment->exists());

        $segment2 = $parent->createNewTemporalSegment($this->_dates[2]); // split it in half
        $this->assertTrue($segment2->exists());

        $this->assertTrue($segment->containsDate($this->_dates[1]));
        $this->assertFalse($segment->containsDate($this->_dates[2]));

        $this->assertTrue($segment2->containsDate($this->_dates[2]));
        $this->assertFalse($segment2->containsDate($this->_dates[1]));

        // future
        $parent = $this->getParentRecord('omegaParent', $this->_dates[1]);
        $this->assertTrue($parent->exists());

        $segment = $parent->getCurrentSegment($this->_dates[2]);
        $this->assertTrue($segment->exists());

        $segment2 = $parent->createNewTemporalSegment($this->_dates[2]);
        $this->assertTrue($segment2->exists());
        $this->assertTrue($segment->containsDate($this->_dates[1]));
        $this->assertTrue($segment2->containsDate($this->_dates[2]));
        $this->assertFalse($segment->containsDate($this->_dates[2]));
        $this->assertFalse($segment2->containsDate($this->_dates[1]));

        // test that the "can't-modify-the-past" mechanism is working
        $record2 = false;
        $record = $this->getChildRecord('pastChild', $this->_dates[-2]);
        $this->assertTrue($record->exists());
        try {
            $record2 = $record->createNewTemporalSegment($this->_dates[-1]);
            $this->failExpectedException('Doctrine_Temporal_PastModificationException');
        }
        catch (Exception $e) {
            if (get_class($e) != 'Doctrine_Temporal_PastModificationException') {
                throw $e;
            }
            // awesome.
        }
        // make sure the record didn't get modified in the process.
        $this->assertFalse($record2);
        $record->refresh(); // cancel out the erroneous data we just made
        $this->assertTrue($record->containsDate($this->_dates[-2]));
        $this->assertTrue($record->containsDate($this->_dates[-1]));

        // restore to original state
        $this->reloadAll();
    }

    public function testEnforceTemporalUniqueness() {
        // try to save a new record with an existing unique field
        $parent = new TemporalTestParent();
        $parent->name = 'futureParent';
        $parent->eff_date = $this->_dates[1];

        try {
            $parent->save();
            $this->failExpectedException('Doctrine_Temporal_UniqueKeyException');
        }
        catch (Exception $e) {
            if (get_class($e) != 'Doctrine_Temporal_UniqueKeyException') {
                throw $e;
            }
            // awesome.
        }
        // make sure the record didn't get saved
        $this->assertFalse($parent->exists());
    }

    public function testExtendChildDates() {
        $parent = $this->getParentRecord('futureParent', $this->_dates[1]);
        $child = $parent->Children[0];

        // extend the exp_date to be later
        $this->assertEqual($parent->exp_date, $this->_dates[3]);
        $this->assertEqual($child->exp_date, $this->_dates[3]);
        $parent->exp_date = $this->_dates[4];
        $mods = $parent->getModified(true); // true: get old value
        $parent->save();
        $parent->extendChildDates($mods);
        $this->assertEqual($parent->exp_date, $this->_dates[4]);
        $this->assertEqual($child->exp_date, $this->_dates[4]);

        // extend the eff_date to be earlier
        $this->assertEqual($parent->eff_date, $this->_dates[1]);
        $this->assertEqual($child->eff_date, $this->_dates[1]);
        $parent->eff_date = $this->_dates[0];
        $mods = $parent->getModified(true); // true: get old value
        $parent->save();
        $parent->extendChildDates($mods);
        $this->assertEqual($parent->eff_date, $this->_dates[0]);
        $this->assertEqual($child->eff_date, $this->_dates[0]);

        // restore to original state
        $this->reloadAll();
    }

    public function testGetCurrentSegment() {
        $parent = $this->getParentRecord('pastParent', $this->_dates[-1]);
        $segment = $parent->getCurrentSegment();
        $this->assertEqual($segment->segment_name, 'pastSegment-1'); // the last-past segment

        $parent = $this->getParentRecord('currentParent');
        $segment = $parent->getCurrentSegment();
        $this->assertEqual($segment->segment_name, 'currentSegment0'); // the segment that's actually current

        $parent = $this->getParentRecord('futureParent', $this->_dates[1]);
        $segment = $parent->getCurrentSegment();
        $this->assertEqual($segment->segment_name, 'futureSegment1'); // the first-future segment
    }

    public function testGetLastSegment() {
        $parent = $this->getParentRecord('pastParent', $this->_dates[-1]);
        $segment = $parent->getLastSegment();
        $this->assertEqual($segment->segment_name, 'pastSegment-1');

        $parent = $this->getParentRecord('currentParent');
        $segment = $parent->getLastSegment();
        $this->assertEqual($segment->segment_name, 'currentSegment1');

        $parent = $this->getParentRecord('futureParent', $this->_dates[1]);
        $segment = $parent->getLastSegment();
        $this->assertEqual($segment->segment_name, 'futureSegment2');
    }

    public function testGetOverlappingRecords() {
        $parent = new TemporalTestParent();
        $parent->name = 'futureParent';
        $parent->eff_date = $this->_dates[1];
        $overlapping = $parent->getOverlappingRecords();
        $this->assertEqual($overlapping[0]->name, 'futureParent');
    }

    public function testGetNextGetPrevious() {
        $parent = $this->getParentRecord('currentParent');
        $segment = $parent->getCurrentSegment(); // currentSegment0
        $next = $segment->getNext();
        $this->assertEqual($next->segment_name, 'currentSegment1');
        $previous = $segment->getPrevious();
        $this->assertEqual($previous->segment_name, 'currentSegment-1');
    }

    public function testChronologicalRelations() {
        // do an unordered query to make sure the default order is "wrong"
        $segments = Doctrine_Query::create()
            ->from('TemporalTestParentSegment')
            ->where('segment_name LIKE ?', 'currentSegment%')
            ->execute();
        $this->assertEqual($segments[0]->segment_name, 'currentSegment0');

        // now traverse the relation to make sure the order is fixed during relation travern
        $parent = $this->getParentRecord('currentParent');
        $segments = $parent->Segments;
        $this->assertEqual($segments[0]->segment_name, 'currentSegment-1'); // relation should have been ordered chronological
    }

    public function testSetDatesWithinParent() {
        $parent = $this->getParentRecord('futureParent', $this->_dates[1]);
        $child = new TemporalTestChild();
        $child->name = 'futureChild';

        // test with null exp_date
        $child->eff_date = $this->_dates[-5];
        $child->exp_date = null;
        $child->setDatesWithinParent($parent);
        $this->assertEqual($child->eff_date, $parent->eff_date);
        $this->assertEqual($child->exp_date, $parent->exp_date);

        // test with finite exp_date
        $child->eff_date = $this->_dates[-5];
        $child->exp_date = $this->_dates[5];
        $child->setDatesWithinParent($parent);
        $this->assertEqual($child->eff_date, $parent->eff_date);
        $this->assertEqual($child->exp_date, $parent->exp_date);
    }

    public function testTerminate() {
        // future = delete
        $parent = $this->getParentRecord('futureParent', $this->_dates[1]);
        $parent->terminate();
        $this->assertFalse($parent->exists());

        // current = terminate
        $parent = $this->getParentRecord('currentParent');
        $parent->terminate();
        $this->assertTrue($parent->exists());
        $this->assertEqual($parent->exp_date, $this->_dates[0]);
    }

    private function setDateVariables() {
        for ($i = -5; $i <= 5; $i++) {
            $this->_dates[$i] = date('Y') + $i . '-' . date('m-d');
        }
    }

    private function reloadAll() {
        Doctrine_Template_Temporal::setTemporalEnforcement(false);

        Doctrine_Query::create()->delete('TemporalTestParent')->execute();
        Doctrine_Query::create()->delete('TemporalTestChild')->execute();

        Doctrine_Core::getTable('TemporalTestParent')->clear(); // make sure cache doesn't interfere
        Doctrine_Core::getTable('TemporalTestChild')->clear(); // make sure cache doesn't interfere

        $parent = new TemporalTestParent();
        $parent->name = 'pastParent';
        $parent->eff_date = $this->_dates[-2];
        $parent->exp_date = $this->_dates[0];
        $child = new TemporalTestParentSegment();
        $child->segment_name = 'pastSegment-2';
        $child->eff_date = $this->_dates[-2];
        $child->exp_date = $this->_dates[-1];
        $parent->Segments[] = $child;
        $child = new TemporalTestParentSegment();
        $child->segment_name = 'pastSegment-1';
        $child->eff_date = $this->_dates[-1];
        $child->exp_date = $this->_dates[0];
        $parent->Segments[] = $child;
        $child = new TemporalTestChild();
        $child->name = 'pastChild';
        $child->eff_date = $this->_dates[-2];
        $parent->Children[] = $child;
        $parent->save();

        /* Current Parent */
        $parent = new TemporalTestParent();
        $parent->name = 'currentParent';
        $parent->eff_date = $this->_dates[-1];
        $parent->exp_date = $this->_dates[3];
        $parent->save(); // eff_date defaults to today; exp_date defaults to null

        $segment = new TemporalTestParentSegment();
        $segment->segment_name = 'currentSegment0';
        $segment->eff_date = $this->_dates[0];
        $segment->exp_date = $this->_dates[1];
        $parent->Segments[] = $segment;
        $segment->save();

        $segment = new TemporalTestParentSegment();
        $segment->segment_name = 'currentSegment1';
        $segment->eff_date = $this->_dates[1];
        $segment->exp_date = $this->_dates[3];
        $parent->Segments[] = $segment;
        $segment->save();

        $segment = new TemporalTestParentSegment();
        $segment->segment_name = 'currentSegment-1'; // this is out of order so that we can test chronological relation query
        $segment->eff_date = $this->_dates[-1];
        $segment->exp_date = $this->_dates[0];
        $parent->Segments[] = $segment;
        $segment->save();

        $child = new TemporalTestChild();
        $child->name = 'currentChild';
        $parent->Children[] = $child;

        $parent->save();

        /* Future Parent */
        $parent = new TemporalTestParent();
        $parent->name = 'futureParent';
        $parent->eff_date = $this->_dates[1];
        $parent->exp_date = $this->_dates[3];
        $child = new TemporalTestParentSegment();
        $child->segment_name = 'futureSegment1';
        $child->eff_date = $this->_dates[1];
        $child->exp_date = $this->_dates[2];
        $parent->Segments[] = $child;
        $child = new TemporalTestParentSegment();
        $child->segment_name = 'futureSegment2';
        $child->eff_date = $this->_dates[2];
        $child->exp_date = $this->_dates[3];
        $parent->Segments[] = $child;
        $child = new TemporalTestChild();
        $child->name = 'futureChild';
        $parent->Children[] = $child;
        $parent->save();

        $parent = new TemporalTestParent();
        $parent->name = 'omegaParent';
        $parent->save(); // dates default to today until forever
        $child = new TemporalTestParentSegment();
        $child->segment_name = 'omegaSegment';
        $parent->Segments[] = $child;
        $child = new TemporalTestChild();
        $child->name = 'omegaChild';
        $parent->Children[] = $child;
        $parent->save();

        Doctrine_Template_Temporal::setTemporalEnforcement();
    }
}

class TemporalTestParent extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '10',
        ));
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('segment_name', 'string', 255);
    }

    public function setUp() {
        $this->hasMany('TemporalTestChild as Children', array(
             'local'    => 'id',
             'foreign'  => 'parent_id'));
        $tso_template = new Doctrine_Template_TemporallySegmentedObject(array(
            'children'                  => array('Children'),
            'unique_fields'             => array('name'),
            'allow_past_modifications'  => false,
            'parent_fk'                 => 'parent_id',
        ));
        $this->actAs($tso_template);
    }
}

class TemporalTestChild extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', 10, array(
             'type' => 'integer',
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '10',
        ));
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('parent_id', 'integer', 10);
    }

    public function setUp() {
        $this->hasOne('TemporalTestParent as Parent', array(
             'local'    => 'parent_id',
             'foreign'  => 'id'));
        $temporal0 = new Doctrine_Template_Temporal(array(
            'parents'                   => array('Parent'),
            'unique_fields'             => array('name'),
            'allow_past_modifications'  => false,
        ));
        $this->actAs($temporal0);
    }
}
