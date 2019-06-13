<?php declare(strict_types = 1);

namespace App\Library\Application\Handlers;

use App\Library\Http\{Exceptions\HttpException, Request, Response, ResponseFactory, StatusCode};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Twig\Error\{LoaderError, RuntimeError, SyntaxError};

final class NotFoundHandler extends Handler
{

    /**
     * @param ServerRequestInterface|Request $request
     * @param \Throwable|HttpException $exception
     * @param bool $displayErrorDetails
     * @param bool $logErrors
     * @param bool $logErrorDetails
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(
        ServerRequestInterface $request,
        \Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $response = $exception->response ?? ResponseFactory::factory(StatusCode::HTTP_NOT_FOUND);

        if ($logErrors) {
            $this->container->log->warning('File not found: ' . $request->getUri());
        }

        if ($request->isXhr()) {
            return $response->withJson([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }

        return $response->write($this->container->view->render('Errors/404'));
    }
}
