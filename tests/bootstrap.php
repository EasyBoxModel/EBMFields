<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use EBMApp\Tests\Migration\Bootstrap;

require 'vendor/autoload.php';

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => ':memory:',
    'prefix' => ''
  ]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Run Migrations
Bootstrap::run();
