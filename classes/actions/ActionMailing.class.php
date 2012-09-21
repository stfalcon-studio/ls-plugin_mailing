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
 * Mailing Plugin Action class for LiveStreet
 *
 * Show form for message sending and send messages to all users in DB
 */
class PluginMailing_ActionMailing extends ActionPlugin
{

    /**
     * Action initialization
     *
     * @return void
     */
    public function Init()
    {
        $this->Viewer_AddHtmlTitle($this->Lang_Get('ml_title'));
        $this->SetDefaultEvent('main');
    }

    /**
     * Registration events
     *
     * @return void
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('main', 'EventMailer');
        $this->AddEvent('edit', 'EventEdit');
        $this->AddEvent('list', 'EventList');
        $this->AddEvent('delete', 'EventDelete');
        $this->AddEvent('activate', 'EventActivate');
        $this->AddEvent('unsubscribe', 'EventUnsubscribe');
    }

    /**
     * Send messages
     *
     * @return mixed
     */
    protected function EventMailer()
    {
        $oUser = $this->User_GetUserCurrent(); // Current user
        if (!$oUser || !$oUser->isAdministrator()) { //If user is admin show the form
            return Router::Action('error'); // Redirect to the error page
        }

        /* Language filter */
        if (in_array('l10n', $this->Plugin_GetActivePlugins())) {
            $aLangs = $this->PluginL10n_L10n_GetAllowedLangsAliases();
            $this->Viewer_Assign("sTemplateWebPathPluginL10n", Plugin::GetTemplateWebPath('l10n'));
            $this->Viewer_Assign("aLangs", $aLangs);
        } else {
            $this->Viewer_Assign("sTemplateWebPathPluginL10n", null);
        }

        if (getRequest('submit')) {
            if (!$this->CheckMailingFields()) {
                return false;
            }
            $sText = $this->Text_Parser(getRequest('talk_text', null, 'post'));
            $sTitle = getRequest('subject', null, 'post');
            $sActive = getRequest('active', null, 'post');

            $aLangs = getRequest('aLangs', null, 'post');
            $aSex = getRequest('aSex', null, 'post');


            // создаем рассылку
            $oMailing = new PluginMailing_ModuleMailing_EntityMailing();
            $oMailing->setSendByUserId($oUser->getId());
            $oMailing->setMailingTitle($sTitle);
            $oMailing->setMailingText($sText);
//            $oMailing->setMailingCount(count($aUserIdTo));
            $oMailing->setMailingActive($sActive);
            $oMailing->setMailingSex($aSex);
            $oMailing->setMailingLang($aLangs);
            $oMailing->setMailingDate(date("Y-m-d H:i:s"));
            $oMailing->setMailingTalk(getRequest('talk'));

            if ($this->PluginMailing_ModuleMailing_AddMailing($oMailing)) {
                $this->Message_AddNoticeSingle($this->Lang_Get("ml_ok"));
                func_header_location(Router::GetPath('mailing') . "list");
            } else {
                $this->Message_AddErrorSingle($this->Lang_Get("mailing_error_unable_to add"));
                return;
            }
        }
    }

    /**
     * List of mailings
     *
     * @return mixed
     */
    public function EventList()
    {
        $oUser = $this->User_GetUserCurrent(); // Current user
        if (!$oUser || !$oUser->isAdministrator()) { //If user is admin show the form
            return Router::Action('error'); // Redirect to the error page
        }
        $aMailing = $this->PluginMailing_ModuleMailing_GetMailings();
        $this->Viewer_Assign("sAction", "list");
        $this->Viewer_Assign('aMailing', $aMailing);
        $this->SetTemplateAction('list');
    }

    /**
     * Edit mailings
     *
     * @return mixed
     */
    public function EventEdit()
    {

        $oUser = $this->User_GetUserCurrent(); // Current user
        if (!$oUser || !$oUser->isAdministrator()) { //If user is admin show the form
            return Router::Action('error'); // Redirect to the error page
        }

        $sMailingId = (int) $this->GetParam(0);
        /* @var $oMailing PluginMailing_ModuleMailing_EntityMailing */
        if (!$oMailing = $this->PluginMailing_ModuleMailing_GetMailingById($sMailingId)) {
            return parent::EventNotFound();
        }

        if (isset($_REQUEST['cancel'])) {
            func_header_location(Router::GetPath('mailing') . "list/");
        }


        /* Language */
        $aLangs = array();
        if (in_array('l10n', $this->Plugin_GetActivePlugins())) {
            $aLangs = $this->PluginL10n_L10n_GetAllowedLangsAliases();
            $this->Viewer_Assign("aLangs", $aLangs);
            $this->Viewer_Assign("sTemplateWebPathPluginL10n", Plugin::GetTemplateWebPath('l10n'));
        } else {
            $this->Viewer_Assign("sTemplateWebPathPluginL10n", null);
        }

        if (isset($_REQUEST['submit_mailing_edit'])) {
            if (!$this->CheckMailingFields()) {
                $this->Viewer_Assign('oMailing', $oMailing);
                $this->SetTemplateAction('edit.mailing');
                return false;
            }
            $aSex = getRequest('aSex', array(), 'post');
            unset($aSex['$family']);
            $aLangs = getRequest('aLangs', array(), 'post');
            unset($aLangs['$family']);

            $oMailing->setMailingTitle(getRequest('subject'));

            $oMailing->setMailingText(htmlspecialchars(getRequest('talk_text', null, 'post')), ENT_QUOTES, 'UTF_8', true);

            //if ($oMailing->getMailingSex() != $aSex || $oMailing->getMailingLang() != $aLangs) {
            $oMailing->setMailingSex($aSex);
            $oMailing->setMailingLang($aLangs);
            // удаляем старый список рассылки
            $this->PluginMailing_ModuleMailing_DeleteMailingQueue($oMailing);

            $oUserCurrent = $this->User_GetUserCurrent();

            $oMailing->setSendByUserId($oUserCurrent->GetId());

            // пересоздаем очередь рассылки с новыми
            $this->PluginMailing_ModuleMailing_AddMailToQueue($oMailing);
            //}

            if (!$this->PluginMailing_ModuleMailing_UpdateMailing($oMailing)) {
                $this->Message_AddErrorSingle($this->Lang_Get("mailing_error_unable_to_edit"));
            }
            func_header_location(Router::GetPath('mailing') . "list");
        }

        $this->Viewer_Assign('oMailing', $oMailing);
        $this->SetTemplateAction('edit.mailing');
    }

    /**
     * Activate/deactivate mailing
     *
     * @return mixed
     */
    public function EventActivate()
    {
        $oUser = $this->User_GetUserCurrent(); // Current user
        if (!$oUser || !$oUser->isAdministrator()) { //If user is admin show the form
            return Router::Action('error'); // Redirect to the error page
        }

        $sMailingId = (int) $this->GetParam(0);
        /* @var $oMailing PluginMailing_ModuleMailing_EntityMailing */
        if (!$oMailing = $this->PluginMailing_ModuleMailing_GetMailingById($sMailingId)) {
            return parent::EventNotFound();
        }

        if (isset($_REQUEST['cancel'])) {
            func_header_location(Router::GetPath('mailing') . "list/");
        }

        if (isset($_REQUEST['submit_mailing_activate'])) {
            if ($oMailing->getMailingActive()) {
                $oMailing->setMailingActive(false);
            } else {
                $oMailing->setMailingActive(true);
            }

            $oMailing->setMailingDate(date("Y-m-d H:i:s"));
            if (!$this->PluginMailing_ModuleMailing_ActivateMailing($oMailing)) {
                $this->Message_AddErrorSingle($this->Lang_Get("mailing_error_unable_to_edit"));
            }
            func_header_location(Router::GetPath('mailing') . "list");
        }

        $this->Viewer_Assign('oMailing', $oMailing);
        $this->SetTemplateAction('activate.mailing');
    }

    /**
     * Activate mailing
     *
     * @param PluginMailing_ModuleMailing_EntityMailing $oMailing
     * @return boolean
     */
    protected function StartMailing(PluginMailing_ModuleMailing_EntityMailing $oMailing)
    {

        $oUserCurrent = $this->User_GetUserCurrent();
        $oMailing->setSendByUserId($oUserCurrent->getId());
        if (!$this->PluginMailing_ModuleMailing_AddMailToQueue($oMailingQueue)) {
            return false;
        }
        return true;
    }

    /**
     * check mailing fields
     * @return boolean
     */
    protected function CheckMailingFields()
    {
        $bOk = true;

        if (!func_check(getRequest('subject'), 'text', 2, 200)) {
            $this->Message_AddError($this->Lang_Get('talk_create_title_error'));
            $bOk = false;
        }

        if (!func_check(getRequest('talk_text'), 'text', 2, 1000000)) {
            $this->Message_AddError($this->Lang_Get('talk_create_text_error'));
            $bOk = false;
        }

        if (!is_array(getRequest('aSex')) || count(getRequest('aSex')) == 0) {
            $this->Message_AddError($this->Lang_Get('ml_sex_select_error'));
            $bOk = false;
        }

        if (in_array('l10n', $this->Plugin_GetActivePlugins())) {
            if (!is_array(getRequest('aLangs')) || count(getRequest('aLangs')) == 0) {
                $this->Message_AddError($this->Lang_Get('ml_lang_select_error'));
                $bOk = false;
            }
        }

        return $bOk;
    }

    /**
     * Delete mailing
     *
     * @return mixed
     */
    public function EventDelete()
    {
        $oUser = $this->User_GetUserCurrent(); // Current user
        if (!$oUser || !$oUser->isAdministrator()) { //If user is admin show the form
            return Router::Action('error'); // Redirect to the error page
        }
        $sMailingId = (int) $this->GetParam(0);

        if (!$oMailing = $this->PluginMailing_ModuleMailing_GetMailingById($sMailingId)) {
            return parent::EventNotFound();
        }

        if (isset($_REQUEST['cancel'])) {
            func_header_location(Router::GetPath('mailing') . "list/");
        }

        if (isset($_REQUEST['delete_mailing'])) {
            if (!$this->PluginMailing_ModuleMailing_DeleteMailing($oMailing->getMailingId())) {
                $this->Message_AddErrorSingle($this->Lang_Get("mailing_error_unable_to_delete"));
            }
            func_header_location(Router::GetPath('mailing') . "list");
        }

        $this->Viewer_Assign('oMailing', $oMailing);
        $this->SetTemplateAction('delete.mailing');
    }

    protected function EventUnsubscribe()
    {
        $sEmail = getRequest('email');
        $sHash  = getRequest('hash');
        if (!$sHash || !$sEmail) {
            $this->Message_AddError($this->Lang_Get('lsdigest_usub_noparams'), $this->Lang_Get('error'));
            return;
        }

        if (!$oUser = $this->User_GetUserByMail($sEmail)) {
            $this->Message_AddError($this->Lang_Get('lsdigest_usub_nouser'), $this->Lang_Get('error'));
            return;
        }
        
        if ($oUser->getUserNoDigestHash() != $sHash) {
            $this->Message_AddError($this->Lang_Get('lsdigest_usub_nouser'), $this->Lang_Get('error'));
            return;
        } else if ($oUser->getUserNoDigest()) {
            $this->Message_AddError($this->Lang_Get('lsdigest_usub_already'), $this->Lang_Get('error'));
            return;

        }

        if (!$this->User_UnsubscribeUser($oUser)) {
            $this->Message_AddError($this->Lang_Get('lsdigest_usub_sys_error'), $this->Lang_Get('error'));
        } else {
            $this->Message_AddNotice($this->Lang_Get('lsdigest_usub_complete'), $this->Lang_Get('attention'));
        }

    }
}