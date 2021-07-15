<?php

/**
 * Класс объекта-стены
 */
class fotostranaWall extends fotostranaObject
{

    private $user_id;

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
        catch (fotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }

    function postImage($text, $img, $linkParams=array())
    {
        try
        {
            $this->lastError = null;
            if (strpos($img,'http')===0) {
                // простой запрос
                $r = $this->request();
                $r->setMethod('WallUser.appPostImage');
                $r->setParam('text',$text);
                $r->setParam('linkParams',$linkParams);
                $r->setParam('imgUrl',$img);
                $apiresult = $r->get();
            } else {
                // POST-запрос CURL-ом
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
        catch (fotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }

    function __construct($user_id)
    {
        $this->user_id=$user_id;
    }

    function get($limit = 3)
    {
        try
        {
            $this->lastError = null;
            $r = $this->request();
            $r->setMethod('WallUser.getListsCached');
            $r->setParam('userIds',$this->user_id);
            $apiresult = $r->get();
            if (array_key_exists('response',$apiresult)) {
                if (array_key_exists(FOTOSTRANA_VIEWER_ID,$apiresult['response'])) {
                    return array_slice($apiresult['response'][FOTOSTRANA_VIEWER_ID], 0, $limit);
                }
            }
        }
        catch (fotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }
}

?>