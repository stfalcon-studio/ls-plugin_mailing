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
 * Handle message content and send it
 */
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(dirname(__FILE__))));
$sDirRoot = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
require_once($sDirRoot . "/config/config.ajax.php");

// Default values

$sError = '';

$bStateError = false;

if ($oEngine->User_IsAuthorization()) {
    $oUserCurrent = $oEngine->User_GetUserCurrent();
    if ($oUserCurrent->isAdministrator()) {
        $sText = $oEngine->Text_Parser(getRequest('talk_text', null, 'post'));
        $sTitle = getRequest('subject', null, 'post');
        $sActive = getRequest('active', null, 'post');

        $aLangs = getRequest('aLangs', null, 'post');
        unset($aLangs['$family']);
        $aSex = getRequest('aSex', null, 'post');
        unset($aSex['$family']);

        // проверка полей, текст, заголовок, пол
        if (!func_check($sText, 'text', 2, 3000)) {
            $sError = $oEngine->Lang_Get('talk_create_text_error');
            $bStateError = true;
        }
        if (!func_check($sTitle, 'text', 2, 200)) {
            $sError = $oEngine->Lang_Get('talk_create_title_error');
            $bStateError = true;
        }
        if (!is_array($aSex) || count($aSex) == 0) {
            $sError = $oEngine->Lang_Get('ml_sex_select_error');
            $bStateError = true;
        }
        if ( in_array('l10n',$oEngine->Plugin_GetActivePlugins()) ) {
            if (!is_array($aLangs) || count($aLangs) == 0) {
                $sError = $oEngine->Lang_Get('ml_lang_select_error');
                $bStateError = true;
            }
        }
        // если нет ошибок то:
        if (!$bStateError) {

            // создаем рассылку
            $oMailing = new PluginMailing_ModuleMailing_EntityMailing();
            $oMailing->setSendByUserId($oUserCurrent->getId());
            $oMailing->setMailingTitle($sTitle);
            $oMailing->setMailingText($sText);
            $oMailing->setMailingActive($sActive);
            $oMailing->setMailingSex($aSex);
            $oMailing->setMailingLang($aLangs);
            $oMailing->setMailingDate(date("Y-m-d H:i:s"));

            if (!$oEngine->PluginMailing_ModuleMailing_AddMailing($oMailing)) {
                $sError = $oEngine->Lang_Get('mailing_error_unable_to add');
                $bStateError = true;
            } else {
                // оповещение о результатах
                if ($sActive) {
                    $sText = $oEngine->Lang_Get('ml_ok');
                } else {
                    $sText = $oEngine->Lang_Get('ml_ok_inactive');
                }

                $sTitle = $oEngine->Lang_Get('ml_title');
            }
        }
    } else {
        $sError = $oEngine->Lang_Get('not_access');
        $bStateError = true;
    }
} else {
    $sError = $oEngine->Lang_Get('need_authorization');
    $bStateError = true;
}

$GLOBALS['_RESULT'] = array(
    "bStateError" => $bStateError,
    "sError" => $sError,
    "sErrorTitle" => $oEngine->Lang_Get('error'),
    "sTitle" => $sTitle,
    "sText" => $sText,
);