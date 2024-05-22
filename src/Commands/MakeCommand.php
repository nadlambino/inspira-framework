<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;

class MakeCommand extends Command
{
	use FileCreator;

	protected string $description = "Make a command class";

	protected ?string $argument = 'name';

	public function run() : void
    {
		$name = $this->input->getArgument();
		$directory = base_path('console/Commands');

		$this->create('command', $name, $directory);
	}
}
