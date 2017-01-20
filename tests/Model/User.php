<?php

namespace EBMQ\Tests\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    public $table = 'users';
}

