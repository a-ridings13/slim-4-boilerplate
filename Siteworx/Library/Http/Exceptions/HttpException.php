<?php declare(strict_types = 1);

namespace App\Library\Http\Exceptions;

use App\Library\Http\Request;
use App\Library\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

abstract class HttpException extends \Exception implements ErrorHandlerInterface
{

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Response
     */
    public $response;

    public function __construct(
        Request $request,
        Response $response,
        string $message = null,
        $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message ?? '', $code, $previous);
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return int
     */
    abstract public function getStatusCode(): int;

    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        return $this->response;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
