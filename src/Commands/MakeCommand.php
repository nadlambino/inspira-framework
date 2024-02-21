<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;

class MakeCommand extends Command
{
	use FileCreator;

	protected string $description = "Make a command class.";

	protected array $requires = ['name'];

	public function run()
	{
		$name = $this->input->getArgument('name');
		$directory = base_path('console/Commands');

		$this->create('command', $name, $directory);
	}
}
