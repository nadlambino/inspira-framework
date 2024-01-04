<?php

declare(strict_types=1);

namespace Inspira\Framework;

use Exception;
use Inspira\Container\Container;
use Inspira\Framework\Providers\ApplicationProvider;
use Inspira\Framework\Providers\DatabaseProvider;
use Inspira\Framework\Providers\HttpProvider;
use Inspira\Http\Request;

/**
 * @author Ronald Lambino
 */
class Application extends Container
{
	/**
	 * Whether the app is running on console or on web
	 *
	 * @var bool $isConsoleApp
	 */
	private bool $isConsoleApp = false;

	/**
	 * The base path where the application is installed
	 * The default value is one directory up because the
	 * app bootstrap file is located at `/bootstrap/app.php`
	 *
	 * @var string $basePath
	 */
	private string $basePath = '..';

	/**
	 * The path where the routes.php is located
	 * The value will be prefixed by the $basePath
	 *
	 * @var string $routesPath
	 */
	private string $routesPath = '/app';

	/**
	 * The path where the config files are located
	 * The value will be prefixed by the $basePath
	 *
	 * @var string $configsPath
	 */
	private string $configsPath = '/configs';

	/**
	 * The path where the database files are located (sqlite, migrations)
	 * The value will be prefixed by the $basePath
	 *
	 * @var string $databasePath
	 */
	private string $databasePath = '/database';

	/**
	 * The path where the views file are located
	 * The value will be prefixed by the $basePath
	 *
	 * @var string $viewsPath
	 */
	private string $viewsPath = '/resources/views';

	/**
	 * The path where the cache files are located
	 * The value will be prefixed by the $basePath
	 *
	 * @var string $cachePath
	 */
	private string $cachePath = '/cache';

	/**
	 * The path where the middleware files are located
	 * The value will be prefixed by the $basePath
	 *
	 * @var string $middlewaresPath
	 */
	private string $middlewaresPath = '/app/Middlewares';

	private array $providers = [
		ApplicationProvider::class,
		HttpProvider::class,
		DatabaseProvider::class
	];

	/**
	 * Application constructor
	 */
	public function __construct()
	{
		$this->singleton(Application::class, fn() => $this);
		$this->singleton(Container::class, fn() => $this);
		parent::__construct();
	}

	/**
	 * @return self
	 * @throws
	 */
	public function boot(): self
	{
		foreach ($this->providers as $provider) {
			$this->singleton($provider);

			$this->make($provider)->register();
		}

		return $this;
	}

	/**
	 * @throws
	 */
	public function start(): void
	{
		ob_start();
		foreach ($this->providers as $provider) {
			$this->make($provider)->start();
		}

		$this->validatePaths();
	}

	public function runAppOnConsole(): static
	{
		$this->isConsoleApp = true;

		return $this;
	}

	public function isConsoleApp(): bool
	{
		return $this->isConsoleApp;
	}

	/**
	 * Get application base url
	 */
	public function getBaseUrl(): string
	{
		try {
			/** @var Request $request*/
			$request = $this->make(Request::class);
			$url = (string) $request->getUri();
		} catch (Exception) {
			$url = $_SERVER['name'] ?? 'localhost';
		}

		$components = parse_url($url);

		$scheme = isset($components['scheme']) ? $components['scheme'] . '://' : '';
		$host = $components['host'] ?? 'localhost';
		$port = isset($components['port']) && $components['port'] != '80' ? ':' . $components['port'] : '';

		return $scheme . $host . $port;
	}

	public function setBasePath(string $path): static
	{
		$this->basePath = rtrim($path);

		return $this;
	}

	public function getBasePath(): string
	{
		return realpath($this->basePath) ?: $this->basePath;
	}

	public function setRoutesPath(string $path): static
	{
		$this->routesPath = trim($path, DIRECTORY_SEPARATOR);

		return $this;
	}

	public function getRoutesPath(): string
	{
		$path = $this->basePath . DIRECTORY_SEPARATOR . $this->routesPath;

		return realpath($path) ?: $path;
	}

	public function setConfigsPath(string $path): static
	{
		$this->configsPath = trim($path, DIRECTORY_SEPARATOR);

		return $this;
	}

	public function getConfigsPath(): string
	{
		$path = $this->basePath . DIRECTORY_SEPARATOR . $this->configsPath;

		return realpath($path) ?: $path;
	}

	public function setDatabasePath(string $path): static
	{
		$this->databasePath = trim($path, DIRECTORY_SEPARATOR);

		return $this;
	}

	public function getDatabasePath(): string
	{
		$path = $this->basePath . DIRECTORY_SEPARATOR . $this->databasePath;

		return realpath($path) ?: $path;
	}

	public function setViewsPath(string $path): static
	{
		$this->viewsPath = trim($path, DIRECTORY_SEPARATOR);

		return $this;
	}

	public function getViewsPath(): string
	{
		$path = $this->basePath . DIRECTORY_SEPARATOR . $this->viewsPath;

		return realpath($path) ?: $path;
	}

	public function setCachePath(string $path): static
	{
		$this->cachePath = trim($path, DIRECTORY_SEPARATOR);

		return $this;
	}

	public function getCachePath(): string
	{
		$path = $this->basePath . DIRECTORY_SEPARATOR . $this->cachePath;

		return realpath($path) ?: $path;
	}

	public function setMiddlewaresPath(string $path): static
	{
		$this->middlewaresPath = trim($path, DIRECTORY_SEPARATOR);

		return $this;
	}

	public function getMiddlewaresPath(): string
	{
		$path = $this->basePath . DIRECTORY_SEPARATOR . $this->middlewaresPath;

		return realpath($path) ?: $path;
	}

	/**
	 * @return void
	 * @throws Exception
	 */
	private function validatePaths(): void
	{
		$paths = [
			$this->getBasePath(),
			$this->getRoutesPath(),
			$this->getConfigsPath(),
			$this->getDatabasePath(),
			$this->getViewsPath()
		];

		foreach ($paths as $path) {
			if (!is_dir($path)) {
				throw new Exception("Directory `$path` does not exists. Configure the application directories properly.");
			}
		}
	}
}
