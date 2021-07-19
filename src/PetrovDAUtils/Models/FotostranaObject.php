<?php
namespace PetrovDAUtils\Models;

use PetrovDAUtils\FotostranaError;

use PetrovDAUtils\Request\FotostranaRequest;

/**
 * Êëàññ àáñòğàêòíîãî îáúåêòà API Ôîòîñòğàíû (ïîëüçîâàòåëÿ, èçîáğàæåíèÿ, ñòåíû è ò.ä.)
 */
abstract class FotostranaObject extends FotostranaBase
{

    public $lastError = null;
    protected $data = array();

    function request()
    {
        if (!$this->getFromOCache('request')) {
            $this->putToOCache('request',new FotostranaRequest());
        }
        return $this->getFromOCache('request');
    }

    function loadData()
    {

    }

    function flushData()
    {
        $this->data = array();
    }

    function __get($key)
    {
        try
        {
            $this->lastError = null;
            if (!isset($this->data[$key]) || !$this->data[$key]) {
                $this->loadData();
            }
            if (!is_array($this->data)) {
                $this->data = array();
            }
            if (array_key_exists($key, $this->data)) {
                return $this->getByKey($key);
            }
        }
        catch (FotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }

    protected function getByKey($key)
    {
        return $this->data[$key];
    }

}