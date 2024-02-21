<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;

class MakeModel extends Command
{
	use FileCreator;

	protected string $description = "Make a model class.";

	protected array $requires = ['name'];

	public function run()
	{
		$name = $this->input->getArgument('name');
		$directory = app_path('Models');

		$this->create('model', $name, $directory);
	}
}
