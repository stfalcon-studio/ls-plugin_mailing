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

    /**
     * Action initialization
     *
     * @return void
     */
    public function Init() {

        $this->Viewer_AddHtmlTitle($this->Lang_Get('ml_title'));
        $this->SetDefaultEvent('main');
    }

    /**
     * Registration events
     *
     * @return void
     */
    protected function RegisterEvent() {
        $this->AddEvent('main', 'EventAdd');
        $this->AddEvent('edit', 'EventAdd');
        $this->AddEvent('list', 'EventList');
        $this->AddEvent('delete', 'EventDelete');
        $this->AddEvent('activate', 'EventActivate');
    }

    /**
     * List of mailings
     *
     * @return mixed
     */
    public function EventList() {

        $this->CheckCurrentUser();

        $aMailing = $this->PluginMailing_ModuleMailing_GetMailings();
        $this->Viewer_Assign("sAction", "list");
        $this->Viewer_Assign('aMailing', $aMailing);
        $this->SetTemplateAction('list');
    }

    /**
     * Add and Edit mailings
     *
     * @return mixed
     */
    public function EventAdd() {

        $this->CheckCurrentUser();

        $sMailingId = (int) $this->GetParam(0);

        if (($bEditMode = ($this->sCurrentEvent == 'edit')) && $sMailingId) {
            // Если ID передан, но не найдена запись в БД, сообщаем об этом
            if (!$oMailing = $this->PluginMailing_ModuleMailing_GetMailingById($sMailingId)) {
                return parent::EventNotFound();
            }
        } else {
            $oMailing = new PluginMailing_ModuleMailing_EntityMailing();
            $oMailing->setMailingSex(array('man', 'woman', 'other'));
        }

        /* L10n plugin support */
        if (in_array('l10n', $this->Plugin_GetActivePlugins())) {
            $this->Viewer_Assign("aLangs", $this->PluginL10n_L10n_GetAllowedLangsAliases());
            $this->Viewer_Assign("sTemplateWebPathPluginL10n", Plugin::GetTemplateWebPath('l10n'));
        } else {
            $this->Viewer_Assign("aLangs", array());
            $this->Viewer_Assign("sTemplateWebPathPluginL10n", null);
        }

        // Флаг режима добавления или редактирования
        $this->Viewer_Assign("bEditMode", $bEditMode);

        $sLinkList = Router::GetPath('mailing') . "list";
        $this->Viewer_Assign("sLinkList", $sLinkList);

        if (isPost('submit_button_save')) {
            $this->Security_ValidateSendForm();

            $bOk = true;

            // Активность рассылки
            $bActive = (bool) getRequest('active', false, 'post');

            $this->Viewer_Assign('bActive', $bActive);

            // Заголовок
            $sTitle = getRequest('subject', null, 'post');

            if (!func_check($sTitle, 'text', 2, 200)) {
                $this->Message_AddError($this->Lang_Get('talk_create_title_error'), $this->Lang_Get('error'));
                $bOk = false;
            }

            $oMailing->setMailingTitle($sTitle);

            // Текст письма
            $sText = htmlspecialchars(getRequest('talk_text', null, 'post'), ENT_QUOTES, 'UTF-8', true);

            if (!func_check($sText, 'text', 2, 10000)) {
                $this->Message_AddError($this->Lang_Get('talk_create_text_error'), $this->Lang_Get('error'));
                $bOk = false;
            }

            $oMailing->setMailingText($sText);

            // Фильтр по половому признаку
            $aSex = getRequest('aSex', array(), 'post');

            if (!is_array(getRequest('aSex')) || count(getRequest('aSex')) == 0) {
                $this->Message_AddError($this->Lang_Get('ml_sex_select_error'), $this->Lang_Get('error'));
                $bOk = false;
            }

            $oMailing->setMailingSex($aSex);

            // Фильтр по языку пользователя
            $aLangs = getRequest('aLangs', array(), 'post');

            if (in_array('l10n', $this->Plugin_GetActivePlugins())) {

                if (!is_array(getRequest('aLangs')) || count(getRequest('aLangs')) == 0) {
                    $this->Message_AddError($this->Lang_Get('ml_lang_select_error'), $this->Lang_Get('error'));
                    $bOk = false;
                }
            }

            $oMailing->setMailingLang($aLangs);

            if ($bOk) {

                $oUserCurrent = $this->User_GetUserCurrent();

                $oMailing->setSendByUserId($oUserCurrent->GetId());

                if ($bEditMode) {
                    // удаляем старый список рассылки
                    $this->PluginMailing_ModuleMailing_DeleteMailingQueue($oMailing);

                    // пересоздаем очередь рассылки с новыми
                    if (!$this->PluginMailing_ModuleMailing_AddMailToQueue($oMailing)) {

                        $this->Message_AddErrorSingle($this->Lang_Get("ml_error_unable_to_edit"), $this->Lang_Get('error'), true);
                    } else {
                        $this->Message_AddNotice($this->Lang_Get('ml_ok_edit'), $this->Lang_Get('attention'), true);
                    }
                } else {

                    $oMailing->setMailingActive($bActive);

                    $oMailing->setMailingDate(date("Y-m-d H:i:s"));

                    if (!$this->PluginMailing_ModuleMailing_AddMailing($oMailing)) {
                        $this->Message_AddErrorSingle($this->Lang_Get("ml_error_unable_to_add"), $this->Lang_Get('error'), true);
                    } else {
                        $sNoticeText = $bActive ? $this->Lang_Get('ml_ok') : $this->Lang_Get('ml_ok_inactive');
                        $this->Message_AddNotice($sNoticeText, $this->Lang_Get('attention'), true);
                    }
                }


                Router::Location($sLinkList);
            }
        }

        $this->Viewer_Assign('oMailing', $oMailing);

        $this->SetTemplateAction('add');
    }

    /**
     * Activate/deactivate mailing
     *
     * @return mixed
     */
    public function EventActivate() {
        $this->Security_ValidateSendForm();
        $this->CheckCurrentUser();

        /* @var $oMailing PluginMailing_ModuleMailing_EntityMailing */
        if (!$oMailing = $this->PluginMailing_ModuleMailing_GetMailingById((int) $this->GetParam(0))) {
            return parent::EventNotFound();
        }

        $oMailing->setMailingActive($oMailing->getMailingActive() ? false : true);

        $oMailing->setMailingDate(date("Y-m-d H:i:s"));

        if (!$this->PluginMailing_ModuleMailing_UpdateMailing($oMailing)) {
            $this->Message_AddErrorSingle($this->Lang_Get("ml_error_unable_to_edit"), $this->Lang_Get('error'), true);
        } else {
            $this->Message_AddNotice($this->Lang_Get('ml_ok_status'), $this->Lang_Get('attention'), true);
        }

        Router::Location(Router::GetPath('mailing') . "list");
    }

    /**
     * Delete mailing
     *
     * @return mixed
     */
    public function EventDelete() {
        $this->Security_ValidateSendForm();
        $this->CheckCurrentUser();

        if (!$oMailing = $this->PluginMailing_ModuleMailing_GetMailingById((int) $this->GetParam(0))) {
            return parent::EventNotFound();
        }

        if (!$this->PluginMailing_ModuleMailing_DeleteMailing($oMailing->getMailingId())) {
            $this->Message_AddErrorSingle($this->Lang_Get("ml_error_unable_to_delete"), $this->Lang_Get('error'), true);
        } else {
            $this->Message_AddNotice($this->Lang_Get("ml_ok_delete"), $this->Lang_Get('attention'), true);
        }

        Router::Location(Router::GetPath('mailing') . "list");
    }

    /**
     * Проверяет текущего пользователя
     *
     * @return object
     */
    protected function CheckCurrentUser() {
        $oUser = $this->User_GetUserCurrent(); // Current user
        if (!$oUser || !$oUser->isAdministrator()) { //If user is admin show the form
            return Router::Action('error'); // Redirect to the error page
        }

        return $oUser;
    }

}