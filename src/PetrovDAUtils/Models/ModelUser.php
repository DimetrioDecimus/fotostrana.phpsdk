<?php
namespace PetrovDAUtils\Models;

use PetrovDAUtils\Enums\EnumsProtocol;
use PetrovDAUtils\FotostranaError;

/**
 * Класс объекта-пользователя
 */
class ModelUser extends ModelAbstractObject
{

    private $user_id;
    public $user_loaded = false;

    function loadData()
    {
        if (!$this->user_loaded)
        {
            $r = $this->request();
            $r->setMethod('User.getProfiles');
            $r->setParam(EnumsProtocol::USER_IDS, $this->user_id);
            $r->setParam(EnumsProtocol::FIELDS,'user_name,user_lastname,user_link,sex,birthday,photo_small,photo_97,photo_192,photo_big,photo_box,city_id,city_name,slogan,vip_end,is_payable');
            $apiresult = $r->get();

            if ($apiresult['response'][$this->user_id]) {
                $this->data = $apiresult['response'][$this->user_id];
            } else {
                $this->data = array();
                throw new FotostranaError('003','User API object is not loaded.');
            }
            $this->user_loaded = true;
        }
    }

    function __construct($user_id)
    {
        $this->user_id=$user_id;
        try
        {
            $this->lastError = null;
            $this->loadData();
        }
        catch (FotostranaError $e)
        {
            $this->user_loaded = false;
            $this->lastError = $e;
        }
    }

    function __get($key)
    {
        try
        {
            $this->lastError = null;
            switch ($key) {
                case 'id':
                    return $this->user_id;
                    break;
                case 'registrationDate':
                    return $this->getRegistrationDate();
                    break;
                case 'friends':
                    return $this->getFriends();
                    break;
                case 'ofriends':
                    return $this->getFriendsAsObjects();
                    break;
                case 'isAppWidgetUser':
                    return $this->getIsAppWidgetUser();
                    break;
                case 'settings':
                    return $this->getUserSettings();
                    break;
                case 'installed':
                    return $this->settings['installed'];
                    break;
                case 'balance':
                    return $this->settings['balance'];
                    break;
                case 'bitmask':
                    return $this->settings['0'];
                    break;
                case 'marketDiscount':
                    return $this->getMarketDiscount();
                    break;
                default:
                    return parent::__get($key);
            }
        }
        catch (FotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }

    function getRegistrationDate()
    {
        if (!array_key_exists('registrationDate',$this->data)) {
            $r = $this->request();
            $r->setMethod('User.getRegistrationDate');
            $r->setParam(EnumsProtocol::USER_ID, $this->user_id);
            $apiresult = $r->get();
            $this->data['registrationDate'] = $apiresult['response'];
        }
        return $this->data['registrationDate'];
    }

    function getFriends()
    {
        if (!array_key_exists('friends',$this->data) || true) {
            $r = $this->request();
            $r->setMethod('User.getFriendsAny');
            $r->setParam(EnumsProtocol::USER_ID, $this->user_id);
            $apiresult = $r->get();
            $this->data['friends'] = $apiresult['response'];
        }
        return $this->data['friends'];
    }

    function getFriendsAsObjects()
    {
        if (!array_key_exists('friends_objects', $this->data)) {
            $friends = $this->getFriends();
            $this->data['friends_objects'] = array();
            if (is_array($friends)) {
                foreach ($friends as $friend_id) {
                    $this->data['friends_objects'][] = new ModelUser($friend_id);
                }
            }
        }
        return $this->data['friends_objects'];
    }

    function getIsAppWidgetUser()
    {
        if (!array_key_exists('isAppWidgetUser',$this->data)) {
            $r = $this->request();
            $r->setMethod('User.isAppWidgetUser');
            $r->setParam(EnumsProtocol::USER_ID, $this->user_id);
            $apiresult = $r->get();
            $this->data['isAppWidgetUser'] = $apiresult['response'];
        }
        return $this->data['isAppWidgetUser'];
    }

    function getUserSettings()
    {
        if (!array_key_exists('settings',$this->data)) {
            $r = $this->request();
            $r->setMethod('User.getUserSettingsAny');
            $r->setParam(EnumsProtocol::USER_ID, $this->user_id);
            $apiresult = $r->get();
            $this->data['settings'] = $apiresult['response'];
        }
        return $this->data['settings'];
    }

    function sendNotification($text, $params)
    {
        $r = $this->request();
        $r->setMethod('User.sendNotification');
        $r->setParam(EnumsProtocol::USER_IDS,$this->user_id);
        $r->setParam(EnumsProtocol::TEXT,$text);
        $r->setParam(EnumsProtocol::PARAMS,$params);
        $apiresult = $r->get();
        return $apiresult;
    }

    // TODO: WTF is achievements?
    function giveAchievment($achievment_id)
    {
        $r = $this->request();
        $r->setMethod('User.giveAchievment');
        $r->setParam(EnumsProtocol::USER_ID,$this->user_id);
        $r->setParam(EnumsProtocol::ACHIEVE_ID,$achievment_id);
        $apiresult = $r->get();
        return $apiresult;
    }

    // TODO: do we need tihs action?
    function getMarketDiscount()
    {
        $r = $this->request();
        $r->setMethod('User.getMarketDiscount');
        $r->setParam(EnumsProtocol::USER_ID,$this->user_id);
        $apiresult = $r->get();
        if (isset($apiresult['response'])) {
            return $apiresult['response'];
        }
    }

}