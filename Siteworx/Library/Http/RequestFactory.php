<?php declare(strict_types = 1);

namespace App\Library\Http;

use Slim\Psr7\Cookies;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Factory\UriFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\UploadedFile;

final class RequestFactory
{
    public static function createFromGlobals(): Request
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = (new UriFactory())->createFromGlobals($_SERVER);

        $headers = Headers::createFromGlobals();
        $cookies = Cookies::parseHeader($headers->getHeader('Cookie'));

        $body = (new StreamFactory())->createStream();
        $body->write(file_get_contents('php://input'));
        $body->rewind();

        $uploadedFiles = UploadedFile::createFromGlobals($_SERVER);

        $request = new Request($method ?? 'GET', $uri, $headers, $cookies, $_SERVER, $body, $uploadedFiles);
        $contentTypes = $request->getHeader('Content-Type') ?? [];

        $parsedContentType = '';

        foreach ($contentTypes as $contentType) {
            $fragments = explode(';', $contentType);
            $parsedContentType = current($fragments);
        }

        $contentTypesWithParsedBodies = ['application/x-www-form-urlencoded', 'multipart/form-data'];

        if ($method === 'POST' && in_array($parsedContentType, $contentTypesWithParsedBodies, true)) {
            return $request->withParsedBody($_POST);
        }

        return $request;
    }
}
