<?php
namespace PetrovDAUtils\Request;


use PetrovDAUtils\Enums\EnumsConfig;

/**
 * ¬спомогательный класс подсчЄта количества запросов, чтобы соблюдать ограничени€ API ‘отостраны
 */
class RequestCounter
{
    static private $queries = [];

    const MAX_QUERIES=20; // 20 запросов
    const PER_TIME=10; // за 10 секунд

    static function addQuery()
    {
        self::$queries[time()] = '';
    }

    static function removeQuery($t)
    {
        unset (self::$queries[$t]);
    }

    static function countQueries()
    {
        return count(self::$queries);
    }

    static function agingQueries()
    {
        foreach (self::$queries as $q => $v) {
            if ($q < (time() - self::PER_TIME)) {
                unset (self::$queries[$q]);
            }
        }
    }

    static function wait()
    {
        if (EnumsConfig::FOTOSTRANA_DEBUG) { echo ("Query timeout check: query count ".self::countQueries().", max queries ".self::MAX_QUERIES." <br>\n"); }
        while (self::countQueries() >= self::MAX_QUERIES) {
            if (EnumsConfig::FOTOSTRANA_DEBUG) { echo ("MAX_QUERIES reached (query count ".self::countQueries()."), wait..<br/>\n"); }
            self::agingQueries();
            sleep(1);
        }
    }
}