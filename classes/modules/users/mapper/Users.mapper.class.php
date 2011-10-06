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
 * Mapper for users IDs. Select all users IDs from DB
 */
class PluginMailing_ModuleUsers_MapperUsers extends Mapper
{

    /**
     * Get list of users ids from DB
     * @param array $aSex
     * @param array $aLangs
     * @param int   $iSkipUserId
     * @param bool  $iSkipLang
     * @return array
     */
    public function GetUsersIdList(array $aSex, array $aLangs, $iSkipUserId = null)
    {
        if (!count($aSex)) { 
            return array();
        }
        
        $sql = 'SELECT `user_id`
                  FROM ' . Config::Get('db.table.user') . '
                 WHERE `user_profile_sex` IN (?a)
               ';
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
    
}

