<?php declare(strict_types = 1);

namespace App\Controllers;

use App\Library\Http\{Request, Response};

/**
 * Interface ControllerInterface
 * @package App\Controllers
 */
interface ControllerInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function getAction(Request $request, Response $response, array $params = []): Response;

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function postAction(Request $request, Response $response, array $params = []): Response;

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function putAction(Request $request, Response $response, array $params = []): Response;

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function deleteAction(Request $request, Response $response, array $params = []): Response;
}
