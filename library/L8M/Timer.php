<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Timer.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Timer.php 7 2014-03-11 16:18:40Z nm $
 */

/**
 *
 *
 * L8M_Timer
 *
 *
 */
class L8M_Timer
{

    /**
     *
     *
     * Class Variables
     *
     *
     */

	/**
	 * The start time.
	 *
	 * @var array
	 */
	protected $_startTime = NULL;

	/**
	 * The end time
	 *
	 * @var array
	 */
	protected $_endTime = NULL;

    /**
     *
     *
     * Class Constructor
     *
     *
     */

	/**
	 * Constructs L8M_Timer instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->start();
	}

    /**
     *
     *
     * Class Methods
     *
     *
     */

	/**
	 * Starts the timer.
	 *
	 * @return L8M_Timer
	 */
	public function start()
	{
		$this->_startTime = microtime();
		$this->_endTime = NULL;
		return $this;
	}

	/**
	 * Stops the timer.
	 *
	 * @return L8M_Timer
	 */
	public function stop()
	{
		$this->_endTime = microtime();
		return $this;
	}

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns the time measured.
	 *
	 * @return string
	 */
	public function getTime()
	{
		/**
		 * start time
		 */
		$startTime = explode(' ', $this->_startTime);

		/**
		 * end time
		 */
		$endTime = $this->_endTime
				 ? $this->_endTime
				 : microtime()
		;

		$endTime = explode(' ', $endTime);

		/**
		 * time
		 */
        $runTimeSeconds = $endTime[1] - $startTime[1];
        $runTimeMicroSeconds = $endTime[0] - $startTime[0];
        if ($runTimeMicroSeconds < 0) {
            $runTimeSeconds = $runTimeSeconds - 1;
            $runTimeMicroSeconds = '1.0e0' + $runTimeMicroSeconds;
        }
        $runTimeMicroSeconds = number_format($runTimeMicroSeconds, 8);
		$seconds = mktime(0, 0, $runTimeSeconds, 0, 0, 0);
        $timeFormatString = 'H:i:s';

        $microSeconds = ($runTimeMicroSeconds == NULL)
        			  ? number_format(0, 8)
        			  : number_format($runTimeMicroSeconds, 8)
		;

        $time = date($timeFormatString, $seconds) . '.' . substr($microSeconds, strpos($microSeconds, '.') + 1);

        return $time;

	}

}