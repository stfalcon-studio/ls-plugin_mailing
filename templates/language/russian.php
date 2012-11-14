<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: Mailing
 * @Plugin Id: mailing
 * @Plugin URI:
 * @Description: Mailing for LiveStreet
 * @Version: 1.0
 * @Author: Vlad Lutsenko
 * @Author URI:
 * @LiveStreet Version: 0.4.2
 * @File Name: russian.php
 * @License: GNU GPL v2, http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * ----------------------------------------------------------------------------
 */

/**
 * Russian Language file
 */
return array(
    'ml_title' => 'Рассылка сообщений',
    'ml_list' => 'Созданные рассылки',
    'ml_ok' => 'Рассылка создана',
    'ml_ok_inactive' => 'Черновик сохранен',
    'ml_sex' => 'Пол',
    'ml_sex_select_error' => 'Необходимо выбрать хотя бы один пол',
    'ml_lang_select_error' => 'Необходимо выбрать хотя бы один язык',
    'ml_active' => 'Разослать',
    'ml_lang' => 'Язык',
    'ml_lang_all' => 'Любой',
    'ml_list' => 'Список рассылок',
    'ml_subj' => 'Тема',
    'ml_progress' => 'Разослано/Всего',
    'ml_status' => 'Статус',
    'ml_date' => 'Дата создания',
    'ml_action' => 'Действие',
    'ml_ready' => 'Рассылается',
    'ml_wait' => 'В ожидании',
    'ml_deactivation' => 'Остановить',
    'ml_activation' => 'Запустить',
    'ml_edit' => 'Редактировать',
    'ml_delete' => 'Удалить',
    'ml_delete_confirm' => 'Вы уверены в том, что хотите удалить рассылку?',
    'ml_yes' => 'Да',
    'ml_no' => 'Нет',
    'ml_cancel' => 'Отменить',
    'ml_save' => 'Сохранить',
    'ml_no_list' => 'Рассылки отстутствуют',
    'ml_send_all' => 'Разослано полностью',
    'ml_edit_warning' => 'Внимание! При изменении фильтра, рассылка будет пересоздана.',
    'ml_loader_text' => 'Подождите пожалуйста, идет отправка данных...',
    'mailing_error_unable_to add' => 'Не удалось добавить рассылку',
    'mailing_error_unable_to_delete' => 'Не удалось удалить рассылку',
    'mailing_error_unable_to_edit' => 'Не удалось отредактировать рассылку',
    'mailing_error_unable_to_stop' => 'Не удалось остановить рассылку',
    'mailing_error_unable_to_start' => 'Не удалось запустить рассылку',

    'ml_send_talk' => 'Создавать ЛС',
    'settings_tuning_notice_administration' => 'Получать рассылку от администрации',

    'lsdigest_usub_noparams' => 'E-mail или хэш не найден',
    'lsdigest_usub_nouser' => 'Пользователь по параметрам не найден',
    'lsdigest_usub_sys_error' => 'Невозможно отказаться от подписки, свяжитесь с администратором',
    'lsdigest_usub_complete' => 'Вы успешно отказаться от рассылки',
    'lsdigest_usub_already' => 'Вы уже отказались от рассылки',

    'unsub_notice' => '<p><a href="' . Config::Get('path.root.web') . '/mailing/unsubscribe?email=%%email%%&hash=%%hash%%">Если Вы не хотите получать эти уведомления, нажмите на эту ссылку</a></p>'
);

