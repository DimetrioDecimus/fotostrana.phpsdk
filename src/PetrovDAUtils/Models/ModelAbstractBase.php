<?php
namespace PetrovDAUtils\Models;
/**
 * Класс базовых операций низкого уровня (кэширование php-объектов и т.д.)
 */
abstract class ModelAbstractBase
{

    /** @var ModelAbstractObject[]  */
    protected $ocache = array(); // кэш объектов php

    /**
     * @param string $key
     * @return mixed
     */
    function getFromOCache(string $key)
    {
        if (array_key_exists($key, $this->ocache)) {
            return $this->ocache[$key];
        }
    }

    /**
     * @param string $key
     * @param mixed $object
     */
    function putToOCache(string $key, mixed $object)
    {
        $this->ocache[$key]=$object;
    }

}
