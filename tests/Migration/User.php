<?php

namespace EBMQ\Tests\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class User
{

    public static function migrate()
    {
        $schema = Capsule::schema();
        $schema->create('users', function(Blueprint $table){
            $table->increments('id');
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->integer('place_of_birth_id')->nullable();
        });
    }

    /**
    * @description Removes auto increments and erases all rows
    */
    static function reset()
    {
        Capsule::table('users')->truncate();
    }
}
