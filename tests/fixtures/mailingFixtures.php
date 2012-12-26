<?php

$sDirRoot = dirname(realpath((dirname(__DIR__)) . "/../../"));
set_include_path(get_include_path().PATH_SEPARATOR.$sDirRoot);

require_once($sDirRoot . "/tests/AbstractFixtures.php");


class mailingFixtures extends AbstractFixtures
{
    public function load()
    {
         $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    }
}

