<?php

declare(strict_types=1);

namespace Inspira\Framework\Providers;

use Inspira\Framework\Application;

abstract class Provider
{
	public function __construct(protected Application $app) { }

	public function register(): void { }

	public function start(): void { }
}
