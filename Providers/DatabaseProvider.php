<?php

declare(strict_types=1);

namespace Inspira\Framework\Providers;

use PDO;
use Inspira\Config\Config;
use Inspira\Database\Builder\Query;
use Inspira\Database\Connection;
use Inspira\Database\Connectors\MySql;
use Inspira\Database\Connectors\PgSql;
use Inspira\Database\Connectors\Sqlite;
use Inspira\Framework\Application;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\Inflector\InflectorInterface;

class DatabaseProvider extends Provider
{
	public function register()
	{
		$this->app->singleton(PDO::class, fn() => (new Connection(Application::getInstance(), Config::getInstance()))->create());
		$this->app->singleton(InflectorInterface::class, EnglishInflector::class);
		$this->app->bind(Query::class);

		// PDO Connectors
		$this->app->bind('mysql', MySql::class);
		$this->app->bind('pgsql', PgSql::class);
		$this->app->bind('sqlite', Sqlite::class);
	}
}
