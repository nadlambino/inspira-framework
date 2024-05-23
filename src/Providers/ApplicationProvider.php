<?php

declare(strict_types=1);

namespace Inspira\Framework\Providers;

use Inspira\Config\Config;
use Inspira\Config\Env;
use Inspira\ErrorPage\ErrorPage;
use Inspira\View\Directives;
use Inspira\View\View;

class ApplicationProvider extends Provider
{
    public function register() : void
    {
        $this->registerErrorPage();

        $this->app->setResolved(Env::class, new Env($this->app->getBasePath()));
        $this->app->setResolved(Config::class, new Config($this->app->getConfigsPath()));
        $this->registerView();

        $this->app->singleton(Config::class);
        $this->app->singleton(Env::class);
        $this->app->singleton(View::class);
    }

    public function start() : void
    {
        date_default_timezone_set(Config::get('app.timezone', 'UTC'));
    }

    private function registerErrorPage() : void
    {
        $debug = filter_var(Env::get('APP_DEBUG', true), FILTER_VALIDATE_BOOL);

        (new ErrorPage())
            ->isEnabled($debug)
            ->isRunningOnConsole($this->app->isConsoleApp())
            ->register();
    }

    private function registerView() : void
    {
        $this->app->singleton(Directives::class);

        $this->app->setResolved(View::class, (new View(Config::get('view', []), $this->app)));
    }
}
