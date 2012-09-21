<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Talk
 *
 * @author user
 */
class PluginMailing_ModuleTalk extends PluginMailing_Inherit_ModuleTalk {

     public function SetUserCurrent(ModuleUser_EntityUser $oUser)
    {
        $this->oUserCurrent = $oUser;
    } 
}

?>
