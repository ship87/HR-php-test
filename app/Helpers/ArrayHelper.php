<?php

namespace App\Helpers;

/**
 * Class ArrayHelper - вспомогательный класс для работы с массивами
 * @package App\Helpers
 */
class ArrayHelper
{
    /**
     * Получить из массива по ключу и значению
     *
     * @param $items
     * @param $key
     * @param $value
     * @return array
     */
    public static function getByAliasValue($items, $key, $value)
    {
        $result = [];
        foreach ($items as $item) {
            if ($item[$key] === $value) {
                $result = $item;
                break;
            }
        }
        return $result;
    }
}
