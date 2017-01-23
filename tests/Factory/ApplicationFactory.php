<?php

namespace EBMApp\Tests\Factory;

use EBMApp\Base\Application;
use EBMApp\Tests\Application\UserApplication;

class ApplicationFactory
{
    public static function create(Int $id): Application
    {
        return new UserApplication($id);
    }
}
