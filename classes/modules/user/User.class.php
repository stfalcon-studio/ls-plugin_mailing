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
 * Mailing Plugin Users Class for LiveStreet
 *
 * Select all users IDs from Mapper
 */
class PluginMailing_ModuleUser extends PluginMailing_Inherit_ModuleUser
{

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
     * @return array
     */
    public function GetUsersIDList(array $aSex, array $aLangs, $iSkipUserId = null, $aFilter = array())
    {
        $aIds = $this->oMapper->GetUsersIDList($aSex, $aLangs, $iSkipUserId, $aFilter);

        return $this->GetUsersAdditionalData($aIds);
    }

    /**
     *
     * @return boolean
     */
    public function SetUnSubHash($oUser = null)
    {
        return $this->oMapper->SetUnSubHash($oUser);
    }

    public function Add(ModuleUser_EntityUser $oUser)
    {
        if ($oUser = parent::Add($oUser)) {
            $this->SetUnSubHash($oUser);
            return $oUser;
        }

        return false;
    }

    public function GetUserByMailAndHash($sMail, $sHash)
    {
        if ($oUser = $this->GetUserByMail($sMail)) {
            if ($oUser->getUserNoDigestHash() == $sHash) {
                return $oUser;
            }
        }

        return false;
    }

    public function UnsubscribeUser($oUser)
    {
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_update'));
		$this->Cache_Delete("user_{$oUser->getId()}");
        return $this->oMapper->UnsubscribeUser($oUser);
    }

    public function UnsubscribeDigest($oUser)
    {
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('user_update'));
        $this->Cache_Delete("user_{$oUser->getId()}");
        return $this->oMapper->UnsubscribeDigest($oUser);
    }

    public function UpdateSubscription($oUser)
    {
        return $this->oMapper->UpdateSubscription($oUser);
    }
    
    public function SetUserCurrent(ModuleUser_EntityUser $oUser)
    {
        $this->oUserCurrent = $oUser;
    }        


}