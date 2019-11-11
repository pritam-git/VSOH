<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage DeveloperGarden
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: GetParticipantStatusResponseType.php 435 2015-09-28 15:14:38Z nm $
 */

/**
 * @see Zend_Service_DeveloperGarden_Response_BaseType
 */
require_once 'Zend' . DIRECTORY_SEPARATOR . 'Service' . DIRECTORY_SEPARATOR . 'DeveloperGarden' . DIRECTORY_SEPARATOR . 'Response' . DIRECTORY_SEPARATOR . 'BaseType.php';

/**
 * @category   Zend
 * @package    Zend_Service
 * @subpackage DeveloperGarden
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @author     Marco Kaiser
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Service_DeveloperGarden_Response_ConferenceCall_GetParticipantStatusResponseType
    extends Zend_Service_DeveloperGarden_Response_BaseType
{
    /**
     * @var array
     */
    public $status = null;

    /**
     * returns the status array
     * a array of
     * Zend_Service_DeveloperGarden_ConferenceCall_ParticipantStatus
     *
     * @return array
     */
    public function getStatus()
    {
        return $this->status;
    }
}
