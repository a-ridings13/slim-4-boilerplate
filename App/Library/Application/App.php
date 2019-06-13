<?php declare(strict_types = 1);

namespace App\Library\Application;

use App\Controllers\Web\IndexController;
use App\Library\Application\Handlers\{ErrorHandler, NotAuthorizedHandler, NotFoundHandler};
use App\Library\Http\Exceptions\{NotAuthorizedException, NotFoundException};
use App\Library\Http\ResponseFactory;
use App\Middleware\{ApiMiddleware, OAuthMiddleware, SanitizationMiddleware};
use Slim\Exception\HttpNotFoundException;
use Slim\Middleware\ErrorMiddleware;

/**
 * Class App
 *
 * @method Container getContainer()
 *
 * @package App\Library\Application
 */
class App extends \Slim\App
{

    /**
     * @var Container
     */
    protected $container;

    public static function factory(): self
    {
        $responseFactory = new ResponseFactory();
        $container = new Container();
        $app = new static($responseFactory, $container);
        $errorMiddleware = new ErrorMiddleware(
            $app->callableResolver,
            $responseFactory,
            (bool) $container->config->get('dev_mode', false),
            true,
            true
        );

        $errorMiddleware->setErrorHandler(NotFoundException::class, NotFoundHandler::class);
        $errorMiddleware->setErrorHandler(HttpNotFoundException::class, NotFoundHandler::class);
        $errorMiddleware->setErrorHandler(NotAuthorizedException::class, NotAuthorizedHandler::class);
        $errorMiddleware->setDefaultErrorHandler(ErrorHandler::class);
        $app->add($errorMiddleware);

        $app->registerRoutes();

        return $app;
    }

    /**
     * @return App
     */
    private static function app(): self
    {
        return $GLOBALS['app'];
    }

    /**
     * @return Container
     */
    public static function di(): Container
    {
        return self::app()->getAppContainer();
    }

    /**
     * @return Container
     */
    private function getAppContainer(): Container
    {
        return $this->container;
    }

    private function registerRoutes(): void
    {
        $this->get('[/]', IndexController::class . ':getAction');
        $this->add(new ApiMiddleware());
        $this->add(new SanitizationMiddleware());
        $this->add(new OAuthMiddleware());
    }
}
