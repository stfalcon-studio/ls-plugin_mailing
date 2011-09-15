<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Mailing
 * @Plugin Id: mailing
 * @Plugin URI:
 * @Description: Mass mailing for users
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.5.0
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

/**
 * Mailing Plugin Mailing Class for LiveStreet
 *
 */
class PluginMailing_ModuleMailing extends Module
{

    /**
     * Mapper for PluginMailing_ModuleMailing
     * @var PluginMailing_ModuleMailing_MapperMailing
     */
    protected $_oMapper;

    /**
     * Initialization
     *
     * @return void
     */
    public function Init()
    {
        $this->_oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Add mailing
     *
     * @param PluginMailing_ModuleMailing_EntityMailing $oMailing
     * @return PluginMailing_ModuleMailing_EntityMailing|boolean
     */
    public function AddMailing(PluginMailing_ModuleMailing_EntityMailing $oMailing)
    {

        // Set default array of options
        if (!$oMailing->getMailingSex()) {
            $oMailing->setMailingSex(array('man', 'woman', 'other'));
        }

        // Get recipients ids
        if ($sId = $this->_oMapper->AddMailing($oMailing)) {
            $oMailing->setMailingId($sId);
            $this->AddMailToQueue($oMailing);
            return $oMailing;
        }
        return false;
    }

    /**
     * Update mailing
     * 
     * @param PluginMailing_ModuleMailing_EntityMailing $oMailing
     * @return boolean
     */
    public function UpdateMailing(PluginMailing_ModuleMailing_EntityMailing $oMailing)
    {
        return $this->_oMapper->UpdateMailing($oMailing);
    }

    /**
     * Delete mailing
     * @param int $sMailingId
     * @return boolean
     */
    public function DeleteMailing($sMailingId)
    {
        return $this->_oMapper->DeleteMailing($sMailingId);
    }

    /**
     * Add mail to queue
     *
     * @param PluginMailing_ModuleMailing_EntityMailing $oMailing
     * @return boolean
     */
    public function AddMailToQueue(PluginMailing_ModuleMailing_EntityMailing $oMailing)
    {

        // Get recipients ids
        $aUserIdTo = $this->PluginMailing_Users_GetUsersIdList($oMailing->getMailingSex(), $oMailing->getMailingLang(), $oMailing->getSendByUserId());


        // Return if recipients list is empty
        $i = 0;
        if (!empty($aUserIdTo)) {
            foreach ($aUserIdTo as $iUserId) {
                // Put mail into Mailing queue
                $oMailingQueue = new PluginMailing_ModuleMailing_EntityMailingQueue();
                $oMailingQueue->setMailingId($oMailing->getMailingId());
                $oMailingQueue->setUserId($iUserId);
                if ($this->_oMapper->addMailToQueue($oMailingQueue)) {
                    $i++;
                }
            }
        }
        
        // Update number of successful addition
        $oMailing->setMailingCount($i);

        $this->UpdateMailing($oMailing);

        return $oMailing;
    }

    /**
     * Delete mail from queue by array id
     * 
     * @param array $sArrayId
     * @return boolean
     */
    public function DeleteMailFromQueueByArrayId($sArrayId)
    {
        return $this->_oMapper->DeleteMailFromQueueByArrayId($sArrayId);
    }

    /**
     * Get mailings
     *
     * @return PluginMailing_ModuleMailing_EntityMailing
     */
    public function GetMailings()
    {
        return $this->_oMapper->GetMailings();
    }

    /**
     * Get Mailing By Id
     * @param int $sMailingId
     * @return PluginMailing_ModuleMailing_EntityMailing
     */
    public function GetMailingById($sMailingId)
    {
        return $this->_oMapper->GetMailingById($sMailingId);
    }

    /**
     * DeleteMailingQueue
     *
     * @param PluginMailing_ModuleMailing_EntityMailing $oMailing
     * @return boolean
     */
    public function DeleteMailingQueue(PluginMailing_ModuleMailing_EntityMailing $oMailing)
    {
        return $this->_oMapper->DeleteMailingQueue($oMailing);
    }

    /**
     * Get mails from queue for sending, limit in config
     *
     * @return PluginMailing_ModuleMailing_EntityMailingQueue
     */
    public function GetMailsFromQueue()
    {
        return $this->_oMapper->GetMailsFromQueue();
    }

    /**
     * Set talk id for mail_queue
     * 
     * @param array $aTalkIds
     * @return boolean
     */
    public function SetTalkIdForSendedMail($MailId, $TalkId)
    {
        return $this->_oMapper->SetTalkIdForSendedMail($MailId, $TalkId);
    }
    
}
