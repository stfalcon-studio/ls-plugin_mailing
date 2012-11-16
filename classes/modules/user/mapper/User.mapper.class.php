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
 * Mapper for users IDs. Select all users IDs from DB
 */
class PluginMailing_ModuleUser_MapperUser extends PluginMailing_Inherit_ModuleUser_MapperUser
{

    /**
     * Get list of users ids from DB
     * @param array $aSex
     * @param array $aLangs
     * @param int   $iSkipUserId
     * @param bool  $iSkipLang
     * @return array
     */
    public function GetUsersIdList(array $aSex, array $aLangs, $iSkipUserId = null, $aFilter = array())
    {
        if (!count($aSex)) {
            return array();
        }

        $sql = 'SELECT `user_id`
                  FROM ' . Config::Get('db.table.user') . '
                 WHERE `user_profile_sex` IN (?a)
                   AND user_no_digest = 0 
               ';

        foreach ($aFilter as $key => $value) {
            $sql .= ' AND ' . $key . ' = ' . $value;
        }

        // Skip some user from results. For example current user
        if ($iSkipUserId) {
            $sql .= " AND `user_id` <> " . (int) $iSkipUserId;
        }
        if (count($aLangs)) {
            $sql .= ' AND `user_lang` IN (?a)';
            return $this->oDb->selectCol($sql, $aSex, $aLangs);
        }
        return $this->oDb->selectCol($sql, $aSex);
    }

    /**
     *
     * @return boolean
     */
    public function SetUnSubHash($oUser)
    {
        $bRes = true;

        $sql = "SELECT
                    u.user_id
                FROM
					" . Config::Get('db.table.user') . " as u
                WHERE
                    1=1";

        if (!is_null($oUser)) {
            $sql .= ' AND user_id = ' . (int) $oUser->getId();
        }

        if ($aRows = $this->oDb->select($sql)) {
            $sql = "UPDATE
                        " . Config::Get('db.table.user') . "
                    SET
                        user_no_digest_hash = ?
                    WHERE
                        user_id = ?d
                        ";
            foreach ($aRows as $aRow) {
                if (!$this->oDb->query($sql, func_generator(16), $aRow['user_id'])) {
                    $bRes = false;
                }
            }
        } else {
            $bRes = false;
        }
        return $bRes;
    }

    public function UnsubscribeUser($oUser)
    {
         $sql = "UPDATE
                        " . Config::Get('db.table.user') . "
                    SET
                        user_no_digest = 1
                    WHERE
                        user_id = ?d
                        ";
        return $this->oDb->query($sql, $oUser->getId());

    }

    public function UpdateSubscription($oUser)
    {
        $sql = "UPDATE
                    " . Config::Get('db.table.user') . "
                SET
                    user_no_digest = ?d
                WHERE
                    user_id = ?d
                ";
        return $this->oDb->query($sql, $oUser->getUserNoDigest(), $oUser->getId());
    }
}

