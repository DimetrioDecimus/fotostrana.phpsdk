<?php

// ��������� ��������� �������� �� �������� ���������� (�������� ���, ���� � ��� ��� ���)
define('FOTOSTRANA_APPID', '------------'); // ��� APP_ID
define('FOTOSTRANA_CLIENTKEY', '-------------------------'); // ���������� ����
define('FOTOSTRANA_SERVERKEY', '-------------------------'); // ��������� ����

// �� ������ ������������ �������� ������ ��� �������,
define('FOTOSTRANA_URL', 'http://www.fotostrana.ru');
define('FOTOSTRANA_API_BASEURL', 'http://www.fotostrana.ru/apifs.php');

//��������� ������: �������� ����������
define('FOTOSTRANA_MOBILE_BILLING_PAGE', 'http://m.fotostrana.ru/applications');
define('FOTOSTRANA_MOBILE_SPEND_PAGE', 'http://m.fotostrana.ru/applications');
define('FOTOSTRANA_MOBILE_CUSTOMIZE_PAGE', 'http://m.fotostrana.ru/applications');

// ��������� ��� ������������� � oAuth-������������ (�������� � ���������� � ��������� �������)
//define('FOTOSTRANA_OAUTH_CALLBACK', 'http://'.$_SERVER['HTTP_HOST'].'/sdk/callback-example.php');
//define('FOTOSTRANA_REQUIRED_PERMISSIONS', 'basic,friends');

// ��������� ������� � �����������
define('FOTOSTRANA_DEBUG', 0);
define('FOTOSTRANA_REQUESTS_CACHE_TIMEOUT', 30);
define('FOTOSTRANA_REQUESTS_LOGGER_ENABLED', true);

//��������� ������������ SDK
define('FOTOSTRANA_AUTH_KEY_CHECK', true);

// � ��� ��������� ��������� ��������������� �������� �� ������ iframe ��� �������� ������ ����������
define('FOTOSTRANA_SESSION_KEY', isset($_REQUEST['sessionKey']) ? $_REQUEST['sessionKey'] : null);
define('FOTOSTRANA_VIEWER_ID', isset($_REQUEST['viewerId']) ? $_REQUEST['viewerId'] : null);
define('FOTOSTRANA_AUTH_KEY', isset($_REQUEST['authKey']) ? $_REQUEST['authKey'] : null);

// �������� ���� ��� ������� � �������
define('FOTOSTRANA_EXCHANGE', 1);

// ������� ����� ��� appSettings
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