<?php declare(strict_types = 1);

namespace App\Library\Application\Handlers;

use App\Library\Application\Container;
use Slim\Interfaces\ErrorHandlerInterface;

abstract class Handler implements ErrorHandlerInterface
{

    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
