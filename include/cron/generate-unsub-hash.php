#!/usr/bin/env php
<?php
/* ---------------------------------------------------------------------------
 * @Plugin Name: LsDigest
 * @Plugin Id: lsdigest
 * @Plugin URI:
 * @Description:
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 1.0.1
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */
define('SYS_HACKER_CONSOLE', false);

$sDirRoot = dirname(realpath((dirname(__FILE__)) . "/../../../"));
set_include_path(get_include_path() . PATH_SEPARATOR . $sDirRoot);
chdir($sDirRoot);
require_once($sDirRoot . "/config/loader.php");
require_once($sDirRoot . "/engine/classes/Cron.class.php");

class GenerateUnsubHash extends Cron
{

    public function Client()
    {


        // Get active plugins
        $aActivePlugins = $this->oEngine->Plugin_GetActivePlugins();

        //  Checking plugin self status
        if (!in_array('mailing', $aActivePlugins)) {
            echo "Mailing plugin doesn't enabled! Please enable its before running." . PHP_EOL;
            return;
        }

        $this->oEngine->User_SetUnSubHash();
        $this->oEngine->Cache_Clean();
    }

}

$sLockFilePath = Config::Get('sys.cache.dir') . 'generatehash.lock';
/**
 * Создаем объект крон-процесса,
 * передавая параметром путь к лок-файлу
 */
$app = new GenerateUnsubHash($sLockFilePath);
print $app->Exec();
?>