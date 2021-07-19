<?php
namespace PetrovDAUtils\Models;

use PetrovDAUtils\Request\FotostranaSubRequest;
/**
 * TODO: need to check for what this api interface? Should it be a apr of billing obj?
 */
class FotostranaMobileApi extends FotostranaObject
{

    public function buyItem($name, $amount, $item_id, $currency_names, $pic_url, $return, $exchange = null)
    {
        if ($exchange === null) $exchange = FOTOSTRANA_EXCHANGE;

        $params = array();
        $params['method'] = 'MobEvents.buyItem';
        $params['name'] = $name;
        $params['amount'] = $amount;
        $params['id'] = $item_id;
        $params['currency_names'] = $currency_names;
        $params['pic_url'] = $pic_url;
        $params['exchange'] = $exchange;

        if ($return !== null)
            $params['return'] = $return;

        $r = new FotostranaSubRequest();
        $url = $r->makeApiRequestUrl($params);
        return $this->modifyUrlForMobileApi($url);
    }

    public function spendMoney($amount, $return)
    {
        $params = array();
        $params['method'] = 'MobEvents.spendMoney';
        $params['amount'] = $amount;
        $params['return'] = $return;

        $r = new FotostranaSubRequest();
        $url = $r->makeApiRequestUrl($params);
        return $this->modifyUrlForMobileApi($url);
    }

    public function appSettings($request_permission, $return)
    {
        $params = array();
        $params['method'] = 'MobEvents.appSettings';
        $params['request_permission'] = $request_permission;
        $params['return'] = $return;

        $r = new FotostranaSubRequest();
        $url = $r->makeApiRequestUrl($params);
        return $this->modifyUrlForMobileApi($url);
    }

    public function invite($return, $userId = 0, $text = null, $optParams = null)
    {
        $params = array();
        $params['method'] = 'MobEvents.invite';
        $params['userId'] = $userId;
        $params['text'] = $text;
        $params['params'] = $optParams;
        $params['return'] = $return;

        $r = new FotostranaSubRequest();
        $url = $r->makeApiRequestUrl($params);
        return $this->modifyUrlForMobileApi($url);
    }

    private function modifyUrlForMobileApi(string $url)
    {
        $count = 1;
        return str_replace(FOTOSTRANA_API_BASEURL, FOTOSTRANA_MOBILE_CUSTOMIZE_PAGE, $url, $count);
    }
}