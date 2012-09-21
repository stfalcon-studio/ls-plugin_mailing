<?php

return array(
    'lsdigest_usub_noparams' => 'Email or hash not found',
    'lsdigest_usub_nouser' => 'User by parameters not found',
    'lsdigest_usub_sys_error' => 'Unable to unsubscribe, contact to administrator',
    'lsdigest_usub_complete' => 'You succesfully unsubscribe from digest',
    'lsdigest_usub_already' => 'You already unsubscribe from digest',
    'unsub_notice' => '<p><a href="' . Config::Get('path.root.web') . '/mailing/unsubscribe?email=%%email%%&hash=%%hash%%">If you do not want to receive these messages, click here to unsubscribe</a></p>'
);
