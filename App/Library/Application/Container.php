<?php declare(strict_types = 1);

namespace App\Library\Application;

use App\Library\Twig;
use Carbon\Carbon;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\{MemoryUsageProcessor, WebProcessor};
use Noodlehaus\Config;
use Pimple\Container as Pimple;
use Pimple\Exception\UnknownIdentifierException;
use Psr\Container\{ContainerExceptionInterface, ContainerInterface, NotFoundExceptionInterface};
use App\Library\Application\Exceptions\InvalidContainerItemException;
use Psr\Log\LogLevel;
use Twig\Loader\FilesystemLoader;

/**
 * Class Container
 *
 * @property Config config
 * @property Twig view
 * @property Logger log
 *
 * @package Siteworx\App\Library
 */
final class Container extends Pimple implements ContainerInterface
{

    /**
     * @var bool
     */
    private $booted = false;

    public function __construct(array $values = array())
    {
        parent::__construct($values);

        if ($this->booted === false) {
            $this->bootstrap();
        }
    }

    private function bootstrap(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Config
        |--------------------------------------------------------------------------
        */
        $this['config'] = function () {
            $path = __DIR__ . '/../../../var/config/config.php';

            return Config::load($path);
        };

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */
        $this['view'] = function () {
            $loader = new FilesystemLoader($this->config->get('run_dir') . '/App/Views');
            $twig = new Twig($loader, [
                'cache' => $this->config->get('run_dir') . '/var/cache/views',
                'auto_reload' => $this->config->get('dev_mode', false)
            ]);
            $twig->addGlobal('config', $this->config);
            $twig->addGlobal('year', Carbon::now()->format('Y'));

            return $twig;
        };

        /*
        |--------------------------------------------------------------------------
        | Log
        |--------------------------------------------------------------------------
        */
        $this['log'] = function () {
            $logger = new Logger('App');

            $formatter = new LineFormatter(
                null,
                null,
                true,
                true
            );

            $handler = new StreamHandler(
                $this->config->get('run_dir') . '/var/log/app.log',
                $this->config->get('log.log_level', LogLevel::DEBUG)
            );
            $handler->setFormatter($formatter);

            $logger->pushHandler($handler);

            $logger->pushProcessor(new WebProcessor());
            $logger->pushProcessor(new MemoryUsageProcessor());

            return $logger;
        };

        $this->booted = true;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed Entry.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @throws InvalidContainerItemException|NotFoundExceptionInterface  No entry was found for **this** identifier.
     */
    public function get($id)
    {
        try {
            return $this->offsetGet($id);
        } catch (UnknownIdentifierException $exception) {
            throw new InvalidContainerItemException($exception);
        }
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id): bool
    {
        try {
            return $this->offsetGet($id) !== null;
        } catch (UnknownIdentifierException $exception) {
            return false;
        }
    }

    public function __isset($name): bool
    {
        return $this->has($name);
    }

    public function __set($name, $value)
    {
    }

    /**
     * @param $name
     * @return mixed
     * @throws InvalidContainerItemException
     */
    public function __get($name)
    {
        return $this->get($name);
    }
}
