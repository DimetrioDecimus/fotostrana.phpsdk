<?php
namespace PetrovDAUtils\Models;

use PetrovDAUtils\FotostranaError;

/**
 * ����� �������-�����
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
            $r->setParam('text',$text);
            $r->setParam('linkParams',$linkParams);
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
                $r->setParam('text',$text);
                $r->setParam('linkParams',$linkParams);
                $r->setParam('imgUrl',$img);
                $apiresult = $r->get();
            } else {
                // POST-������ CURL-��
                $r = $this->request();
                $r->setMethod('WallUser.appPostImage');
                $r->setParam('text',$text);
                $r->setParam('linkParams',$linkParams);
                $r->setParam('foto-img',"@".$img);
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