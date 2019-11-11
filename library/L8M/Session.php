<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/Session.php
 * @author     Norbert Marks <nm@l8m.com>
 * @version    $Id: Session.php 261 2015-03-05 16:50:13Z nm $
 */

/**
 *
 *
 * L8M_Session
 *
 *
 */
 class L8M_Session extends Zend_Session
 {
	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

	/**
	 * Returns the number of sessions that can be found in the session storage
	 * (whether it is file or database based session handling).
	 *
	 * @return int
	 */
	public static function getSessionCount()
	{
		$returnValue = NULL;

		/**
		 * sessionNamespace
		 */
		$sessionNamespace = new Zend_Session_Namespace();
		if (isset($sessionNamespace->initialized) &&
			$sessionNamespace->initialized === TRUE) {
			/**
			 * sessionSaveHandler
			 */
			$sessionSaveHandler = Zend_Session::getSaveHandler();

			/**
			 * no save handler, sessions are stored in session save path
			 */
			if ($sessionSaveHandler === NULL) {
				$sessionCount = 0;
				$sessionSavePath = session_save_path();
				$directoryIterator = new DirectoryIterator($sessionSavePath);
				while($directoryIterator->valid()) {
					if (preg_match('/^sess_[0-9a-z]+$/i', $directoryIterator->getFilename()) &&
						$directoryIterator->isFile()) {
						$sessionCount++;
					}
					$directoryIterator->next();
				}
				$returnValue = $sessionCount;

			} else

			/**
			 * a save handler that implements Zend_Session_SaveHandler_Interface
			 * and thus has a method gc() available
			 */
			if (is_object($sessionSaveHandler) &&
				$sessionSaveHandler instanceof Zend_Session_SaveHandler_Interface) {
				/**
				 * @todo retrieve count of active sessions from database
				 */
				$returnValue = FALSE;
			}
		}
		return $returnValue;
	}

	/**
	 * Clears sessions that can be cleared. and return number of cleared sessions.
	 *
	 * @return integer
	 */
	public static function clearAll($withOwn = TRUE)
	{

		/**
		 * sessionCount
		 */
		$sessionCount = 0;

		/**
		 * sessionSaveHandler
		 */
		$sessionSaveHandler = Zend_Session::getSaveHandler();

		/**
		 * no save handler, sessions are stored in session save path
		 */
		if ($sessionSaveHandler === NULL) {
			$sessionSavePath = session_save_path();
			$directoryIterator = new DirectoryIterator($sessionSavePath);
			while($directoryIterator->valid()) {
				if (preg_match('/^sess_[0-9a-z]+$/i', $directoryIterator->getFilename()) &&
					$directoryIterator->isFile()) {
					$sessionCount++;
					if ($directoryIterator->isWritable()) {
						$sessionFilePath = $sessionSavePath
										 . DIRECTORY_SEPARATOR
										 . $directoryIterator->getFilename()
						;
						if (@unlink($sessionFilePath)) {
							$sessionCount--;
						} else {
							/**
							 * destroy own session
							 */
							if ($withOwn) {
								Zend_Session::destroy(TRUE);
								$sessionCount--;
							}
						}
					}
				}
				$directoryIterator->next();
			}

		} else

		/**
		 * a save handler that implements Zend_Session_SaveHandler_Interface
		 * and thus has a method gc() available
		 */
		if (is_object($sessionSaveHandler) &&
			$sessionSaveHandler instanceof Zend_Session_SaveHandler_Interface) {
			$sessionSaveHandler->gc(0);
		}

		return $sessionCount;
	}
}