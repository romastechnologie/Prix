<?php

namespace App\Services;
use Symfony\Component\HttpFoundation\Request;


class LibrairieService
{
    public function getWhereStatement($keyword, $alias, $columns, $_trouve = false)
    {

    }

    public function reformat($data): array
    {
        return array_map(function ($item) {
            return $item;
        }, $data);
    }

    public function arraySearch($array, $keyword)
    {
        return array_filter($array, function ($a) use ($keyword) {
            return (boolean) preg_grep("/$keyword/i", (array) $a);
        });
    }

    public function filterArray($array, $allowed = [])
    {
        $arr = array_filter(
            $array,
            function ($val, $key) use ($allowed) { // N.b. $val, $key not $key, $val
                return isset($allowed[$key]) && ($allowed[$key] === true || $allowed[$key] === $val);
            },
            ARRAY_FILTER_USE_BOTH
        );

        return $arr;
    }

    public function camelize($input, $separator = '_')
    {
        return str_replace($separator, '', ucwords($input, $separator));
    }

    public function camel2dashed($className) {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $className));
    }
}