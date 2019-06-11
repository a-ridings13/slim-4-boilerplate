<?php declare(strict_types = 1);

namespace App\Library\Http\Exceptions;

use App\Library\Http\Request;
use App\Library\Http\Response;
use App\Library\Http\StatusCode;

class NotFoundException extends HttpException
{
    public function __construct(Request $request, Response $response, string $message = null)
    {
        parent::__construct($request, $response, $message ?? 'The requested resource could not be found.');
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return StatusCode::HTTP_NOT_FOUND;
    }
}
