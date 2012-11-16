<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Mailing
 * @Plugin Id: mailing
 * @Plugin URI:
 * @Description: Mass mailing for users
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 1.0.1
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

class PluginMailing_ModuleMailing_EntityMailing extends Entity
{

    /**
     * Getters
     */
    public function getMailingId()
    {
        return (int) $this->_aData['mailing_id'];
    }

    public function getSendByUserId()
    {
        return (int) $this->_aData['send_by_user_id'];
    }

    public function getMailingTitle()
    {
        return $this->_aData['mailing_title'];
    }

    public function getMailingText()
    {
        return $this->_aData['mailing_text'];
    }

    public function getMailingDate()
    {
        return $this->_aData['mailing_date'];
    }

    public function getMailingCount()
    {
        return (int) isset($this->_aData['mailing_count']) ? $this->_aData['mailing_count'] : 0;
    }

    public function getMailingActive()
    {
        return (bool) isset($this->_aData['mailing_active']) ? $this->_aData['mailing_active'] : true;
    }

    public function getMailingSex()
    {
        if (!isset($this->_aData['mailing_sex'])) {
            return array();
        } else {
            return (array) unserialize($this->_aData['mailing_sex']);
        }
    }

    public function getMailingLang()
    {
        return (array) unserialize($this->_aData['mailing_lang']);
    }

    public function getFilter()
    {
        if (!isset($this->_aData['filter'])) {
            return array();
        }
        return $this->_aData['filter'];
    }

    public function getMailingTalk()
    {
        return $this->_aData['mailing_talk'];
    }

    /**
     * Setters
     */
    public function setMailingId($data)
    {
        $this->_aData['mailing_id'] = (int) $data;
    }

    public function setSendByUserId($data)
    {
        $this->_aData['send_by_user_id'] = (int) $data;
    }

    public function setMailingTitle($data)
    {
        $this->_aData['mailing_title'] = $data;
    }

    public function setMailingText($data)
    {
        $this->_aData['mailing_text'] = $data;
    }

    public function setMailingDate($data)
    {
        $this->_aData['mailing_date'] = $data;
    }

    public function setMailingCount($data)
    {
        $this->_aData['mailing_count'] = (int) $data;
    }

    public function setMailingActive($data)
    {
        $this->_aData['mailing_active'] = (bool) $data;
    }

    public function setMailingSex(array $data)
    {
        $this->_aData['mailing_sex'] = serialize($data);
    }

    public function setMailingLang(array $data)
    {
        $this->_aData['mailing_lang'] = serialize($data);
    }

    public function setMailingTalk($data)
    {
        $this->_aData['mailing_talk'] = (bool)$data;
    }

}
