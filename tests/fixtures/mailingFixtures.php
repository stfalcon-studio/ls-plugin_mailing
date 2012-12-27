<?php

$sDirRoot = dirname(realpath((dirname(__DIR__)) . "/../../"));
set_include_path(get_include_path().PATH_SEPARATOR.$sDirRoot);

require_once($sDirRoot . "/tests/AbstractFixtures.php");


class mailingFixtures extends AbstractFixtures
{
    public function load()
    {
        // clear mailing dir
        shell_exec('rm -rf /var/mail/sendmail/new/*');

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $oUser1 = $this->_createUser('user-woman1', 'qwerty','user_woman1@info.com', '2012-11-1 00:10:20');

        $oUser1->setProfileName('Woman1');
        $oUser1->setProfileAbout('Woman1');
        $oUser1->setProfileSex('woman');
        $this->oEngine->User_Update($oUser1);

        $oUser2 = $this->_createUser('user-woman2', 'qwerty','user_woman2@info.com', '2012-12-1 00:10:20');

        $oUser2->setProfileName('Woman2');
        $oUser2->setProfileAbout('Woman2');
        $oUser2->setProfileSex('woman');
        $this->oEngine->User_Update($oUser2);

        $oUser3 = $this->_createUser('user-other1', 'qwerty','user_other1@info.com', '2012-11-1 00:10:20');

        $oUser3->setProfileName('other1');
        $oUser3->setProfileAbout('other1');
        $oUser3->setProfileSex('other');
        $this->oEngine->User_Update($oUser3);

        $oUser4 = $this->_createUser('user-other2', 'qwerty','user_other2@info.com', '2012-12-1 00:10:20');

        $oUser4->setProfileName('other2');
        $oUser4->setProfileAbout('other2');
        $oUser4->setProfileSex('other');
        $this->oEngine->User_Update($oUser4);

    }

    private function _createUser($sUserName, $sPassword,$sMail,$sDate)
    {
        $oUser = Engine::GetEntity('User');
        $oUser->setLogin($sUserName);
        $oUser->setPassword(md5($sPassword));
        $oUser->setMail($sMail);
        $oUser->setUserDateRegister($sDate);
        $oUser->setUserIpRegister('127.0.0.1');
        $oUser->setUserActivate('1');
        $oUser->setUserActivateKey('0');

        $this->oEngine->User_Add($oUser);

        return $oUser;
    }
}

