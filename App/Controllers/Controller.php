<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Library\Http\{Exceptions\NotFoundException, Request, Response};

/**
 * Class Controller
 */
abstract class Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function getAction(Request $request, Response $response, array $params = []): Response
    {
        throw new NotFoundException($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function postAction(Request $request, Response $response, array $params = []): Response
    {
        throw new NotFoundException($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function putAction(Request $request, Response $response, array $params = []): Response
    {
        throw new NotFoundException($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function deleteAction(Request $request, Response $response, array $params = []): Response
    {
        throw new NotFoundException($request, $response);
    }
}
