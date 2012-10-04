<?php

return array(
  'lsdigest_usub_noparams' => 'Відсутній email або hash',
    'lsdigest_usub_nouser' => 'По наданим параметрам користувача не знайденно',
    'lsdigest_usub_sys_error' => 'Не вдалося відписати, зверніться до адміністратора',
    'lsdigest_usub_complete' => 'Ви успішно відписались від дайджесту рецептів',
    'lsdigest_usub_already' => 'Ви вже відписались від дайджесту',
    'unsub_notice' => '<p><a href="' . Config::Get('path.root.web') . '/mailing/unsubscribe?email=%%email%%&hash=%%hash%%">Якщо Ви не хочете отримувати ці повідомлення, натисніть на це посилання</a></p>'
);