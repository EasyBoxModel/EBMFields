<?php 

namespace EBMQ\Tests\Migration;

class Bootstrap 
{
    public static function run()
    {
        User::migrate();
        PlaceOfBirth::migrate();
    }
}