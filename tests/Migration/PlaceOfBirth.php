<?php

namespace EBMQ\Tests\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class PlaceOfBirth
{

    public static function migrate()
    {
        $schema = Capsule::schema();
        $schema->create('place_of_birth', function(Blueprint $table){
            $table->increments('id');
            $table->string('description');
        });

        Capsule::table('place_of_birth')->insert([
            'description' => 'mexico',
        ]);
    }

    /**
    * @description Removes auto increments and erases all rows
    */
    static function reset()
    {
        Capsule::table('place_of_birth')->truncate();
    }
}
