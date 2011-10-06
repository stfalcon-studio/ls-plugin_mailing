#!/usr/bin/env php
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
$sDirRoot = dirname(realpath((dirname(__FILE__)) . "/../../../"));
set_include_path(get_include_path() . PATH_SEPARATOR . $sDirRoot);
chdir($sDirRoot);

require_once($sDirRoot . "/config/loader.php");
require_once($sDirRoot . "/engine/classes/Cron.class.php");

class SendMailingNotifies extends Cron
{

    protected $sProcessName = 'SendMailingNotifies';

    /**
     * Выбираем пул заданий и рассылаем по ним e-mail
     */
    public function Client() {
        // Выбираем из очереди
        $aMails = $this->oEngine->PluginMailing_ModuleMailing_GetMailsFromQueue();

        if (empty($aMails)) {
            echo PHP_EOL . "No mailings are found.";
            return false;
        }

        foreach ($aMails as $oMail) {
            /* @var $oMail PluginMailing_ModuleMailing_EntityMailingQueue */

            // Создаем новый разговор
            $oTalk = Engine::GetEntity('Talk');
            $oTalk->setUserId($oMail->getSendByUserId());
            $oTalk->setTitle($oMail->getMailingTitle());
            $oTalk->setText(htmlspecialchars_decode($oMail->getMailingText(), ENT_QUOTES));
            $oTalk->setDate(date("Y-m-d H:i:s"));
            $oTalk->setDateLast(date("Y-m-d H:i:s"));
            $oTalk->setUserIp(Config::Get('IP_SENDER'));
            $oTalk = $this->oEngine->Talk_AddTalk($oTalk);

            // Отправляем пользователю
            $oTalkUser = Engine::GetEntity('Talk_TalkUser');
            $oTalkUser->setTalkId($oTalk->getId());
            $oTalkUser->setUserId($oMail->getUserId());
            $oTalkUser->setDateLast(null);
            $this->oEngine->Talk_AddTalkUser($oTalkUser);

            // Отправка самому себе, чтобы можно было читать ответ
            $oTalkUser = Engine::GetEntity('Talk_TalkUser');
            $oTalkUser->setTalkId($oTalk->getId());
            $oTalkUser->setUserId($oMail->getSendByUserId());
            $oTalkUser->setDateLast(date("Y-m-d H:i:s"));
            $this->oEngine->Talk_AddTalkUser($oTalkUser);

            // Отправляем оповещение на email
            $oUserToMail = $this->oEngine->User_GetUserById($oMail->getUserId());
            $oUserCurrent = $this->oEngine->User_GetUserById($oMail->getSendByUserId());
            $this->oEngine->Notify_SendTalkNew($oUserToMail, $oUserCurrent, $oTalk);
            $this->oEngine->PluginMailing_ModuleMailing_SetTalkIdForSendedMail($oMail->getId(), $oTalk->getId());
        }
    }

}

$sLockFilePath = Config::Get('sys.cache.dir') . 'mailing.lock';
/**
 * Создаем объект крон-процесса,
 * передавая параметром путь к лок-файлу
 */
$app = new SendMailingNotifies($sLockFilePath);
print $app->Exec();
?>