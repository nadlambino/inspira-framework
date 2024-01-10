<?php

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\CommandRegistry as Registry;
use Inspira\Console\Console;
use Inspira\Console\Exceptions\DuplicateCommandException;

class CommandRegistry
{
	/**
	 * @throws DuplicateCommandException
	 */
	public function __construct(protected Console $console, protected Registry $registry)
	{
		$this->registry->addCommand('run', Application::class);
		$this->registry->addCommand('make', Make::class);
		$this->registry->addCommand('view', View::class);
	}
}
