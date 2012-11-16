<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Mailing
 * @Plugin Id: mailing
 * @Plugin URI:
 * @Description: Mass mailing for users
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 0.5.1
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

/**
 * Mailing Plugin Hook class for LiveStreet
 *
 * Sets Hook for menu template and adds link into it
 */
class PluginMailing_HookMailing extends Hook {

    /**
     * Register Template Menu Hook
     *
     * @return void
     */
    public function RegisterHook() {
        $this->AddHook('template_menu_talk_talk_item', 'InitAction', __CLASS__);
        $this->AddHook('template_form_settings_tuning_end', 'FormTuning', __CLASS__);
        $this->AddHook('module_user_update_after', 'UpdateTuning', __CLASS__);
    }

    /**
     * Hook Handler
     * Add a link to menu
     *
     * @return mixed
     */
    public function InitAction($aVars) {
        $oUser = $this->User_GetUserCurrent();

        // If user is admin than show link
        if ($oUser && $oUser->isAdministrator()) {
            return $this->Viewer_Fetch(
                    Plugin::GetTemplatePath(__CLASS__) . 'menu.mailing.tpl');
        }
    }

    public function FormTuning()
    {
        return $this->Viewer_Fetch(
                    Plugin::GetTemplatePath(__CLASS__) . 'actions/ActionSettings/form_tuning.tpl');
    }

    public function UpdateTuning($aVars)
    {
        $oUser = $aVars['params'][0];
        if (isPost('submit_settings_tuning')) {
            $oUser->setUserNoDigest(getRequest('settings_notice_administration') ? 0 : 1 );
            $this->User_UpdateSubscription($oUser);
        }
    }

}