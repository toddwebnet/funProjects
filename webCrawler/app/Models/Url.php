<?php
namespace App\Models;

class Url extends ValidModel
{
    protected $fillable = ['url'];

    public static function findUrl($url){
        return self::where('url', $url)->first();
    }
}
