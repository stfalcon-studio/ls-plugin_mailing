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
    'ml_title' => 'Subscribe below',
    'ml_list' => 'By mailing',
    'ml_ok' => 'Mailing list created',
    'ml_ok_inactive' => 'Draft saved',
    'ml_sex' => 'Paul',
    'ml_sex_select_error' => 'You must select at least one sex',
    'ml_lang_select_error' => 'You must select at least one language',
    'ml_active' => 'Send out',
    'ml_lang' => 'Language',
    'ml_lang_all' => 'Any',
    'ml_list' => 'Mailing List',
    'ml_subj' => 'Subject',
    'ml_progress' => 'Send Requests/Total',
    'ml_status' => 'Title',
    'ml_date' => 'Created',
    'ml_action' => 'Action',
    'ml_ready' => 'Distribution',
    'ml_wait' => 'Pending',
    'ml_deactivation' => 'Stop',
    'ml_activation' => 'Run',
    'ml_edit' => 'Edit',
    'ml_delete' => 'delete',
    'ml_delete_confirm' => 'Are you sure you want to delete this email?',
    'ml_yes' => 'Yes',
    'ml_no' => 'No',
    'ml_cancel' => 'Cancel',
    'ml_save' => 'Save',
    'ml_no_list' => 'Subscribe otstutstvuyut',
    'ml_send_all' => 'sent out a full',
    'ml_edit_warning' => 'Warning! If you change the filter, sending to rebuild. ',
    'ml_loader_text' => 'Please wait, is transmitting data ...',
    'mailing_error_unable_to add' => 'Can not add a mailing list',
    'mailing_error_unable_to_delete' => 'Unable to remove list',
    'mailing_error_unable_to_edit' => 'Can not edit the list',
    'mailing_error_unable_to_stop' => 'Failed to stop sending',
    'mailing_error_unable_to_start' => 'Could not start the list',

    'ml_send_talk' => 'Create Message',
    'settings_tuning_notice_administration' => 'to receive news from the administration',
    
    'lsdigest_usub_noparams' => 'Email or hash not found',
    'lsdigest_usub_nouser' => 'User by parameters not found',
    'lsdigest_usub_sys_error' => 'Unable to unsubscribe, contact to administrator',
    'lsdigest_usub_complete' => 'You succesfully unsubscribe from digest',
    'lsdigest_usub_already' => 'You already unsubscribe from digest',
    
    'unsub_notice' => '<p><a href="' . Config::Get('path.root.web') . '/mailing/unsubscribe?email=%%email%%&hash=%%hash%%"> If you do not want to receive these notifications, click on this link</a></p>'
);

