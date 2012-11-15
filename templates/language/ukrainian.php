<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Mailing
 * @Plugin Id: mailing
 * @Plugin URI:
 * @Description: Mailing for LiveStreet
 * @Version: 1.0
 * @Author: Vlad Lutsenko
 * @Author URI:
 * @LiveStreet Version: 1.0.1
 * @File Name: russian.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

/**
 * Russian Language file
 */
return array(
    'ml_title' => 'Розсилка повідомлень',
    'ml_list' => 'Створені розсилки',
    'ml_ok' => 'Розсилка створена',
    'ml_ok_inactive' => 'Чернетка збережений',
    'ml_sex' => 'Стать',
    'ml_sex_select_error' => 'Необхідно вибрати хоча б одну стать',
    'ml_lang_select_error' => ' Необхідно вибрати хоча б одну мову',
    'ml_active' => 'Розіслати',
    'ml_lang' => 'Мова',
    'ml_lang_all' => 'Будь який',
    'ml_list' => 'Список розсилань',
    'ml_subj' => 'Тема',
    'ml_progress' => 'розіслано/Всього',
    'ml_status' => 'Статус',
    'ml_date' => 'Дата створення',
    'ml_action' => 'Дія',
    'ml_ready' => 'Розсилається',
    'ml_wait' => 'В очікуванні',
    'ml_deactivation' => 'Зупинити',
    'ml_activation' => 'Запустити',
    'ml_edit' => 'Редагувати',
    'ml_delete' => 'Видалити',
    'ml_delete_confirm' => 'Ви впевнені в тому, що хочете видалити розсилку?',
    'ml_yes' => 'Так',
    'ml_no' => 'Ні',
    'ml_cancel' => 'Скасувати',
    'ml_save' => 'Зберегти',
    'ml_no_list' => 'Розсилання отстутствуют',
    'ml_send_all' => ' розіслано повністю',
    'ml_edit_warning' => 'Увага! При зміні фільтра, розсилка буде перестворена',
    'ml_loader_text' => 'Почекайте ласка, йде відправка даних ... ',
    'mailing_error_unable_to add' => 'Не вдалося додати розсилку',
    'mailing_error_unable_to_delete' => 'Не вдалося видалити розсилку',
    'mailing_error_unable_to_edit' => 'Не вдалося відредагувати розсилку',
    'mailing_error_unable_to_stop' => 'Не вдалося зупинити розсилку',
    'mailing_error_unable_to_start' => 'Не вдалося запустити розсилку',

    'ml_send_talk' => 'Створювати ЛЗ',
    'settings_tuning_notice_administration' => 'Отримувати розсилку від адміністрації',
    
    'lsdigest_usub_noparams' => 'E-mail або хеш не знайдений',
    'lsdigest_usub_nouser' => 'Користувача по параметрами не знайдено',
    'lsdigest_usub_sys_error' => 'Неможливо відмовитися від підписки, звяжіться з адміністратором',
    'lsdigest_usub_complete' => 'Ви успішно відмовитися від дайджесту',
    'lsdigest_usub_already' => 'Ви вже відмовитися від дайджесту',
    
    'unsub_notice' => '<p><a href="' . Config::Get('path.root.web') . '/mailing/unsubscribe?email=%%email%%&hash=%%hash%%">Якщо Ви не хочете отримувати ці повідомлення, натисніть на це посилання</a></p>'
);

