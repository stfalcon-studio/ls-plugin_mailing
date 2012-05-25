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
class PluginMailing_ModuleUser extends PluginMailing_Inherit_ModuleUser {

    /**
     * Mapper for PluginMailing_ModuleUsers
     * @var ModuleUser_MapperUser
     */
    protected $oMapper;

    /**
     * Return list of users ids from DB
     * @param array $aSex
     * @param array $aLangs
     * @param int   $iSkipUserId
     * @param array $aFilter
     * @return \ModuleUser_EntityUser
     */
    public function GetUsersIDList(array $aSex, array $aLangs, $iSkipUserId = null, $aFilter = array())
    {
        $aIds = $this->oMapper->GetUsersIDList($aSex, $aLangs, $iSkipUserId, $aFilter);

        return $this->GetUsersAdditionalData($aIds);
    }

    /**
     * Set unsub hash for user
     *
     * @return boolean
     */
    public function SetUnSubHash($oUser = null)
    {
        return $this->oMapper->SetUnSubHash($oUser);
    }

    /**
     * Generate unsub hash for user after registration
     *
     * @param ModuleUser_EntityUser $oUser
     * @return boolean
     */
    public function Add(ModuleUser_EntityUser $oUser)
    {
        if ($oUser = parent::Add($oUser)) {
            $this->SetUnSubHash($oUser);
            return $oUser;
        }

        return false;
    }

    /**
     * Update subscription settings
     *
     * @param ModuleUser_EntityUser $oUser
     * @return type
     */
    public function UpdateSubscription($oUser)
    {
        return $this->oMapper->UpdateSubscription($oUser);
    }


}