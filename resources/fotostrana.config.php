<?php

// следующие параметры возьмите из настроек приложени€ (создайте его, если у вас его нет)
define('FOTOSTRANA_APPID', '------------'); // ваш APP_ID
define('FOTOSTRANA_CLIENTKEY', '-------------------------'); // клиентский ключ
define('FOTOSTRANA_SERVERKEY', '-------------------------'); // серверный ключ

// вы можете использовать тестовые адреса дл€ отладки,
define('FOTOSTRANA_URL', 'http://www.fotostrana.ru');
define('FOTOSTRANA_API_BASEURL', 'http://www.fotostrana.ru/apifs.php');

//ћобильна€ верси€: страницы приложений
define('FOTOSTRANA_MOBILE_BILLING_PAGE', 'http://m.fotostrana.ru/applications');
define('FOTOSTRANA_MOBILE_SPEND_PAGE', 'http://m.fotostrana.ru/applications');
define('FOTOSTRANA_MOBILE_CUSTOMIZE_PAGE', 'http://m.fotostrana.ru/applications');

// параметры дл€ совместимости с oAuth-авторизацией (по€витс€ в ‘отостране в обозримом будущем)
//define('FOTOSTRANA_OAUTH_CALLBACK', 'http://'.$_SERVER['HTTP_HOST'].'/sdk/callback-example.php');
//define('FOTOSTRANA_REQUIRED_PERMISSIONS', 'basic,friends');

// параметры отладки и кэшировани€
define('FOTOSTRANA_DEBUG', 0);
define('FOTOSTRANA_REQUESTS_CACHE_TIMEOUT', 30);
define('FOTOSTRANA_REQUESTS_LOGGER_ENABLED', true);

//параметры безопасности SDK
define('FOTOSTRANA_AUTH_KEY_CHECK', true);

// в эти константы занос€тс€ соответствующие значени€ из строки iframe при загрузке вашего приложени€
define('FOTOSTRANA_SESSION_KEY', isset($_REQUEST['sessionKey']) ? $_REQUEST['sessionKey'] : null);
define('FOTOSTRANA_VIEWER_ID', isset($_REQUEST['viewerId']) ? $_REQUEST['viewerId'] : null);
define('FOTOSTRANA_AUTH_KEY', isset($_REQUEST['authKey']) ? $_REQUEST['authKey'] : null);

// обменный курс дл€ функций с валютой
define('FOTOSTRANA_EXCHANGE', 1);

// битовые маски дл€ appSettings
define('FOTOSTRANA_MASK_DEFAULT', 1);
define('FOTOSTRANA_MASK_USERWALL', 2);
define('FOTOSTRANA_MASK_USERCOMMUNITIES', 4);
define('FOTOSTRANA_MASK_USERFORUM', 8);
define('FOTOSTRANA_MASK_USERINVITE', 16);
define('FOTOSTRANA_MASK_USERNOTIFY', 32);
define('FOTOSTRANA_MASK_SILENT_BILLING', 64);
define('FOTOSTRANA_MASK_USERPHOTO', 128);
define('FOTOSTRANA_MASK_USEREMAIL', 512);

?>