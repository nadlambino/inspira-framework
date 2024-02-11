<?php

declare(strict_types=1);

namespace Inspira\Framework\Providers;

use Inspira\Config\Config;
use Inspira\Database\QueryBuilder\Query;
use Inspira\Database\ConnectionPool;
use Inspira\Database\Drivers\MySqlDriver;
use Inspira\Database\Drivers\PgSqlDriver;
use Inspira\Database\Drivers\Sqlite;
use Inspira\Framework\Application;
use PDO;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\Inflector\InflectorInterface;

class DatabaseProvider extends Provider
{
	public function register(): void
	{
		$this->app->singleton(PDO::class, fn() => (new ConnectionPool(Application::getInstance(), Config::getInstance()->get('database')))->create());
		$this->app->singleton(InflectorInterface::class, EnglishInflector::class);
		$this->app->bind(Query::class);
	}
}
