<?php

namespace EBMQ\Tests\Factory;

use EBMQ\Base\Application;
use EBMQ\Tests\Application\UserApplication;

class ApplicationFactory
{
    public static function create(Int $id): Application
    {
        return new UserApplication($id);
    }
}
