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
 * Mailing Plugin Users Class for LiveStreet
 *
 * Select all users IDs from Mapper
 */
class PluginMailing_ModuleUsers extends Module {

    /**
     * Mapper for PluginMailing_ModuleUsers
     * @var Mapper
     */
    protected $_oMapper;

    /**
     * Initialization
     *
     * @return void
     */
    public function Init() {
        $this->_oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Return list of users ids from DB
     * @param array $aSex
     * @param array $aLangs
     * @param int   $iSkipUserId
     * @return array
     */
    public function GetUsersIDList(array $aSex, array $aLangs, $iSkipUserId = null)
    {
        return $this->_oMapper->GetUsersIDList($aSex, $aLangs, $iSkipUserId);
    }

}