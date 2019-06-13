<?php declare(strict_types = 1);

namespace App\Library\Http;

use Slim\Psr7\Request as SlimRequest;

/**
 * Class Request
 */
final class Request extends SlimRequest
{

    /**
     * @var array|null
     */
    private $rawInput;

    /**
     * Is this an XHR request?
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return bool
     */
    public function isXhr(): bool
    {
        return $this->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * Fetch request parameter value from body or query string (in that order).
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @param  string $key     The parameter key.
     * @param  mixed  $default The default value.
     *
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        $postParams = $this->getParsedBody();
        $getParams = $this->getQueryParams();
        $result = $default;
        if (is_array($postParams) && isset($postParams[$key])) {
            $result = $postParams[$key];
        } elseif (is_object($postParams) && property_exists($postParams, $key)) {
            $result = $postParams->$key;
        } elseif (isset($getParams[$key])) {
            $result = $getParams[$key];
        }
        return $result;
    }

    /**
     * Fetch associative array of body and query string parameters.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @param array|null $only list the keys to retrieve.
     *
     * @return array|null
     */
    public function getParams(array $only = null): ?array
    {
        $params = $this->getQueryParams();
        $postParams = $this->getParsedBody();
        $bodyParams = $this->jsonDecodeBody();

        if ($postParams) {
            $params = array_replace($params, (array)$postParams);
        }

        if ($bodyParams !== null) {
            $params = array_merge($params, $bodyParams);
        }

        if ($only) {
            $onlyParams = [];
            foreach ($only as $key) {
                if (array_key_exists($key, $params)) {
                    $onlyParams[$key] = $params[$key];
                }
            }
            return $onlyParams;
        }
        return $params;
    }

    /**
     * Use with caution input is not sanitized
     *
     * @return array|null
     */
    public function getRawParams(): ?array
    {
        return $this->rawInput;
    }

    /**
     * @param array $params
     * @return Request
     */
    public function withRaw(array $params): self
    {
        $clone = clone $this;
        $clone->rawInput = $params;
        return $clone;
    }

    /**
     * @return array|null
     */
    private function jsonDecodeBody(): ?array
    {
        $body = $this->getBody()->getContents();
        return json_decode($body, true);
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return strtoupper($this->getMethod()) === 'POST';
    }

    /**
     * @return bool
     */
    public function isPut(): bool
    {
        return strtoupper($this->getMethod()) === 'PUT';
    }

    /**
     * @return bool
     */
    public function isGet(): bool
    {
        return strtoupper($this->getMethod()) === 'GET';
    }
}
