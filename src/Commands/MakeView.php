<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;

class MakeView extends Command
{
	use FileCreator;

	protected string $description = "Make a view file or component class";

	protected array $requires = ['name'];

	public function run()
	{
		$name = $this->input->getArgument('name');
		$withComponent = $this->input->getArgument('c');

		if ($withComponent) {
			$this->create('view.component', $name, app_path('Views'));
		}

		$this->create('view', pascal_to_kebab($name), base_path('assets/views'));
	}
}
