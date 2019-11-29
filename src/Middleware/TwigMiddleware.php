<?php
declare(strict_types=1);

/**
 * Slim Framework (http://slimframework.com)
 *
 * @license   https://github.com/slimphp/Twig-View/blob/master/LICENSE.md (MIT License)
 */

namespace App\Middleware;

use ArrayAccess;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;


class TwigMiddleware implements MiddlewareInterface
{
    /**
     * @var Twig
     */
    protected $twig;
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var RouteParserInterface
     */
    protected $routeParser;
    /**
     * @var string
     */
    protected $basePath;
    /**
     * @param Twig                 $twig
     * @param ContainerInterface   $container
     * @param RouteParserInterface $routeParser
     * @param string               $basePath
     */
    public function __construct(Twig $twig, ContainerInterface $container, RouteParserInterface $routeParser, string $basePath = '')
    {
        $this->twig = $twig;
        $this->container = $container;
        $this->routeParser = $routeParser;
        $this->basePath = $basePath;
    }
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $extension = new TwigExtension($this->routeParser, $request->getUri(), $this->basePath);
        $this->twig->addExtension($extension);
        if (method_exists($this->container, 'set')) {
            $this->container->set('view', $this->twig);
        } elseif ($this->container instanceof ArrayAccess) {
            $this->container['view'] = $this->twig;
        }
        return $handler->handle($request);
    }
}