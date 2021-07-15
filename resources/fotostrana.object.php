<?php

/**
 * Класс абстрактного объекта API Фотостраны (пользователя, изображения, стены и т.д.)
 */
class fotostranaObject extends fotostranaBase
{

    public $lastError = null;
    protected $data = array();

    function request()
    {
        if (!$this->getFromOCache('request')) {
            $this->putToOCache('request',new fotostranaRequest());
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
        catch (fotostranaError $e)
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

?>