<?php
declare(strict_types=1);

mb_internal_encoding('UTF-8');
error_reporting(E_ALL);
ini_set('display_errors', 'stderr');

//Composer
require __DIR__ . '/vendor/autoload.php';

//Initiating shared container, bindings, directories and etc
$app = \Meeting\App::init([
    'root' => __DIR__
]);

if ($app != null) {
    $app->serve();
}
