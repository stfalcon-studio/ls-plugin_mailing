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
 * Mailing Plugin Hook class for LiveStreet
 *
 */
class PluginMailing_ModuleMailing_MapperMailing extends Mapper
{
    /**
     * Add mailing
     *
     * @param PluginMailing_ModuleMailing_EntityMailing $oMailing
     * @return int|boolean
     */
    public function AddMailing(PluginMailing_ModuleMailing_EntityMailing $oMailing)
    {
        $sql = "INSERT INTO " . Config::Get('db.table.mailing') . "
		     ( send_by_user_id,
                       mailing_title,
                       mailing_text,
                       mailing_count,
                       mailing_active,
                       mailing_sex,
                       mailing_date,
                       mailing_lang
                     )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
		";
        if ($iId = $this->oDb->query($sql,
                        $oMailing->getSendByUserId(), $oMailing->getMailingTitle(), $oMailing->getMailingText(),
                        $oMailing->getMailingCount(), $oMailing->getMailingActive(), serialize($oMailing->getMailingSex()),
                        $oMailing->getMailingDate(), serialize($oMailing->getMailingLang()))) {
            return $iId;
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
        $sql = "UPDATE " . Config::Get('db.table.mailing') . "
		   SET send_by_user_id = ?,
                       mailing_title = ?,
                       mailing_text = ?,
                       mailing_count = ?d,
                       mailing_active = ?d,
                       mailing_sex = ?,
                       mailing_date = ?,
                       mailing_lang = ?
                 WHERE mailing_id = ?d
		";
        if ($this->oDb->query($sql,
                        $oMailing->getSendByUserId(), $oMailing->getMailingTitle(), $oMailing->getMailingText(),
                        $oMailing->getMailingCount(), $oMailing->getMailingActive(), serialize($oMailing->getMailingSex()),
                        $oMailing->getMailingDate(), serialize($oMailing->getMailingLang()), $oMailing->getMailingId())) {
            return true;
        }
        return false;
    }

    /**
     * Delete mailing
     *
     * @param int $sMailingId
     * @return boolean
     */
    public function DeleteMailing($sMailingId)
    {
        $sql =  "DELETE FROM " . Config::Get('db.table.mailing') . "
		  WHERE mailing_id = ?d
                ";
        if ($this->oDb->query($sql, $sMailingId)) {
            return true;
        }
        return false;
    }

    /**
     * Add mail to queue
     *
     * @param PluginMailing_ModuleMailing_EntityMailingQueue $oMailingQueue
     * @return boolean
     */
    public function AddMailToQueue(PluginMailing_ModuleMailing_EntityMailingQueue $oMailingQueue)
    {
        $sql = "INSERT INTO " . Config::Get('db.table.mailing_queue') . "
		     ( mailing_id, user_id )
                VALUES (?d, ?d)
               ";
        if ($this->oDb->query($sql,
                        $oMailingQueue->getMailingId(), $oMailingQueue->getUserId())) {
            return true;
        }
        return false;
    }

    /**
     * Get mails from queue for sending, limit in config
     *
     * @return PluginMailing_ModuleMailing_EntityMailingQueue
     */
    public function GetMailsFromQueue()
    {
        $sql = "SELECT m.*, mq.*, u.user_mail
                  FROM " . Config::Get('db.table.mailing_queue') . " as mq
                  LEFT JOIN " . Config::Get('db.table.user') . " u ON u.user_id = mq.user_id
                  LEFT JOIN " . Config::Get('db.table.mailing') . " m ON m.mailing_id = mq.mailing_id
                 WHERE m.mailing_active = 1
                   AND mq.talk_id IS NULL
		 ORDER BY mq.id
		 LIMIT ?d";
        $aMails = array();
        if ($aRows = $this->oDb->select($sql, Config::Get('MAIL_LIMIT'))) {
            foreach ($aRows as $oRow) {
                $aMails[] = new PluginMailing_ModuleMailing_EntityMailingQueue($oRow);
            }
            return $aMails;
        }
        return false;
    }

    /**
     * Delete mails from queue by array ids
     * @param array $aMailingId
     * @return boolead
     */
    public function DeleteMailFromQueueByArrayId($aMailingId)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.mailing_queue') . "
                 WHERE id IN(?a)
		";
        if ($this->oDb->query($sql, $aMailingId)) {
            return true;
        }
        return false;
    }

    /**
     * Get mailings
     *
     * @return PluginMailing_ModuleMailing_EntityMailing
     */
    public function GetMailings()
    {
        $sql = "SELECT m.*, count(mq.talk_id) as mailing_send
                  FROM " . Config::Get('db.table.mailing') . " m
                  LEFT JOIN " . Config::Get('db.table.mailing_queue') . " mq
                         ON mq.mailing_id = m.mailing_id
                 GROUP BY m.mailing_id
                ";

        $aMailing = array();
        if ($aRows = $this->oDb->select($sql)) {
            foreach ($aRows as $oRow) {
                $aMailing[] = new PluginMailing_ModuleMailing_EntityMailing($oRow);
            }
            return $aMailing;
        }
        return false;
    }

    /**
     * Get Mailing By Id
     * @param int $sMailingId
     * @return PluginMailing_ModuleMailing_EntityMailing
     */
    public function GetMailingById($sMailingId)
    {
        $sql = "SELECT *
                  FROM " . Config::Get('db.table.mailing') . "
                 WHERE mailing_id = ?d";
        if ($oRow = $this->oDb->selectRow($sql, $sMailingId)) {
            return new PluginMailing_ModuleMailing_EntityMailing($oRow);
        }
        return false;
    }

    /**
     * DeleteMailingQueue
     *
     * @param PluginMailing_ModuleMailing_EntityMailing $oMailing
     * @return boolean
     */
    public function DeleteMailingQueue(PluginMailing_ModuleMailing_EntityMailing $oMailing)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.mailing_queue') . "
                 WHERE mailing_id = ?d
                ";
        if ($this->oDb->query($sql, $oMailing->getMailingId())) {
            return true;
        }
        return false;
    }

    public function  SetTalkIdForSendedMail($MailId, $TalkId)
    {
        $sql = "UPDATE " . Config::Get('db.table.mailing_queue') . "
                   SET talk_id = ?d
                 WHERE id = ?d
		";
        if ($this->oDb->query($sql, $TalkId, $MailId)) {
            return true;
        }
        return false;
    }

}