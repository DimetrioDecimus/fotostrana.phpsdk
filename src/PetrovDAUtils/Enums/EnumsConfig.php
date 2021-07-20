<?php


namespace PetrovDAUtils\Enums;


class EnumsConfig
{
    // ��������� ��������� �������� �� �������� ���������� (�������� ���, ���� � ��� ��� ���)
    const FOTOSTRANA_APPID = ''; // ��� APP_ID
    const FOTOSTRANA_CLIENTKEY = ''; // ���������� ����
    const FOTOSTRANA_SERVERKEY = ''; // ��������� ����

    // �� ������ ������������ �������� ������ ��� �������,
    const FOTOSTRANA_URL = 'http://www.fotostrana.ru';
    const FOTOSTRANA_API_BASEURL = 'http://www.fotostrana.ru/apifs.php';

    //��������� ������: �������� ����������
    const FOTOSTRANA_MOBILE_BILLING_PAGE = 'http://m.fotostrana.ru/applications';
    const FOTOSTRANA_MOBILE_SPEND_PAGE = 'http://m.fotostrana.ru/applications';
    const FOTOSTRANA_MOBILE_CUSTOMIZE_PAGE = 'http://m.fotostrana.ru/applications';

    // ��������� ������� � �����������
    const FOTOSTRANA_DEBUG = 0;
    const FOTOSTRANA_REQUESTS_CACHE_TIMEOUT = 30;
    const FOTOSTRANA_REQUESTS_LOGGER_ENABLED = true;

    //��������� ������������ SDK
    const FOTOSTRANA_AUTH_KEY_CHECK = false;

    // �������� ���� ��� ������� � �������
    const FOTOSTRANA_EXCHANGE = 1;

    // ������� ����� ��� appSettings
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