<?php 

namespace EBMApp\Tests\Migration;

class Bootstrap 
{
    public static function run()
    {
        User::migrate();
        PlaceOfBirth::migrate();
    }
}