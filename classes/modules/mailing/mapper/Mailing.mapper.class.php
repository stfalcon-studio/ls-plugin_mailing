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
        $sql = "INSERT INTO
                    " . Config::Get('db.table.mailing') . "
                (
                    send_by_user_id,
                    mailing_title,
                    mailing_text,
                    mailing_count,
                    mailing_active,
                    mailing_sex,
                    mailing_date,
                    mailing_lang,
                    mailing_talk
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
		";
        if ($iId = $this->oDb->query($sql, $oMailing->getSendByUserId(), $oMailing->getMailingTitle(), $oMailing->getMailingText(), $oMailing->getMailingCount(), $oMailing->getMailingActive(), serialize($oMailing->getMailingSex()), $oMailing->getMailingDate(), serialize($oMailing->getMailingLang()), $oMailing->getMailingTalk())) {
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
        $sql = "UPDATE
                    " . Config::Get('db.table.mailing') . "
                SET
                    send_by_user_id = ?,
                    mailing_title = ?,
                    mailing_text = ?,
                    mailing_count = ?d,
                    mailing_active = ?d,
                    mailing_sex = ?,
                    mailing_date = ?,
                    mailing_lang = ?,
                    mailing_talk = ?d
                 WHERE
                    mailing_id = ?d
		";
        if ($this->oDb->query($sql, $oMailing->getSendByUserId(), $oMailing->getMailingTitle(), $oMailing->getMailingText(), $oMailing->getMailingCount(), $oMailing->getMailingActive(), serialize($oMailing->getMailingSex()), $oMailing->getMailingDate(), serialize($oMailing->getMailingLang()), $oMailing->getMailingTalk(), $oMailing->getMailingId())) {
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
        $sql = "DELETE FROM
                    " . Config::Get('db.table.mailing') . "
                WHERE
                    mailing_id = ?d
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
        $sql = "INSERT INTO
                    " . Config::Get('db.table.mailing_queue') . "
                (
                    mailing_id,
                    user_id,
                    sended
                )
                VALUES (?d, ?d, ?d)
               ";
        if ($this->oDb->query($sql, $oMailingQueue->getMailingId(), $oMailingQueue->getUserId(), $oMailingQueue->getSended())) {
            return true;
        }
        return false;
    }

    /**
     * Get mails from queue for sending, limit in config
     *
     * @return \PluginMailing_ModuleMailing_EntityMailingQueue
     */
    public function GetMailsFromQueue()
    {
        $sql = "SELECT
                    m.*, mq.*, u.user_mail
                FROM
                    " . Config::Get('db.table.mailing_queue') . " as mq
                LEFT JOIN
                    " . Config::Get('db.table.user') . " u ON u.user_id = mq.user_id
                LEFT JOIN
                    " . Config::Get('db.table.mailing') . " m ON m.mailing_id = mq.mailing_id
                WHERE
                    m.mailing_active = 1
                 AND
                    mq.sended = 0
                ORDER BY
                    mq.id
                LIMIT
                    ?d";
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
     * Get mails from queue by mailing id
     *
     * @param int $iMailingId
     * @return \PluginMailing_ModuleMailing_EntityMailingQueue|boolean
     */
    public function GetMailsFromQueueByMailingId($iMailingId)
    {
        $sql = "SELECT
                    m.*, mq.*, u.user_mail
                FROM
                    " . Config::Get('db.table.mailing_queue') . " as mq
                LEFT JOIN
                    " . Config::Get('db.table.user') . " u ON u.user_id = mq.user_id
                LEFT JOIN
                    " . Config::Get('db.table.mailing') . " m ON m.mailing_id = mq.mailing_id
                WHERE
                    mq.mailing_id = ?d
                ORDER BY
                    mq.id
                ";
        $aMails = array();
        if ($aRows = $this->oDb->select($sql, $iMailingId)) {
            foreach ($aRows as $oRow) {
                $aMails[] = new PluginMailing_ModuleMailing_EntityMailingQueue($oRow);
            }
        }
        return $aMails;
    }

    /**
     * Delete mails from queue by array ids
     * @param array $aMailingId
     * @return boolead
     */
    public function DeleteMailFromQueueByArrayId($aMailingId)
    {
        $sql = "DELETE
                FROM
                    " . Config::Get('db.table.mailing_queue') . "
                WHERE
                    id IN(?a)
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
        $sql = "SELECT
                    m.*,
                    count(mq.sended) as mailing_send
                FROM
                    " . Config::Get('db.table.mailing') . " m
                LEFT JOIN
                    " . Config::Get('db.table.mailing_queue') . " mq ON mq.mailing_id = m.mailing_id AND (mq.sended = 1 OR mq.talk_id IS NOT NULL)
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
        $sql = "SELECT
                    m.*,
                    count(mq.sended) AS mailing_send
                FROM
                    " . Config::Get('db.table.mailing') . " AS m
                LEFT JOIN
                    " . Config::Get('db.table.mailing_queue') . " AS mq ON mq.mailing_id = m.mailing_id AND (mq.sended = 1 OR mq.talk_id IS NOT NULL)
                WHERE
                    m.mailing_id = ?d
                GROUP BY m.mailing_id";
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

    /**
     * Set talk id for mail
     *
     * @param int $MailId
     * @param int $TalkId
     * @return boolean
     */
    public function SetTalkIdForSendedMail($MailId, $TalkId)
    {
        $sql = "UPDATE
                    " . Config::Get('db.table.mailing_queue') . "
                SET
                    talk_id = ?d
                WHERE
                    id = ?d
		";
        if ($this->oDb->query($sql, $TalkId, $MailId)) {
            return true;
        }
        return false;
    }

    /**
     * Set mail in queue sended
     *
     * @param int $MailId
     * @return boolean
     */
    public function SetSended($MailId)
    {
        $sql = "UPDATE
                    " . Config::Get('db.table.mailing_queue') . "
                SET
                    sended = 1
                WHERE
                    id = ?d
		";
        if ($this->oDb->query($sql, $MailId)) {
            return true;
        }
        return false;
    }

}