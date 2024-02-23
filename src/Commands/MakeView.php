<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;
use function Inspira\Utils\to_kebab;

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
			$view =  'components/' . to_kebab($name);
		}

		$this->create('view', $view ?? to_kebab($name), base_path('assets/views'));
	}
}
