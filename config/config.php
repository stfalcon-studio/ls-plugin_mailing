<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Mailing
 * @Plugin Id: mailing
 * @Plugin URI:
 * @Description: Mass mailing for users
 * @Author: stfalcon-studio
 * @Author URI: http://stfalcon.com
 * @LiveStreet Version: 1.0.1
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

// Set action event on page mailing
Config::Set('router.page.mailing', 'PluginMailing_ActionMailing');

//Set db
Config::Set('db.table.mailing', '___db.table.prefix___mailing');
Config::Set('db.table.mailing_queue', '___db.table.prefix___mailing_queue');

// Limit for mails per once
Config::Set('MAIL_LIMIT', 20);

// подставляется в Talk, необходио так же для непоказа автору рассылки писем без ответов
Config::Set('IP_SENDER', '255.255.255.255');

$sMailingTypeName = 'admin';
return array(
    'DigestSubscribeName' => $sMailingTypeName,
    'miling_types' => array(
        $sMailingTypeName,
    ),
);