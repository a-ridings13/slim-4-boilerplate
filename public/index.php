<?php declare(strict_types = 1);

require '../vendor/autoload.php';

use App\Controllers\Web\IndexController;
use App\Library\Application\App;
use App\Library\Http\RequestFactory;

$app = App::factory();

$app->get('[/]', IndexController::class . ':getAction');

$app->run(RequestFactory::createFromGlobals());
