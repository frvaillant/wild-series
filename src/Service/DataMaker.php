<?php


namespace App\Service;


class DataMaker
{

    public static function makeDataFromPost($post): ?object
    {
        $data = null;
        if (!is_null($post) && !empty($post)) {
            $data = json_decode($post);
        }
        return $data;
    }

}