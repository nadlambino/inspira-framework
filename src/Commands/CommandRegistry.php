<?php

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\CommandRegistry as Registry;
use Inspira\Console\Exceptions\DuplicateCommandException;

class CommandRegistry extends Registry
{
	/**
	 * @throws DuplicateCommandException
	 */
	public function __construct()
	{
		$this->addCommand('app:serve', ApplicationServer::class);
		$this->addCommand('make:controller', MakeController::class);
		$this->addCommand('make', Make::class);
		$this->addCommand('view', View::class);
	}
}
