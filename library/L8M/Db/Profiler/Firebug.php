<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Db/Profiler/Firebug.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Firebug.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Db_Profiler_Firebug
 *
 *
 */
class L8M_Db_Profiler_Firebug extends Zend_Db_Profiler_Firebug
{

    /**
     *
     *
     * Class Variables
     *
     *
     */

	/**
     * The label template for this profiler.
     *
     * @var string
     */
    protected $_label_template = 'Database Profiler: Queried database %totalCount% time(s) with a duration of %totalDuration% sec';

    /**
     *
     *
     * Class Constructor
     *
     *
     */

    /**
     * Returns Zend_Db_Profiler_Firebug instance
     *
     * @return Zend_Db_Profiler_Firebug
     */
    public function __construct()
    {
        $databaseConfig = Zend_Registry::get('Zend_Config')->database;
        if ($databaseConfig &&
        	$databaseConfig->get('profiler') &&
        	(FALSE != $labelTemplate = $databaseConfig->profiler->get('labelTemplate'))) {
	        $this->_label_template = $labelTemplate;
        }
    }

    /**
     *
     *
     *
     * Class Methods
     *
     *
     *
     */

    /**
     * Update the label of the message holding the profile info.
     *
     * @return void
     */
    protected function updateMessageLabel()
    {
        if (!$this->_message) {
            return;
        }
        $this->_message->setLabel(str_replace(array('%totalCount%',
                                                    '%totalDuration%'),
                                              array($this->getTotalNumQueries(),
                                                    (string) round($this->_totalElapsedTime, 5)),
                                              $this->_label_template));
    }

}