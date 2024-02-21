<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;

class MakeController extends Command
{
	use FileCreator;

	protected array $requires = ['name'];

	public function run()
	{
		$name = $this->input->getArgument('name');
		$directory = app_path('Controllers');

		$this->create('controller', $name, $directory);
	}
}
