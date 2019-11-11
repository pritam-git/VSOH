<?php
/*
 *  $Id: GoogleI18nTestCase.php 7 2014-03-11 16:18:40Z nm $
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
 * Doctrine_Template_GoogleI18n_TestCase
 *
 * @package     Doctrine
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.phpdoctrine.org
 * @since       1.2
 * @version     $Revision$
 */
class Doctrine_Template_GoogleI18n_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = "GoogleTranslateArticle";
        parent::prepareTables();
    }

    public function testGoogleI18nTranslatesEnglish()
    {
        $article = new GoogleTranslateArticle();
        $article->name = 'Testing this out';
        $article->Translation['en']->title = 'Hello, how are you?';
        $article->Translation['en']->description = 'Good evening';
        $article->save();

        $this->assertEqual($article->Translation['fr']->title, 'Bonjour, comment allez-vous?');
        $this->assertEqual($article->Translation['fr']->description, 'Bonsoir');
    }
}

class GoogleTranslateArticle extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('title', 'string', 255);
        $this->hasColumn('description', 'string', 255);
    }

    public function setUp()
    {
        $this->actAs('Sluggable', array('fields' => array('name')));
        $this->actAs('GoogleI18n', array(
            'languages' => array(
                'en', 'fr', 'es', 'ja', 'it'),
            'fields' => array(
                'title','description'
            )
        ));
    }
}