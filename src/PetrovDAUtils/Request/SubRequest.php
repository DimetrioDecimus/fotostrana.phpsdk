<?php
namespace PetrovDAUtils\Request;

use PetrovDAUtils\Enums\EnumsConfig;
use PetrovDAUtils\FotostranaAuthParams;

/**
 * Подкласс, формирующий URL и SIG для запроса к API
 */
class SubRequest
{
    // TODO: check need such array
    private $server_methods = array(
        // TODO: there is no such method in api
        'User.giveFBAchievment',
        'User.sendNotification',
        // TODO: watch about mail system for apps
        'User.sendAppEmail',
        // TODO: strange achive system
        'User.giveAchievment',
        // TODO: no such method
        'User.getAuthInfo',

        'Userphoto.checkAccess',

        // TODO: need modify api params
        'Billing.getUserBalanceAny',
        'Billing.withDrawMoneySafe',

        // добавьте методы при необходимости
    );

    private function makeSig(array $params) {
        ksort($params);
        if (in_array($params['method'],$this->server_methods)) {
            $p_string='';
        } else {
            $p_string=FotostranaAuthParams::i()->viewerId();
        }
        foreach ($params as $k=>$v)
        {
            if ($k && $v) {
                if (is_array($v)) {
                    $p_string .= str_replace('&', '', urldecode(http_build_query(array($k => $v))));
                }
                else {
                    $p_string .= $k . '=' . $v;
                }
            }
        }

        if (in_array($params['method'],$this->server_methods)) {
            $p_string.=EnumsConfig::FOTOSTRANA_SERVERKEY;
        } else {
            $p_string.=EnumsConfig::FOTOSTRANA_CLIENTKEY;
        }

        if (EnumsConfig::FOTOSTRANA_DEBUG) { echo "p_string: ".$p_string."<br/><br/>\n"; }

        $sig = md5($p_string);
        return $sig;

    }

    function urlencodeArray($params)
    {
        $res = array();
        foreach ($params as $key=>$value)
        {
            if (is_array($value)) $res[$key] = $this->urlencodeArray($value);
            else $res[$key] = urlencode($value);
        }
        return $res;
    }

    function makeApiRequestUrl(array $params) {

        if (!array_key_exists('appId',$params))     { $params['appId']=EnumsConfig::FOTOSTRANA_APPID; }
        if (!array_key_exists('timestamp',$params)) { $params['timestamp']=time(); }
        if (!array_key_exists('format',$params))    { $params['format']=1; }
        if (!array_key_exists('rand',$params))      { $params['rand']=rand(1,999999); }

        //TODO: check if we really need ti, all requests are servers
        if (!in_array($params['method'],$this->server_methods)) {
            $params['sessionKey'] = FotostranaAuthParams::i()->sessionKey();
            $params['viewerId'] = FotostranaAuthParams::i()->viewerId();
        }

        ksort($params);
        $url= EnumsConfig::FOTOSTRANA_API_BASEURL.'?sig='.$this->makeSig($params);

        $e_params = $this->urlencodeArray($params);
        foreach ($e_params as $k=>$v)
        {
            if ($k && $v) {
                if (is_array($v)) {
                    $url .= '&' . urldecode(http_build_query(array($k => $v)));
                }
                else {
                    $url .= '&' . $k . '=' . $v;
                }
            }
        }

        if (EnumsConfig::FOTOSTRANA_DEBUG) { echo "URL: ".htmlspecialchars($url)."<br/><br/>\n"; }

        return $url;

    }

}