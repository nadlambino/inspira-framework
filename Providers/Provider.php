<?php

declare(strict_types=1);

namespace Inspira\Framework\Providers;

use Inspira\Framework\Application;

class Provider
{
	public function __construct(protected Application $app) { }
}
