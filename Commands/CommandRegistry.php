<?php

namespace Inspira\Framework\Commands;

use Inspira\Console\Console;

class CommandRegistry
{
	public function __construct(protected Console $console)
	{
		$this->console->command('run', Application::class);
		$this->console->command('make', Make::class);
		$this->console->command('view', View::class);
	}
}
