<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Mailing
 * @Plugin Id: mailing
 * @Plugin URI:
 * @Description: Mass mailing for users
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.4.2
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

class PluginMailing_ModuleMailing_EntityMailingQueue extends Entity
{
    /**
     * Getters
     */

    public function getId()
    {
        return $this->_aData['id'];
    }
    public function getMailingId()
    {
        return $this->_aData['mailing_id'];
    }

    public function getUserId()
    {
        return $this->_aData['user_id'];
    }

    

    /**
     * Setters
     */
    public function setId($data)
    {
        $this->_aData['id'] = $data;
    }

    public function setMailingId($data)
    {
        $this->_aData['mailing_id'] = $data;
    }

    public function setUserId($data)
    {
        $this->_aData['user_id'] = $data;
    }

    

}