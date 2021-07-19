<?php
namespace PetrovDAUtils\Models;
/**
 * ����� ������� �������� ������� ������ (����������� php-�������� � �.�.)
 */
abstract class FotostranaBase
{

    protected $ocache = array(); // ��� �������� php

    function getFromOCache($key)
    {
        if (array_key_exists($key, $this->ocache)) {
            return $this->ocache[$key];
        }
    }

    function putToOCache($key, $object)
    {
        $this->ocache[$key]=$object;
    }

}
