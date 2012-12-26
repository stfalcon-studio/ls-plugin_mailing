#!/usr/bin/env php
<?php
     if (mail('test@example.com', 'Письмо из скрипта', 'Привет Василий, как дела?', 'From: ivan@example.com')){
      echo 'Письмо успешно отправлено!';
     }else{
      echo 'При отправке письма возникла ошибка';
     }
?>