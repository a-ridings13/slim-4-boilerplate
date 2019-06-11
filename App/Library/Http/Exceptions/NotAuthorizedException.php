<?php declare(strict_types = 1);

namespace App\Library\Http\Exceptions;

use App\Library\Http\StatusCode;

class NotAuthorizedException extends HttpException
{

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return StatusCode::HTTP_UNAUTHORIZED;
    }
}
