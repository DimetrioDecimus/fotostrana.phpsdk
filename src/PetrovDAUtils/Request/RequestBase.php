<?php
namespace PetrovDAUtils\Request;

use PetrovDAUtils\Enums\EnumsConfig;
use PetrovDAUtils\FotostranaError;

/**
 * Класс, формирующий запросы к API
 */
class RequestBase
{

    private $mode='GET';
    private $method;
    private $params=array();
    private $result_raw;
    private $result_formatted;
    private $cache;
    private $cache_allowed = true;
    private $old_cache_state = null;
    private $error;
    private $logDir  = '/log/';
    private $logFileName = 'requests.log';
    private $logFilePath;

    function __construct()
    {
        $this->flushResult();
        $this->cache = new RequestCache();
        $this->logDir = dirname(__FILE__) . $this->logDir;
        if (!is_dir($this->logDir)) mkdir($this->logDir, 0777, true);
        if ($this->logDir) $this->logFilePath = $this->logDir . $this->logFileName;
    }

    function setMethod($method)
    {
        $this->flushResult();
        $this->method=$method;
    }

    function setParam($name,$value)
    {
        if ($value) {
            $this->params[$name] = $value;
        }
    }

    function setParams($params=array())
    {
        $this->params=$params;
    }

    function setMode($mode)
    {
        if (strtoupper($mode)=='GET') {
            $this->mode='GET';
        } else {
            $this->mode='POST';
        }
    }

    function allowCache()
    {
        $this->old_cache_state = $this->cache_allowed;
        $this->cache_allowed = true;
    }

    function disallowCache()
    {
        $this->old_cache_state = $this->cache_allowed;
        $this->cache_allowed = false;
    }

    function restoreCache()
    {
        if ($this->old_cache_state === null) return;
        if ($this->old_cache_state) $this->allowCache();
            else $this->disallowCache();
        $this->old_cache_state = null;
    }

    function get()
    {
        if (!$this->result_formatted) {
            $this->runRequest();
            $this->formatResult();
        }
        $r = $this->result_formatted;
        return $r;
    }

    function getError()
    {
        return $this->error;
    }

    private function formatResult()
    {
        if ($this->result_raw) {

            $this->result_formatted = json_decode($this->result_raw, true);

            if (array_key_exists('error',$this->result_formatted)) {
                $this->error = $this->result_formatted['error'];
                throw new FotostranaError('API Request error', 'Error: '. (isset($this->error['error_subcode']) ? $this->error['error_subcode'].' (subcode)' : $this->error['error_code'].' (code)') . ': ' . $this->error['error_msg']);
            }

            if (!array_key_exists('response',$this->result_formatted)) {
                $this->error = $this->result_formatted['error'];
                throw new FotostranaError('API Request error', 'Invalid API response: no response field');
            }

        }
        else
        {
            throw new FotostranaError('API Request error', 'Empty API response');
        }
    }

    private function runRequest()
    {

        // готовим запрос
        $r = new SubRequest();
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
        if (strtoupper($this->mode)=='GET') {
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
        $this->params = array();
        $this->result_raw = false;
        $this->result_formatted = false;
        $this->error = false;
        $this->mode='GET';
        $this->allowCache();
    }

}