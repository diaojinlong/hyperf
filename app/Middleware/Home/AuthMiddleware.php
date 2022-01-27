<?php

declare(strict_types=1);

namespace App\Middleware\Home;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Service\JwtService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Service\Model\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * @Inject
     * @var UserService
     */
    protected $userService;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = str_replace('Bearer ', '', $request->getHeaderLine('authorization'));
        $data = JwtService::decode($token);
        if (!isset($data['user_id']) || $data['token_type'] != 'access') {
            throw new BusinessException(ErrorCode::ERR_INVALID_TOKEN);
        }
        $user = $this->userService->getInfoById($data['user_id']);
        $request = $request->withAttribute('user', $user);
        $request = Context::set(ServerRequestInterface::class, $request);
        return $handler->handle($request);
    }
}