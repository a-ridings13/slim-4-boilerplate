<?php declare(strict_types = 1);

namespace App\Library\Application\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class InvalidContainerItemException extends \Exception implements NotFoundExceptionInterface
{

}
