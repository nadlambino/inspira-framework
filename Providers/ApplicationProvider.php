<?php

declare(strict_types=1);

namespace Inspira\Framework\Providers;

use Inspira\Config\Config;
use Inspira\Config\Env;
use Inspira\ErrorPage\ErrorPage;
use Inspira\Framework\Application;
use Inspira\View\View;

class ApplicationProvider extends Provider
{
	public function register(): void
	{
		$this->app->setResolved(Env::class, new Env($this->app->getBasePath()));
		$this->app->setResolved(Config::class, new Config($this->app->getConfigsPath()));
		$this->app->singleton(Config::class);
		$this->app->singleton(Env::class);
		$this->app->bind(View::class, fn() => new View(
			$this->app->getViewsPath(),
			$this->app->getCachePath(),
			Config::get('app.views.use_cached', true)
		));
	}

	public function start(): void
	{
		$debug = filter_var(Env::get('APP_DEBUG', true), FILTER_VALIDATE_BOOL);
		$errorPage = new ErrorPage();
		$errorPage->isEnabled($debug)
			->isRunningOnConsole($this->app->isConsoleApp())
			->register();

		date_default_timezone_set(Config::get('app.timezone', 'UTC'));
	}
}
