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
		$this->addCommand('run', Application::class);
		$this->addCommand('make', Make::class);
		$this->addCommand('view', View::class);
	}
}
