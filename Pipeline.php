<?php

declare(strict_types=1);

namespace Inspira\Framework;

use Inspira\Collection\Collection;
use Inspira\Container;
use Inspira\Contracts\RenderableException;
use Inspira\Http\Middlewares\BaseMiddleware;
use Inspira\Http\Middlewares\Middleware;
use Inspira\Http\Router\Route;
use Inspira\Http\Router\Router;
use Inspira\Http\Status;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Pipeline
{
	/** @var MiddlewareInterface[] */
	protected array $middlewares = [
		BaseMiddleware::class,
	];

	public function __construct(protected Application $application, protected Router $router)
	{
		$this->registerMiddlewares();
	}

	/**
	 * @param ServerRequestInterface $request
	 * @param RequestHandlerInterface $handler
	 * @return ResponseInterface
	 * @throws Container\Exceptions\NonInstantiableBindingException
	 * @throws Container\Exceptions\UnresolvableBindingException
	 * @throws Container\Exceptions\UnresolvableBuiltInTypeException
	 * @throws Container\Exceptions\UnresolvableMissingTypeException
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		/** Process global middlewares */
		$response = $this->processMiddlewares($request, $handler, $this->middlewares, true);
		if ($response) {
			return $response;
		}

		$route = $this->router->getCurrentRoute();

		// $route could be an instance of RouteNotFoundException or MethodNotAllowedException, which both extends the RenderableException class
		if ($route instanceof RenderableException) {
			$response = $this->application->make(ResponseInterface::class);
			return $response->withStatus($route->getCode())->content($route->render());
		}

		/** Process route middlewares */
		$middlewares = $route instanceof Route ? $route->getMiddlewares() : [];
		$response = $this->processMiddlewares($request, $handler, array_unique($middlewares), false);
		if ($response) {
			return $response;
		}

		return $handler->handle($request);
	}

	/**
	 * @param ServerRequestInterface $request
	 * @param RequestHandlerInterface $handler
	 * @param array $middlewares
	 * @param bool $global
	 * @return ResponseInterface|null
	 * @throws Container\Exceptions\NonInstantiableBindingException
	 * @throws Container\Exceptions\UnresolvableBindingException
	 * @throws Container\Exceptions\UnresolvableBuiltInTypeException
	 * @throws Container\Exceptions\UnresolvableMissingTypeException
	 */
	private function processMiddlewares(ServerRequestInterface $request, RequestHandlerInterface $handler, array $middlewares, bool $global): ?ResponseInterface
	{
		foreach ($middlewares as $middleware) {
			/** @var Middleware $middlewareInstance */
			$middlewareInstance = $this->application->make($middleware);
			if ($global && isset($middlewareInstance->global) && $middlewareInstance->global === false) {
				continue;
			}

			$request->appendMiddleware($middleware, $global);
			$response = $middlewareInstance->process($request, $handler);
			if ($this->shouldNotProceed($response)) {
				return $response;
			}
		}

		return null;
	}

	private function registerMiddlewares(): void
	{
		$files = glob($this->application->getMiddlewaresPath() . DIRECTORY_SEPARATOR . '*.php');
		$middlewares = [];

		foreach ($files as $file) {
			require $file;
			$classes = get_declared_classes();
			$middlewares = [
				...$middlewares,
				...(new Collection($classes))
					->whereLike(null,'App\Middlewares')
					->toArray()
			];
		}

		$this->middlewares = [...$this->middlewares, ...$middlewares];
	}

	private function shouldNotProceed(ResponseInterface $response): bool
	{
		$status = $response->getStatusCode();

		return $status >= Status::BAD_REQUEST->value || $status === Status::NO_CONTENT->value;
	}
}
