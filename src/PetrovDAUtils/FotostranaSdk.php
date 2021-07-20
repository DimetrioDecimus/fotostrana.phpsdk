<?php
namespace PetrovDAUtils;

//Enums
use PetrovDAUtils\Enums\EnumsConfig;

// Models
use PetrovDAUtils\Models\ModelUser;
use PetrovDAUtils\Models\ModelBilling;
use PetrovDAUtils\Models\ModelWall;
use PetrovDAUtils\Models\ModelMobileApi;

// Request
use PetrovDAUtils\Request\RequestBase;

/**
 * ќсновной класс Fotostrana SDK
 */
class FotostranaSdk
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
        catch (FotostranaError $e)
        {
            $this->isExecutable = false;
            $this->lastError = $e;
            return;
        }

        $this->flushCache();
    }

    function getUser($user_id)
    {
        if (!$this->isExecutable) return null;
        if (!array_key_exists($user_id, $this->cache['users'])) {
            $this->cache['users'][$user_id] = new ModelUser($user_id);
        }
        return $this->cache['users'][$user_id];
    }

    /** @deprecated  */
    function getWall($user_id)
    {
        if (!$this->isExecutable) return null;
        if (!array_key_exists($user_id, $this->cache['walls'])) {
            $this->cache['walls'][$user_id] = new ModelWall($user_id);
        }
        return $this->cache['walls'][$user_id];
    }

    function getBilling()
    {
        if (!$this->isExecutable) return null;
        if (!$this->cache['billing']) {
            $this->cache['billing'] = new ModelBilling();
        }
        return $this->cache['billing'];
    }

    /** @deprecated  */
    function getExchange()
    {
        if (!$this->isExecutable) return null;
        if (!$this->cache['exchange']) {
            $this->cache['exchange'] = new ModelMobileApi();
        }
        return $this->cache['exchange'];
    }

    function searchUsersAsArray($params=array())
    {
        if (!$this->isExecutable) return null;
        if (array_key_exists('prefix'.serialize($params), $this->cache['search'])) {
            return $this->cache['search']['prefix'.serialize($params)];
        } else {

            $r = new RequestBase();
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
            throw new FotostranaError('001');
        }

    }

    function checkAuth()
    {
        // “естируем безопасность

        $t = true;

        define('FOTOSTRANA_SESSION_KEY', $_REQUEST['sessionKey'] ?? null);
        define('FOTOSTRANA_VIEWER_ID',   $_REQUEST['viewerId'] ?? null);
        define('FOTOSTRANA_AUTH_KEY',    $_REQUEST['authKey'] ?? null);

        $ourAuth = md5(EnumsConfig::FOTOSTRANA_APPID.'_'.FOTOSTRANA_VIEWER_ID.'_'.EnumsConfig::FOTOSTRANA_SERVERKEY);
        if (EnumsConfig::FOTOSTRANA_AUTH_KEY_CHECK && (FOTOSTRANA_AUTH_KEY === null || FOTOSTRANA_AUTH_KEY != $ourAuth))
        {
            $t = false;
        }

        if (!$t) {
            throw new FotostranaError('002');
        }
    }

}
