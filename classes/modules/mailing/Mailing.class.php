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

        // Save mailing
        if ($sId = $this->_oMapper->AddMailing($oMailing)) {
            $oMailing->setMailingId($sId);
        } else {
            return false;
        }

        // add mailing to queue
        $this->AddMailToQueue($oMailing);

        return $oMailing;
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
        $aUsersTo = $this->User_GetUsersIdList($oMailing->getMailingSex(), $oMailing->getMailingLang(), $oMailing->getSendByUserId(), $oMailing->getFilter());

        // Return if recipients list is empty
        $iAdded = 0;
        if (count($aUsersTo)) {
            foreach ($aUsersTo as $oUserTo) {
                if (!$oUserTo->getMail()) {
                    continue;
                }

                // Put mail into Mailing queue
                $oMailingQueue = new PluginMailing_ModuleMailing_EntityMailingQueue();
                $oMailingQueue->setMailingId($oMailing->getMailingId());
                $oMailingQueue->setUserId($oUserTo->getId());
                $oMailingQueue->setSended(false);

                if ($this->_oMapper->addMailToQueue($oMailingQueue)) {
                    $iAdded++;
                }
            }
        }

        // Update number of successful addition
        $oMailing->setMailingCount($iAdded);
        $oMailing->setMailingSended(0);
        $this->UpdateMailing($oMailing);

        return $oMailing;
    }

    /**
     * Activate mailing
     *
     * @param PluginMailing_ModuleMailing_EntityMailing $oMailing
     * @return boolean
     */
    public function ActivateMailing($oMailing)
    {
        return $this->UpdateMailing($oMailing);
    }

    /**
     * Send queue mail
     *
     * @param PluginMailing_ModuleMailing_EntityMailingQueue $oMail
     */
    public function SendMail($oMail)
    {
        if ($oMail->getMailingTalk()) {
            if (!isset($_SERVER['REMOTE_ADDR'])) {
                $_SERVER['REMOTE_ADDR'] = Config::Get('IP_SENDER');
            }
            // Создаем новый разговор
            $sTitle = $oMail->getMailingTitle();
            $sText = htmlspecialchars_decode($oMail->getMailingText(), ENT_QUOTES);

            $oUserToMail = $this->User_GetUserById($oMail->getUserId());
            $oUserCurrent = $this->User_GetUserById($oMail->getSendByUserId());

            $oTalk=$this->Talk_SendTalk($sTitle,$sText,$oUserCurrent,array($oUserToMail),false,false);

            if ($oTalk) {
                $this->Notify_SendTalkNew($oUserToMail, $oUserCurrent, $oTalk);
                $this->SetTalkIdForSendedMail($oMail->getId(), $oTalk->getId());
                $this->SetSended($oMail->getId());

                return true;
            }

            return false;

        } else {
            $oUserTo = $this->User_GetUserById($oMail->getUserId());
            $sText = htmlspecialchars_decode($oMail->getMailingText(), ENT_QUOTES);
            $this->Lang_SetLang($oUserTo->getUserLang());
            $sText .= $this->generateUnsubscribeLink($oUserTo, $oMail);
            $this->Mail_SetAdress($oUserTo->getMail(), $oUserTo->getLogin());
            $this->Mail_SetSubject($oMail->getMailingTitle());
            $this->Mail_SetBody($sText);
            $this->Mail_setHTML();

            if ($this->Mail_Send()) {
                return $this->SetSended($oMail->getId());
            }
            return false;
        }
    }

    /**
     * @param ModuleUser_EntityUser $oUser
     * @param PluginMailing_ModuleMailing_EntityMailingQueue $oMail
     *
     * @return string
     */
    private function generateUnsubscribeLink($oUser, $oMail){
        if ($oMail->getMailingType() == 'mailing-digest') {
            return $this->Lang_Get(
                'plugin.mailing.unsub_notice',
                array('email' => $oUser->getMail(), 'hash' => $oUser->getUserNoticeDigestBestTopicsHash())
            );
        } else {
            return $this->Lang_Get(
                'plugin.mailing.unsub_notice',
                array('email' => $oUser->getMail(), 'hash' => $oUser->getUserNoDigestHash())
            );
        }
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
     * @return \PluginMailing_ModuleMailing_EntityMailingQueue
     */
    public function GetMailsFromQueue()
    {
        return $this->_oMapper->GetMailsFromQueue();
    }

    /**
     * Get mails from queue by mailing id
     *
     * @param int $iMailingId
     * @return \PluginMailing_ModuleMailing_EntityMailingQueue
     */
    public function GetMailsFromQueueByMailingId($iMailingId)
    {
        return $this->_oMapper->GetMailsFromQueueByMailingId($iMailingId);
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

    /**
     * Set mail in queue sended
     *
     * @param int $MailId
     * @return boolean
     */
    public function SetSended($MailId)
    {
        return $this->_oMapper->SetSended($MailId);
    }

}
