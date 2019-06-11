<?php declare(strict_types = 1);

namespace App\Library\Http;

use Slim\Psr7\Response as SlimResponse;

/**
 * Class Response
 * @package App\Library\Http
 */
final class Response extends SlimResponse
{
    /**
     * @param $contents
     * @return Response
     */
    public function write($contents): self
    {
        $clone = clone $this;
        $clone->getBody()->write($contents);

        return $clone;
    }

    /**
     * Json.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * This method prepares the response object to return an HTTP Json
     * response to the client.
     *
     * @param  mixed $data   The data
     * @param  int   $status The HTTP status code.
     * @param  int   $encodingOptions Json encoding options
     *
     * @return static
     *
     * @throws \RuntimeException
     */
    public function withJson($data, $status = null, $encodingOptions = 0)
    {
        $response = $this->write($json = json_encode($data, $encodingOptions));

        // Ensure that the json encoding passed successfully
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        $responseWithJson = $response->withHeader('Content-Type', 'application/json');

        if (isset($status)) {
            return $responseWithJson->withStatus($status);
        }

        return $responseWithJson;
    }
}
