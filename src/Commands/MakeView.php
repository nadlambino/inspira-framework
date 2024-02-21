<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;

class MakeView extends Command
{
	use FileCreator;

	protected array $requires = ['name'];

	public function run()
	{
		$name = $this->input->getArgument('name');
		$isComponent = $this->input->getArgument('c');
		$type = 'view';
		$type .= $isComponent ? '.component' : '';
		$directory = $isComponent ? app_path('Views') : base_path('assets/views');

		$this->create($type, $name, $directory);
	}
}
