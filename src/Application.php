<?php

declare(strict_types=1);

namespace Inspira\Framework;

use Composer\Autoload\ClassLoader;
use Exception;
use Inspira\Container\Container;
use Inspira\Framework\Traits\Path;
use Inspira\Framework\Traits\Provider;

/**
 * @author Ronald Lambino
 */
class Application extends Container
{
    use Path, Provider;

	/**
	 * Whether the app is running on console or on web
	 *
	 * @var bool $isConsoleApp
	 */
	private bool $isConsoleApp = false;

	/**
	 * Application constructor
	 */
	public function __construct(private ?ClassLoader $loader = null)
	{
		$this->singleton(Application::class, fn() => $this);
		$this->singleton(Container::class, fn() => $this);
		parent::__construct();
	}

	/**
     * Boot the application
     *
	 * @return self
	 * @throws
	 */
	public function boot(): self
	{
		if (!$this->loader && file_exists($vendor = $this->getBasePath() . '/vendor/autoload.php')) {
			$this->loader = require $vendor;
		}

		$this->addProviders($this->getUserProviders());

		foreach ($this->getProviders() as $provider) {
			$this->singleton($provider);

			$this->make($provider)->register();
		}

		return $this;
	}

	/**
     * Start the application
     *
	 * @throws Exception
	 */
	public function start(): void
	{
		ob_start();
		foreach ($this->getProviders() as $provider) {
			$this->make($provider)->start();
		}

		$this->validatePaths();
	}

    /**
     * Run the application on console
     *
     * @return static
     */
	public function runOnConsole(): static
	{
		$this->isConsoleApp = true;

		return $this;
	}

    /**
     * Check if the application is running on console
     *
     * @return bool
     */
	public function isConsoleApp(): bool
	{
		return $this->isConsoleApp;
	}
}
