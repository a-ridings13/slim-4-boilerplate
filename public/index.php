<?php declare(strict_types = 1);

require '../vendor/autoload.php';

use App\Library\Application\App;
use App\Library\Http\RequestFactory;
use Whoops\Handler\PrettyPageHandler;

/*
|--------------------------------------------------------------------------
| Get Ready....
|--------------------------------------------------------------------------
*/
$app = App::factory();

/*
|--------------------------------------------------------------------------
| Go.....
|--------------------------------------------------------------------------
*/
try {
    $app->run(RequestFactory::createFromGlobals());
} catch (\Exception $exception) {
    $app->getContainer()->log->emergency($exception->getMessage() . ' in file ' . $exception->getFile() . ' on line ' . $exception->getLine());

    if ($app->getContainer()->config->get('dev_mode')) {
        $handler = new PrettyPageHandler();
        $whoops = new Whoops\Run();
        $whoops->pushHandler($handler);
        $whoops->handleException($exception);
        exit();
    }

    echo 'Server Error.';
}
