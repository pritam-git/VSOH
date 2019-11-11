<?php
/*
 *  $Id: LocatableTestCase.php 7 2014-03-11 16:18:40Z nm $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.phpdoctrine.org>.
 */

/**
 * Doctrine_Template_Locatable_TestCase
 *
 * @package     Doctrine
 * @author      Brent Shaffer <bshaffer@centresource.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Template_Locatable_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = "LocatableArticle_CityStateZip";
        $this->tables[] = "LocatableArticle_Address";        
        parent::prepareTables();
    }

    public function testLocatableLocatesByCityStateZip()
    {   
        $article = new LocatableArticle_CityStateZip();
        $article->name  = 'Testing this out';
        $article->city  = 'Nashville';
        $article->state = 'TN';
        $article->zip   = 37211;
        $article->save();
        
        $this->assertTrue($article->latitude, 36.0775432);
        $this->assertTrue($article->longitude, -86.7315785);
    }
    
    public function testLocatableLocatesWithMissingFields()
    {
        $article = new LocatableArticle_CityStateZip();
        $article->name  = 'Testing this out';
        $article->zip   = 37211;
        $article->save();
        
        $this->assertEqual($article->latitude, 36.0775432);
        $this->assertEqual($article->longitude, -86.7315785);
    }
    
    public function testLocatableLocatesByAddress()
    {
        $article = new LocatableArticle_Address();
        $article->name      = "Eiffel Tower";
        $article->address   = 'Tour Eiffel Champ de Mars 75007 Paris, France';
        $article->save();
        
        $this->assertEqual($article->latitude, 48.8550136);
        $this->assertEqual($article->longitude, 2.2891544);
    }
    
    public function testLocatableUpdate()
    {
      $article = new LocatableArticle_CityStateZip();
      $article->name  = 'Testing this out';
      $article->city  = 'Nashville';
      $article->state = 'TN';
      $article->zip   = 37211;
      $article->save();
      

      $this->assertEqual($article->latitude, 36.0775432);
      $this->assertEqual($article->longitude, -86.7315785);

      // Latitude / Longitude data changes with new zipcode
      $article->zip = 37212;
      $article->save();
      
      $this->assertEqual($article->latitude, 36.1281626);
      $this->assertEqual($article->longitude, -86.7969244);
      
      // Latitude / Longitude does not change if data is not updated
      $article->latitude = 0;
      $article->longitude = 0;
      $article->save();      
      
      $this->assertEqual($article->latitude, 0);
      $this->assertEqual($article->longitude, 0);
    }
    
}

class LocatableArticle_CityStateZip extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name',  'string',   255);
        $this->hasColumn('city',  'string',   255);
        $this->hasColumn('state', 'string',   255);
        $this->hasColumn('zip',   'integer',  4);
    }

    public function setUp()
    {
        $this->actAs('Sluggable', array('fields' => array('name')));
        $this->actAs('Locatable', array(
            'fields' => array('city','state', 'zip')
        ));
    }
}

class LocatableArticle_Address extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name',  'string',   255);
        $this->hasColumn('address',   'integer',  4);
    }

    public function setUp()
    {
        $this->actAs('Sluggable', array('fields' => array('name')));
        $this->actAs('Locatable', array(
            'fields' => array('address')
        ));
    }
}