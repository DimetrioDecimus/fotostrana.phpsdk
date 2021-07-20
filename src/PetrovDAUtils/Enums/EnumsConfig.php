<?php


namespace PetrovDAUtils\Enums;


class EnumsConfig
{
    // следующие параметры возьмите из настроек приложения (создайте его, если у вас его нет)
    const FOTOSTRANA_APPID = ''; // ваш APP_ID
    const FOTOSTRANA_CLIENTKEY = ''; // клиентский ключ
    const FOTOSTRANA_SERVERKEY = ''; // серверный ключ

    // вы можете использовать тестовые адреса для отладки,
    const FOTOSTRANA_URL = 'http://www.fotostrana.ru';
    const FOTOSTRANA_API_BASEURL = 'http://www.fotostrana.ru/apifs.php';

    //Мобильная версия: страницы приложений
    const FOTOSTRANA_MOBILE_BILLING_PAGE = 'http://m.fotostrana.ru/applications';
    const FOTOSTRANA_MOBILE_SPEND_PAGE = 'http://m.fotostrana.ru/applications';
    const FOTOSTRANA_MOBILE_CUSTOMIZE_PAGE = 'http://m.fotostrana.ru/applications';

    // параметры отладки и кэширования
    const FOTOSTRANA_DEBUG = 0;
    const FOTOSTRANA_REQUESTS_CACHE_TIMEOUT = 30;
    const FOTOSTRANA_REQUESTS_LOGGER_ENABLED = true;

    //параметры безопасности SDK
    const FOTOSTRANA_AUTH_KEY_CHECK = false;

    // обменный курс для функций с валютой
    const FOTOSTRANA_EXCHANGE = 1;

    // битовые маски для appSettings
    const FOTOSTRANA_MASK_DEFAULT = 1;
    const FOTOSTRANA_MASK_USERWALL = 2;
    const FOTOSTRANA_MASK_USERCOMMUNITIES = 4;
    const FOTOSTRANA_MASK_USERFORUM = 8;
    const FOTOSTRANA_MASK_USERINVITE = 16;
    const FOTOSTRANA_MASK_USERNOTIFY = 32;
    const FOTOSTRANA_MASK_SILENT_BILLING = 64;
    const FOTOSTRANA_MASK_USERPHOTO = 128;
    const FOTOSTRANA_MASK_USEREMAIL = 512;
}