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
            $this->oEngine->PluginMailing_ModuleMailing_SendMail($oMail);
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