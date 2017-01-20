<?php 

namespace EBM\Field\Tests\Migration;

class Bootstrap 
{
    public static function run()
    {
        User::migrate();
        PlaceOfBirth::migrate();
    }
}