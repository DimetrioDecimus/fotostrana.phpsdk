<?php
namespace PetrovDAUtils\Models;

use PetrovDAUtils\FotostranaError;
use PetrovDAUtils\Enums\EnumsProtocol;

/**
 * ����� �������-�����
 * @deprecated
 * TODO: class doesn't work with api
 */
class ModelWall extends ModelAbstractObject
{

    private $user_id;

    /**
     * TODO: method doesn't work on API
     * @deprecated
     * @param $text
     * @param array $linkParams
     * @return |null
     */
    function post($text, $linkParams=array())
    {
        try
        {
            $this->lastError = null;
            $r = $this->request();
            $r->setMethod('WallUser.appPost');
            $r->setParam(EnumsProtocol::TEXT,$text);
            $r->setParam(EnumsProtocol::LINK_PARAMS,$linkParams);
            $apiresult = $r->get();
            return $apiresult;
        }
        catch (FotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }

    // TODO: this method deprecated on API

    /**
     * @deprecated
     * @param $text
     * @param $img
     * @param array $linkParams
     * @return |null
     */
    function postImage($text, $img, $linkParams=array())
    {
        try
        {
            $this->lastError = null;
            if (strpos($img,'http')===0) {
                // ������� ������
                $r = $this->request();
                $r->setMethod('WallUser.appPostImage');
                $r->setParam(EnumsProtocol::TEXT,$text);
                $r->setParam(EnumsProtocol::LINK_PARAMS,$linkParams);
                $r->setParam(EnumsProtocol::IMG_URL,$img);
                $apiresult = $r->get();
            } else {
                // POST-������ CURL-��
                $r = $this->request();
                $r->setMethod('WallUser.appPostImage');
                $r->setParam(EnumsProtocol::TEXT,$text);
                $r->setParam(EnumsProtocol::LINK_PARAMS,$linkParams);
                $r->setParam(EnumsProtocol::FOTO_IMG,"@".$img);
                $r->setMode('POST');
                $apiresult = $r->get();
            }
            return $apiresult;
        }
        catch (FotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }

    function __construct($user_id)
    {
        $this->user_id=$user_id;
    }
}