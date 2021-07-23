<?php
namespace PetrovDAUtils\Request;

use PetrovDAUtils\Enums\EnumsConfig;
use PetrovDAUtils\Enums\EnumsProtocol;

use PetrovDAUtils\Model\ModelAuth;
use PetrovDAUtils\Model\ModelError;

use PetrovDAUtils\Model\ModelRequestResponse;

/**
 * Класс, формирующий запросы к API
 */
class RequestBase
{

    private $mode= EnumsProtocol::HTTP_QUERY_GET;
    private $method;
    private $params = [];
    private $result_raw;
    private $cache;
    private $cache_allowed = true;
    private $old_cache_state = null;
    private $logDir  = '/log/';
    private $logFileName = 'requests.log';
    private $logFilePath;
    private $authParams;

    function __construct(ModelAuth $authParams)
    {
        $this->authParams = $authParams;
        $this->flushResult();
        $this->cache = new RequestCache();
        $this->logDir = dirname(__FILE__) . $this->logDir;
        if (!is_dir($this->logDir)) mkdir($this->logDir, 0777, true);
        if ($this->logDir) $this->logFilePath = $this->logDir . $this->logFileName;
    }

    /**
     * @param $method
     * @return $this
     */
    function setMethod($method)
    {
        $this->flushResult();
        $this->method=$method;
        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    function setParam($name,$value)
    {
        if ($value) $this->params[$name] = $value;
        return $this;
    }

    /**
     * Now awailable GET | POST
     * @param $mode
     * @return $this
     */
    function setMode($mode)
    {
        $this->mode = strtoupper($mode)==EnumsProtocol::HTTP_QUERY_GET ? EnumsProtocol::HTTP_QUERY_GET : EnumsProtocol::HTTP_QUERY_POST;
        return $this;
    }

    /**
     * @return $this
     */
    function setCache(bool $toSet)
    {
        $this->old_cache_state = $this->cache_allowed;
        $this->cache_allowed = $toSet;
        return $this;
    }

    /**
     * @return $this
     */
    function restoreCache()
    {
        if ($this->old_cache_state === null) return $this;
        $this->setCache($this->old_cache_state);
        $this->old_cache_state = null;
        return $this;
    }

    /**
     * @return ModelRequestResponse
     * @throws ModelError
     */
    function sendRequest()
    {
        $this->runRequest();
        return new ModelRequestResponse($this->result_raw);
    }

    private function runRequest()
    {
        // готовим запрос
        $r = new SubRequest($this->authParams);
        $p = array_merge($this->params, array('method'=>$this->method));

        if ($this->cache_allowed && $cached_result = $this->cache->loadCache($p)) {
            $this->result_raw = $cached_result;
            if ($this->logFilePath && EnumsConfig::FOTOSTRANA_REQUESTS_LOGGER_ENABLED) {
                file_put_contents($this->logFilePath, date('r').' cache: '.$this->method.' '.serialize($this->params).' '.serialize($cached_result)."\n\n", FILE_APPEND);
            }
            return;
        }

        // делаем паузу, чтобы соблюдать требование MAX_QUERIES PER_TIME
        RequestCounter::addQuery();
        RequestCounter::wait();

        $url = $r->makeApiRequestUrl( $p );

        if (EnumsConfig::FOTOSTRANA_DEBUG) { echo "Fetching URL ".htmlspecialchars($url)." by ".$this->mode."<br>\n"; }

        // делаем запрос
        if (strtoupper($this->mode)==EnumsProtocol::HTTP_QUERY_GET) {
            $this->result_raw =  file_get_contents($url);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $this->result_raw = curl_exec($ch);
            curl_close($ch);
        }

        if ($this->logFilePath && EnumsConfig::FOTOSTRANA_REQUESTS_LOGGER_ENABLED) {
            file_put_contents($this->logFilePath, date('r').' request: '.$this->method.' '.serialize($this->params).' '.$this->result_raw."\n\n", FILE_APPEND);
        }

        if ($this->cache_allowed) {
            $this->cache->storeCache($p, $this->result_raw);
        }

        if (EnumsConfig::FOTOSTRANA_DEBUG) { var_dump($this->result_raw); }

    }

    private function flushResult()
    {
        $this->method = false;
        $this->params = [];
        $this->result_raw = false;
        $this->mode=EnumsProtocol::HTTP_QUERY_GET;
        $this->setCache(true);
    }

}