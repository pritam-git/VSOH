<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Wildfire/Plugin/FirePHP.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: FirePhp.php 433 2015-09-28 13:41:31Z nm $
 */

/**
 *
 *
 * L8M_Wildfire_Plugin_FirePhp
 *
 *
 */
class L8M_Wildfire_Plugin_FirePhp extends Zend_Wildfire_Plugin_FirePhp {

    /**
     *
     *
     * Class Variables
     *
     *
     */

    /**
     * logStartedTime, an array arrived at by exploding microtime() with ' '
     *
     * @var array
     */
    protected static $_logStartedTime = NULL;

    /**
     *
     *
     * Class Methods
     *
     *
     */

    /**
     * Create singleton instance.
     *
     * @param  string                       $class OPTIONAL Subclass of Zend_Wildfire_Plugin_FirePhp
     * @return Zend_Wildfire_Plugin_FirePhp Returns the singleton Zend_Wildfire_Plugin_FirePhp instance
     * @throws Zend_Wildfire_Exception
     */
    public static function init($class = null)
    {
        if (self::$_instance!==null) {
            throw new Zend_Wildfire_Exception('Singleton instance of Zend_Wildfire_Plugin_FirePhp already exists!');
        }
        if ($class!==null) {
            if (!is_string($class)) {
                throw new Zend_Wildfire_Exception('Third argument is not a class string');
            }
            Zend_Loader::loadClass($class);
            self::$_instance = new $class();
            if (!self::$_instance instanceof Zend_Wildfire_Plugin_FirePhp) {
                self::$_instance = null;
                throw new Zend_Wildfire_Exception('Invalid class to third argument. Must be subclass of Zend_Wildfire_Plugin_FirePhp.');
            }
        } else {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Constructor
     * @return void
     */
    protected function __construct()
    {
        $this->_channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
        $this->_channel->getProtocol(self::PROTOCOL_URI)->registerPlugin($this);
        self::$_logStartedTime = explode(' ',microtime());
    }

    /**
     * Get or create singleton instance
     *
     * @param $skipCreate boolean True if an instance should not be created
     * @return Zend_Wildfire_Plugin_FirePhp
     */
    public static function getInstance($skipCreate=false)
    {
        if (self::$_instance===null && $skipCreate!==true) {
            return self::init();
        }
        return self::$_instance;
    }

    /**
     * Record a message with the given data in the given structure
     *
     * @param string $structure The structure to be used for the data
     * @param array $data The data to be recorded
     * @param boolean $skipEncode TRUE if variable encoding should be skipped
     * @return boolean Returns TRUE if message was recorded
     * @throws Zend_Wildfire_Exception
     */
    protected function _recordMessage($structure, $data, $skipEncode=false)
    {
        switch($structure) {

            case self::STRUCTURE_URI_DUMP:

                if (!isset($data['key'])) {
                    require_once 'Zend' . DIRECTORY_SEPARATOR . 'Wildfire' . DIRECTORY_SEPARATOR . 'Exception.php';
                    throw new Zend_Wildfire_Exception('You must supply a key.');
                }
                if (!array_key_exists('data',$data)) {
                    require_once 'Zend' . DIRECTORY_SEPARATOR . 'Wildfire' . DIRECTORY_SEPARATOR . 'Exception.php';
                    throw new Zend_Wildfire_Exception('You must supply data.');
                }

                $value = $data['data'];
                if (!$skipEncode) {
                  $value = $this->_encodeObject($data['data']);
                }

                return $this->_channel->getProtocol(self::PROTOCOL_URI)->
                           recordMessage($this,
                                         $structure,
                                         array($data['key']=>$value));

            case self::STRUCTURE_URI_FIREBUGCONSOLE:

  	                if (!isset($data['meta']) ||
                    !is_array($data['meta']) ||
                    !array_key_exists('Type',$data['meta'])) {

                    throw new Zend_Wildfire_Exception('You must supply a "Type" in the meta information.');
                }
                if (!array_key_exists('data',$data)) {
                    throw new Zend_Wildfire_Exception('You must supply data.');
                }

                if ($data['meta']['Type']==self::EXCEPTION) {
                    // $data['meta']['Message'] = self::getRunTime().' '.$data['data']['Class'].': '.$data['data']['Message'];
                    // $data['data']['Type'] = '::';
                     // $data['meta']['Type'] = self::TRACE;
                } else

                if ($data['meta']['Type']==self::TABLE && array_key_exists('Label',$data['meta'])) {
                    $data['meta']['Label'] = self::getRunTime().' '.$data['meta']['Label'];
                } else

                if ($data['meta']['Type']==self::TABLE && !array_key_exists('Label',$data['meta'])) {
                    $data['meta']['Label'] = self::getRunTime().' '.$data['data'][0];
                    $data['data'] = self::_prepareDumpObject($data['data'][1]);
                } else

                if ($data['meta']['Type']==self::TRACE) {
                    $data['data']['Message'] = self::getRunTime().' '.$data['data']['Message'];
                } else {
                    $data['data'] = self::getRunTime().' '.$data['data'];
                }

                if (!isset($data['meta']) ||
                    !is_array($data['meta']) ||
                    !array_key_exists('Type',$data['meta'])) {

                    require_once 'Zend' . DIRECTORY_SEPARATOR . 'Wildfire' . DIRECTORY_SEPARATOR . 'Exception.php';
                    throw new Zend_Wildfire_Exception('You must supply a "Type" in the meta information.');
                }

                if (!array_key_exists('data',$data)) {
                    require_once 'Zend' . DIRECTORY_SEPARATOR . 'Wildfire' . DIRECTORY_SEPARATOR . 'Exception.php';
                    throw new Zend_Wildfire_Exception('You must supply data.');
                }

                $value = $data['data'];
                if (!$skipEncode) {
                  $value = $this->_encodeObject($data['data']);
                }

                return $this->_channel->getProtocol(self::PROTOCOL_URI)->
                           recordMessage($this,
                                         $structure,
                                         array($data['meta'],
                                               $value));

            default:
                require_once 'Zend' . DIRECTORY_SEPARATOR . 'Wildfire' . DIRECTORY_SEPARATOR . 'Exception.php';
                throw new Zend_Wildfire_Exception('Structure of name "'.$structure.'" is not recognized.');
                break;
        }
        return false;
    }

    /**
     * Returns current time with microseconds .
     *
     * @return string
     */
    public function getCurrentTime ()
    {
        return self::_getFormattedTime();
    }

    /**
     * Returns string timestamp with microseconds.
     *
     * @return string
     */
    public static function getRunTime ()
    {
        $currentTime = explode(' ', microtime());
        $runTimeSeconds = $currentTime[1] - self::$_logStartedTime[1];
        $runTimeMicroSeconds = $currentTime[0] - self::$_logStartedTime[0];
        if ($runTimeMicroSeconds < 0) {
            $runTimeSeconds = $runTimeSeconds - 1;
            $runTimeMicroSeconds = '1.0e0' + $runTimeMicroSeconds;
        }
        $runTimeMicroSeconds = number_format($runTimeMicroSeconds, 8);
        return self::_getFormattedTime($runTimeSeconds, $runTimeMicroSeconds);

    }

    /**
     * Returns formatted time string.
     *
     * @param  int    $seconds
     * @param  float  $microSeconds
     * @return string
     */
    public static function _getFormattedTime ($seconds = NULL, $microSeconds = NULL)
    {
        if (func_num_args() == 0) {
            $time = explode(' ', microtime());
            $seconds = mktime(0, 0, $time[1], NULL, NULL, NULL);
            $microSeconds = $time[0];
            $timeFormatString = 'Y-m-d H:i:s';
        } else {
            $seconds = mktime(0, 0, $seconds, 0, 0, 0);
            $timeFormatString = 'H:i:s';
        }
        $microSeconds = $microSeconds == NULL
        			  ? number_format(0, 8)
        			  : number_format($microSeconds, 8)
		;

		$formattedTime = date($timeFormatString, $seconds)
					   . '.'
					   . substr($microSeconds, strpos($microSeconds, '.') + 1)
		;

        return  $formattedTime;

    }


    /**
     * Prepares debugLabel for output in FireBug
     *
     * @param  object $debugCaller
     * @param  mixed  $debugLabel
     * @return string
     */
    protected function _prepareDebugLabel ($debugCaller = NULL, $debugLabel = NULL)
    {
        /**
         * debugCaller
         */
        if (is_object($debugCaller)) {
            if (method_exists($debugCaller, '__toString')) {
                $debugCaller = $debugCaller->__toString();
            } else {
                $debugCaller = get_class($debugCaller);
            }
        } else
        if ($debugCaller!==NULL) {
            $debugCaller = (string) $debugCaller;
        } else {
        	$debugCaller = NULL;
        }
        if ($debugCaller!==NULL) {
        	$debugCaller = '['
        				 . $debugCaller
        				 . '] '
        				;
        }
        /**
         * debugLabel
         */
        if ($debugLabel != NULL) {
            $debugLabel .= ' ';
        }
        return  self::getRunTime() . ' ' . $debugCaller . $debugLabel;
    }

    /**
     * Prepares dumpObject for output in FireBug
     *
     * @param  mixed $dumpObject
     * @return string
     */
    protected function _prepareDumpObject ($dumpObject = NULL)
    {
        $dumpObjectPrepared = FALSE;
        /**
         * array
         */
        if (is_array($dumpObject) && count($dumpObject) > 0) {
            $dumpObjectPrepared[] = array('arrayKey' , 'arrayValue');
            foreach ($dumpObject as $dumpLabel => $dumpKey) {
                $dumpObjectPrepared[] = array($dumpLabel , $dumpKey);
            }
        } else
        /**
         * object
         */
        if (is_object($dumpObject)) {
            $dumpObjectPrepared[] = array('objectClass' , 'objectData');
            $dumpObjectPrepared[] = array(get_class($dumpObject) , $dumpObject);
        } else {
            /**
             * others
             *
             * @todo gettype() is performance inefficient
             */
            $dumpObjectPrepared[] = array(gettype($dumpObject));
            $dumpObjectPrepared[] = array($dumpObject);
        }
        return $dumpObjectPrepared;
    }

}