<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Mailing
 * @Plugin Id: mailing
 * @Plugin URI:
 * @Description: Mass mailing for users
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.4.2
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */


/**
 * Deny direct access to this file
 */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

/**
 * Mailing Plugin class for LiveStreet
 */
class PluginMailing extends Plugin
{

    public $aInherits = array(
        'mapper' => array(
            'ModuleTalk_MapperTalk' => '_ModuleTalk_MapperTalk',
            'ModuleUser_MapperUser' => '_ModuleUser_MapperUser'
            ),
        'module' => array(
            'ModuleUser' => '_ModuleUser',
            'ModuleTalk' => '_ModuleTalk'
        )
    );

    /**
     * Plugin Activation
     *
     * @return boolean
     */
    public function Activate()
    {
        $resutls = $this->ExportSQL(dirname(__FILE__) . '/activate.sql');
        $this->Cache_Clean();
        return $resutls['result'];
    }

    /**
     * Деактивация плагина
     *
     * @return boolean
     */
    public function Deactivate()
    {
        $resutls = $this->ExportSQL(dirname(__FILE__) . '/deactivate.sql');
        $this->Cache_Clean();
        return $resutls['result'];
    }

    /**
     * Plugin Initialization
     *
     * @return void
     */
    public function Init()
    {
        $this->Viewer_Assign("sTemplateWebPathPluginMailing", Plugin::GetTemplateWebPath(__CLASS__));
    }

}