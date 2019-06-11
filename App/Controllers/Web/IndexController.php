<?php declare(strict_types = 1);

namespace App\Controllers\Web;

use App\Controllers\Controller;
use App\Library\Application\App;
use App\Library\Http\{Request, Response};
use Twig\Error\{LoaderError, RuntimeError, SyntaxError};

final class IndexController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getAction(Request $request, Response $response, array $params = []): Response
    {
        return $response->write(
            App::di()->view->render('Index')
        );
    }
}
