<?php
namespace PetrovDAUtils\Models;

use PetrovDAUtils\FotostranaError;
use PetrovDAUtils\Enums\FotostranaEnumsProtocol;

/**
 * Êëàññ îáúåêòà-ñòåíû
 * @deprecated
 * TODO: class doesn't work with api
 */
class FotostranaWall extends FotostranaObject
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
            $r->setParam(FotostranaEnumsProtocol::TEXT,$text);
            $r->setParam(FotostranaEnumsProtocol::LINK_PARAMS,$linkParams);
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
                // ïğîñòîé çàïğîñ
                $r = $this->request();
                $r->setMethod('WallUser.appPostImage');
                $r->setParam(FotostranaEnumsProtocol::TEXT,$text);
                $r->setParam(FotostranaEnumsProtocol::LINK_PARAMS,$linkParams);
                $r->setParam(FotostranaEnumsProtocol::IMG_URL,$img);
                $apiresult = $r->get();
            } else {
                // POST-çàïğîñ CURL-îì
                $r = $this->request();
                $r->setMethod('WallUser.appPostImage');
                $r->setParam(FotostranaEnumsProtocol::TEXT,$text);
                $r->setParam(FotostranaEnumsProtocol::LINK_PARAMS,$linkParams);
                $r->setParam(FotostranaEnumsProtocol::FOTO_IMG,"@".$img);
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