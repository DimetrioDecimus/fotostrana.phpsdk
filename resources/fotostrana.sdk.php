<?php

require_once('fotostrana.config.php');
require_once('fotostrana.base.php');
require_once('fotostrana.object.php');
require_once('fotostrana.user.php');
require_once('fotostrana.wall.php');
//require_once('fotostrana.community.php');
require_once('fotostrana.pet.php');
require_once('fotostrana.exchange.php');
require_once('fotostrana.billing.php');
require_once('fotostrana.request.php');
require_once('fotostrana.subrequest.php');
require_once('fotostrana.requestscounter.php');
require_once('fotostrana.requestscache.php');
require_once('fotostrana.error.php');

/**
 * ќсновной класс Fotostrana SDK
 */
class fotostrana
{

    private $cache = array();
    public $isExecutable = true;
    public $lastError = null;

    function __construct()
    {
        try
        {
            $this->selfTest();
            $this->checkAuth();
        }
        catch (fotostranaError $e)
        {
            $this->isExecutable = false;
            $this->lastError = $e;
            return;
        }

        if (!defined('FOTOSTRANA_DEBUG')) {
            define('FOTOSTRANA_DEBUG', 0);
        }

        if (!defined('FOTOSTRANA_API_BASEURL')) {
            define('FOTOSTRANA_API_BASEURL','http://fotostrana.ru/apifs.php');
        }

        $this->flushCache();
    }

    function getUser($user_id)
    {
        if (!$this->isExecutable) return null;
        if (!array_key_exists($user_id, $this->cache['users'])) {
            $this->cache['users'][$user_id] = new fotostranaUser($user_id);
        }
        return $this->cache['users'][$user_id];
    }

    function getWall($user_id)
    {
        if (!$this->isExecutable) return null;
        if (!array_key_exists($user_id, $this->cache['walls'])) {
            $this->cache['walls'][$user_id] = new fotostranaWall($user_id);
        }
        return $this->cache['walls'][$user_id];
    }

    function getBilling()
    {
        if (!$this->isExecutable) return null;
        if (!$this->cache['billing']) {
            $this->cache['billing'] = new fotostranaBilling();
        }
        return $this->cache['billing'];
    }

    function getExchange()
    {
        if (!$this->isExecutable) return null;
        if (!$this->cache['exchange']) {
            $this->cache['exchange'] = new fotostranaExchange();
        }
        return $this->cache['exchange'];
    }

    function searchUsersAsArray($params=array())
    {
        if (!$this->isExecutable) return null;
        if (array_key_exists('prefix'.serialize($params), $this->cache['search'])) {
            return $this->cache['search']['prefix'.serialize($params)];
        } else {

            $r = new fotostranaRequest();
            $r->setMethod('User.getFromSearch');
            $r->setParams($params);
            $apiresult = $r->get();
            $final = $apiresult['response'];

            $this->cache['search']['prefix'.serialize($params)] = $final;
            return $final;
        }
    }

    function searchUsers($params=array())
    {
        if (!$this->isExecutable) return null;
        $result = $this->searchUsersAsArray($params);
        $final = array();
        if (is_array($result) && $result) {
            foreach ($result as $u) {
                $final[$u['user_id']] = $this->getUser($u['user_id']);
            }
        }
        return $final;
    }

    function flushCache()
    {
        $this->cache=array();
        $this->cache['users']=array();
        $this->cache['search']=array();
        $this->cache['walls']=array();
        $this->cache['billing']=array();
        $this->cache['exchange']=array();
    }

    function selfTest()
    {

        // тестируем текущее окружение
        // должны быть разрешены file_get_contents и CURL

        $t = true;

        if (!ini_get('allow_url_fopen')) {
            $t = false;
        }
        if (ini_get('safe_mode')) {
            $t = false;
        }
        if (!in_array('curl', get_loaded_extensions())) {
            $t = false;
        }

        if (!$t) {
            throw new fotostranaError('001');
        }

    }

    function checkAuth()
    {
        // “естируем безопасность

        $t = true;

        $ourAuth = md5(FOTOSTRANA_APPID.'_'.FOTOSTRANA_VIEWER_ID.'_'.FOTOSTRANA_SERVERKEY);
        if (FOTOSTRANA_AUTH_KEY_CHECK && (FOTOSTRANA_AUTH_KEY === null || FOTOSTRANA_AUTH_KEY != $ourAuth))
        {
            $t = false;
        }

        if (!$t) {
            throw new fotostranaError('002');
        }
    }

}

?>