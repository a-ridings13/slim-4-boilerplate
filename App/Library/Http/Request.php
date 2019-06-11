<?php declare(strict_types = 1);

namespace App\Library\Http;

use Slim\Psr7\Request as SlimRequest;

/**
 * Class Request
 */
final class Request extends SlimRequest
{
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
}
