<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;

class MakeController extends Command
{
	use FileCreator;

	protected string $description = "Make a controller class";

    protected ?string $argument = 'name';

	public function run() : void
    {
		$name = $this->input->getArgument();
		$directory = app_path('Controllers');

		$this->create('controller', $name, $directory);
	}
}
