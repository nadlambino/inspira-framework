<?php

declare(strict_types=1);

namespace Inspira\Framework\Providers;

use Inspira\Http\Handler;
use Inspira\Http\Request;
use Inspira\Http\Response;
use Inspira\Http\Router\Router;
use Inspira\Http\Router\RoutesRegistry;
use Inspira\Http\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HttpProvider extends Provider
{
	/**
	 * Routes filename
	 *
	 * @var string $routeFile
	 */
	private string $routeFile = 'routes.php';

	public function register()
	{
		$this->app->singleton(UriInterface::class, Uri::class);
		$this->app->singleton(ServerRequestInterface::class, Request::class);
		$this->app->singleton(RequestHandlerInterface::class, Handler::class);
		$this->app->singleton(ResponseInterface::class, Response::class);
		$this->app->singleton(Response::class);
		$this->app->singleton(Request::class);
		$this->app->singleton(Router::class);
	}

	/**
	 * @throws
	 */
	public function start()
	{
		$routes = $this->app->getRoutesPath() . DIRECTORY_SEPARATOR . $this->routeFile;
		RoutesRegistry::register($routes, $this->app->make(Router::class));
	}
}
