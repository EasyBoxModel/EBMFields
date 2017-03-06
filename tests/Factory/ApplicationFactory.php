<?php

namespace EBMApp\Tests\Factory;

use EBMApp\Base\AbstractApplication;
use EBMApp\Tests\Application\UserApplication;

class ApplicationFactory
{
    public static function create(Int $id): AbstractApplication
    {
        return new UserApplication($id);
    }
}
